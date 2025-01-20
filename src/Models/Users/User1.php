<?php
namespace Models\Users;

use Helpers\ResponseHelper;

class User1 {

    public static function update($pdo, $id, $name, $phone, $username, $role) {
        try {
            $stmt = $pdo->prepare('UPDATE users SET name = ?, phone = ?, username = ?, role = ?,  WHERE id = ?');
            $stmt->execute([$name, $phone, $username, $role, $id]);

            if ($stmt->rowCount() > 0) {
                ResponseHelper::sendDataResponse(200, true, 'User updated successfully', ['member_id' => $id]);
            } else {
                ResponseHelper::sendErrorResponse(404, false, 'User not found', 'NOT_FOUND');
            }
            
        } catch (\PDOException $e) {
            ResponseHelper::sendErrorResponse(500, false, 'Error updating member: ' . $e->getMessage(), 'DB_ERROR');
        } catch (\Exception $e) {
            return ResponseHelper::sendErrorResponse(500, false, 'General error: ' . $e->getMessage(), $e->getCode());
        }
    }

    public static function updatePassword($pdo, $id, $password) {
        try {
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare('UPDATE users SET password = ? WHERE id = ?');
            $stmt->execute([$hashedPassword, $id]);

            if ($stmt->rowCount() > 0) {
                ResponseHelper::sendDataResponse(200, true, 'User password updated successfully', ['member_id' => $id]);
            } else {
                ResponseHelper::sendErrorResponse(404, false, 'User not found', 'NOT_FOUND');
            }
            
        } catch (\PDOException $e) {
            ResponseHelper::sendErrorResponse(500, false, 'Error updating user: ' . $e->getMessage(), 'DB_ERROR');
        } catch (\Exception $e) {
            return ResponseHelper::sendErrorResponse(500, false, 'General error: ' . $e->getMessage(), $e->getCode());
        }
    }
    
    public static function delete($pdo, $id) {
        try {
            $stmt = $pdo->prepare('DELETE FROM users WHERE id = ?');
            $stmt->execute([$id]);

            if ($stmt->rowCount() > 0) {
                ResponseHelper::sendDataResponse(200, true, 'User deleted successfully', ['member_id' => $id]);
            } else {
                ResponseHelper::sendErrorResponse(404, false, 'User not found', 'NOT_FOUND');
            }
        } catch (\PDOException $e) {
            ResponseHelper::sendErrorResponse(500, false, 'Error deleting user: ' . $e->getMessage(), 'DB_ERROR');
        } catch (\Exception $e) {
            return ResponseHelper::sendErrorResponse(500, false, 'General error: ' . $e->getMessage(), $e->getCode());
        }
    }
}
