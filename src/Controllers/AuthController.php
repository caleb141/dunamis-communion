<?php

namespace Controllers;

use Models\Auth;
use Database\Database;
use Helpers\ResponseHelper;

class AuthController {
    private $pdo;

    public function __construct() {
        $this->pdo = (new Database())->connect();
    }

    public function register() {
        $data = json_decode(file_get_contents('php://input'), true);

        $username = $data['username'] ?? null;
        $password = $data['password'] ?? null;
        $role = $data['role'] ?? null;
        $name = $data['name'] ?? null;
        $phone = $data['phone'] ?? null;

        if (!$username || !$password) {
            http_response_code(400);
            ResponseHelper::sendResponse(400, false, 'Username and password are required');
            return;
        }

        Auth::register($this->pdo, $username, $password, $role, $name, $phone);

    }

    public function login() {

        $data = json_decode(file_get_contents('php://input'), true);

        $username = $data['username'] ?? null;
        $password = $data['password'] ?? null;

        if (!$username || !$password) {
            http_response_code(400);
            ResponseHelper::sendResponse(400, false, 'Username and password are required');
            return;
        }

        Auth::login($this->pdo, $username, $password);

    }

    public function logout() {

        http_response_code(200);
        ResponseHelper::sendResponse(200, true, 'Logged out successfully');
    }
}

