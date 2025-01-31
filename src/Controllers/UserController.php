<?php

namespace Controllers;

use Models\Users\User;
use Models\Users\User1;
use Helpers\ResponseHelper;
use Database\Database;
use Models\Audit\Audit1;

class UserController {

    private $pdo;
    public function __construct() {
        $this->pdo = (new Database())->connect();
    }

    public function getAll($role) {
        
        User::getAll($this->pdo, $role);
    }

    public function getById($id, $role) {
        
        User::getById($this->pdo, $id, $role);
    }

    public function getUserTeam($id) {
        
        User::getUserTeam($this->pdo, $id);
    }
    public function getAllUsers() {
        
        User::getAllUsers($this->pdo);
    }

    public function updateUser($id) {

        $data = json_decode(file_get_contents('php://input'), true);

        if (isset($data['name'], $data['phone'], $data['username'])) {
            User1::update($this->pdo, $id, $data['name'], $data['phone'], $data['username'], $data['role']);
            Audit1::addtOLog($this->pdo, 'user', 'User details updated');
        } else {
            ResponseHelper::sendErrorResponse(400, false, 'Invalid input', 'INVALID_INPUT');
        }
    }

    public function updatePassword($id) {

        $data = json_decode(file_get_contents('php://input'), true);

        if (isset($data['newPassword'])) {
            User1::updatePassword($this->pdo, $id, $data['newPassword']);
            Audit1::addtOLog($this->pdo, 'user', 'User password updated');
        } else {
            ResponseHelper::sendErrorResponse(400, false, 'Invalid input', 'INVALID_INPUT');
        }
    }

    public function deleteUser($id) {
    
        User1::delete($this->pdo, $id);
        Audit1::addtOLog($this->pdo, 'user', 'User deleted');

    }
}
