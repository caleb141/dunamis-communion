<?php

namespace Models;

use Helpers\ResponseHelper;

class Auth {

    public $id;
    public $username;
    public $password;
    public $created_at;
    public $role;
    public $name;
    public $phone;

    public function __construct($id, $username, $password, $created_at, $role, $name, $phone) {
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
        $this->created_at = $created_at;
        $this->role = $role;
        $this->name = $name;
        $this->phone = $phone;
    }

    public static function register($pdo, $username, $password, $role, $name, $phone) {
        try {
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare('INSERT INTO users (username, password, role, name, phone) VALUES (?, ?, ?, ?, ?)');
            $stmt->execute([$username, $hashedPassword, $role, $name, $phone]);

            return ResponseHelper::sendDataResponse(201, true, 'User added successfully', ['admin_id' => $pdo->lastInsertId()]);
        } catch (\PDOException $e) {
            return ResponseHelper::sendErrorResponse(500, false, 'Database error: ' . $e->getMessage(), $e->getCode());
        } catch (\Exception $e) {
            return ResponseHelper::sendErrorResponse(500, false, 'General error: ' . $e->getMessage(), $e->getCode());
        }
    }

    public static function login($pdo, $username, $password) {
        try {
            $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ?');
            $stmt->execute([$username]);
    
            $admin = $stmt->fetch();
            if ($admin && password_verify($password, $admin['password'])) {
                return ResponseHelper::sendDataResponse(200, true, 'Login successful', $admin);
            }
    
            return ResponseHelper::sendErrorResponse(401, false, 'Invalid username or password', 'INVALID_CREDENTIALS');

        } catch (\PDOException $e) {
            return ResponseHelper::sendErrorResponse(500, false, 'Database error: ' . $e->getMessage(), $e->getCode());
        } catch (\Exception $e) {
            return ResponseHelper::sendErrorResponse(500, false, 'General error: ' . $e->getMessage(), $e->getCode());
        }
    }
    
}
