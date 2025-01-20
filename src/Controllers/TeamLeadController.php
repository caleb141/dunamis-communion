<?php

namespace Controllers;

use Models\TeamLead;
use Helpers\ResponseHelper;
use Database\Database;

class TeamLeadController {
    private $pdo;
    public function __construct() {
        $this->pdo = (new Database())->connect();
    }

    public function getAll() {
        
        TeamLead::getAll($this->pdo);
    }

    public function getById($id) {

        TeamLead::getById($this->pdo, $id);
    }

    public function getTeamLeadMembers($id) {

        TeamLead::getTeamLeadMembers($this->pdo, $id);
    }

    public function createTeamLead() {
        
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (isset($data['name'], $data['phone'])) {
            TeamLead::create($this->pdo, $data['name'], $data['phone']);
        } else {
            ResponseHelper::sendErrorResponse(400, false, 'Invalid input', 'INVALID_INPUT');
        }
    }

    public function updateTeamLead($id) {
        
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (isset($data['name'], $data['phone'])) {
            TeamLead::update($this->pdo, $id, $data['name'], $data['phone']);
        } else {
            ResponseHelper::sendErrorResponse(400, false, 'Invalid input', 'INVALID_INPUT');
        }
    }

    public function deleteTeamLead($id) {
        
        TeamLead::delete($this->pdo, $id);
    }
}
