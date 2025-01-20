<?php

namespace Controllers;

use Models\Requests\Request;
use Models\Requests\Request1;
use Helpers\ResponseHelper;
use Database\Database;

class RequestController {

    private $pdo;
    public function __construct() {
        $this->pdo = (new Database())->connect();
    }

    public function getAll() {
        
        Request::getAll($this->pdo);
    }

    public function getOne($id) {
        
        Request::getOne($this->pdo, $id);
    }

    public function addRequest() {

        $data = json_decode(file_get_contents('php://input'), true);

        if (isset($data['requesterId'], $data['numMembersNeeded'])) {
            Request1::addRequest($this->pdo, $data['requesterId'], $data['numMembersNeeded']);
        } else {
            ResponseHelper::sendErrorResponse(400, false, 'Invalid input', 'INVALID_INPUT');
        }
    }

    public function updateRequest($id) {

        Request1::updateRequest($this->pdo, $id);
        
    }
}
