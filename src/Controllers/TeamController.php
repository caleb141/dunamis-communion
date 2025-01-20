<?php

namespace Controllers;

use Models\Teams\Team;
use Models\Teams\Team1;
use Models\Teams\Team2;
use Helpers\ResponseHelper;
use Database\Database;

class TeamController {

    private $pdo;
    public function __construct() {
        $this->pdo = (new Database())->connect();
    }

    public function getAll() {

        Team1::getAll($this->pdo);

    }

    public function getById($id) {

        Team1::getById($this->pdo, $id);

    }

    public function createTeam() {

        try{
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (isset($data['team_name'])) {
            Team::create(
                $this->pdo,
                $data['team_name'],
                $data['sections'],
                $data['teamlead_id'],
                $data['ass_teamlead_id'],
                [
                    $data['distPositions_0'],
                    $data['distPositions_1'],
                    $data['distPositions_2'],
                    $data['distPositions_3']
                ],
                $data['members']
            );

        } else {
            ResponseHelper::sendErrorResponse(400, false, 'Invalid input', $data);
        }
    } catch (\Exception $e) {
        return ResponseHelper::sendErrorResponse(500, false, 'General error: ' . $e->getMessage(), $e->getCode());
    }

    }

    public function shuffleTeam() {
        try{
        
        $data = json_decode(file_get_contents('php://input'), true);

        if (isset($data['action'], $data['memberId'])) {

            if ($data['action'] == 'shuffle') {
                Team2::shuffle(
                    $this->pdo,
                    $data['teamId'],
                    $data['newTeamId'],
                    $data['tempMembers'],
                    $data['memberId']
                );

            } elseif ($data['action'] == 'move') {
                Team2::move(
                    $this->pdo,
                    $data['teamId'],
                    $data['newTeamId'],
                    $data['members'],
                    $data['tempMembers'],
                    $data['memberId']
                );
            } else{
                ResponseHelper::sendErrorResponse(400, false, 'Invalid action', 'INVALID_ACTION');
            }
          
        } else {
            ResponseHelper::sendErrorResponse(400, false, 'Invalid input', 'INVALID_INPUT');
        }
    } catch (\Exception $e) {
        return ResponseHelper::sendErrorResponse(500, false, 'General error: ' . $e->getMessage(), $e->getCode());
    }

    }

    public function updateTeam($id) {
        try{
        $data = json_decode(file_get_contents('php://input'), true);

        if (isset($data['team_name'])) {
            Team::update(
                $this->pdo,
                $id,
                $data['team_name'],
                $data['sections'],
                $data['teamlead_id'],
                $data['ass_teamlead_id'],
                [
                    $data['distPositions_0'],
                    $data['distPositions_1'],
                    $data['distPositions_2'],
                    $data['distPositions_3']
                ],
                $data['members'],
                $data['tempMembers']
            );
          
        } else {
            ResponseHelper::sendErrorResponse(400, false, 'Invalid input', 'INVALID_INPUT');
        }
    } catch (\Exception $e) {
        return ResponseHelper::sendErrorResponse(500, false, 'General error: ' . $e->getMessage(), $e->getCode());
    }

    }

    public function deleteTeam($id) {

        Team::delete($this->pdo, $id);

    }
}
