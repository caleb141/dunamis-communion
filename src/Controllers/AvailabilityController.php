<?php

namespace Controllers;

use Models\Availability\Availability;
use Models\Availability\Availability1;
use Helpers\ResponseHelper;
use Database\Database;
use Models\Audit\Audit1;

class AvailabilityController {

    private $pdo;
    public function __construct() {
        $this->pdo = (new Database())->connect();
    }

    public function getAll($team_id) {
        
        Availability::getAll($this->pdo, $team_id);
    }

    public function getOne($id, $date) {
        
        Availability::getOne($this->pdo, $id, $date);
    }

    public function availabilityUser() {

        $data = json_decode(file_get_contents('php://input'), true);

        if (isset($data['teamId'], $data['date'], $data['userId'])) {
            Availability1::logAvailability($this->pdo, $data['teamId'], $data['date'], $data['userId']);
            Audit1::addtOLog($this->pdo, 'availability', 'User availability updated');
        } else {
            ResponseHelper::sendErrorResponse(400, false, 'Invalid input', 'INVALID_INPUT');
        }
    }
}
