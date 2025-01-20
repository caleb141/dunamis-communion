<?php

namespace Controllers;

use Models\Attendance\Attendance;
use Models\Attendance\Attendance1;
use Helpers\ResponseHelper;
use Database\Database;

class AttendanceController {

    private $pdo;
    public function __construct() {
        $this->pdo = (new Database())->connect();
    }

    public function getAll($team_id) {
        
        Attendance::getAll($this->pdo, $team_id);
    }

    public function getOne($id, $date) {
        
        Attendance::getOne($this->pdo, $id, $date);
    }

    public function attendanceUser() {

        $data = json_decode(file_get_contents('php://input'), true);

        if (isset($data['teamId'], $data['date'], $data['members'])) {
            Attendance1::logAttendance($this->pdo, $data['teamId'], $data['date'], $data['members']);
        } else {
            ResponseHelper::sendErrorResponse(400, false, 'Invalid input', 'INVALID_INPUT');
        }
    }
}
