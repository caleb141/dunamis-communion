<?php
namespace Models\Attendance;

use Helpers\ResponseHelper;

class Attendance1 {
 
    public static function logAttendance($pdo, $team_id, $date, $members) {
        try {
            $stmt = $pdo->prepare('SELECT id, members FROM attendance_logs WHERE team_id = ? AND date = ?');
            $stmt->execute([$team_id, $date]);
            $attendanceLog = $stmt->fetch(\PDO::FETCH_ASSOC);
    
            if ($attendanceLog) {

                $updateStmt = $pdo->prepare('UPDATE attendance_logs SET members = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?');
                $updateStmt->execute([json_encode($members), $attendanceLog['id']]);
    
                if ($updateStmt->rowCount() > 0) {
                    return ResponseHelper::sendDataResponse(200, true, 'Attendance updated successfully', [
                        'team_id' => $team_id,
                        'date' => $date,
                        'members' => $members,
                    ]);
                } else {
                    return ResponseHelper::sendErrorResponse(500, false, 'Failed to update attendance', 'UPDATE_FAILED');
                }
            } else {
              
                $insertStmt = $pdo->prepare('INSERT INTO attendance_logs (team_id, date, members, created_at, updated_at) VALUES (?, ?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)');
                $insertStmt->execute([$team_id, $date, json_encode($members)]);
    
                if ($insertStmt->rowCount() > 0) {
                    return ResponseHelper::sendDataResponse(200, true, 'Attendance logged successfully', [
                        'team_id' => $team_id,
                        'date' => $date,
                        'members' => $members,
                    ]);
                } else {
                    return ResponseHelper::sendErrorResponse(500, false, 'Failed to log attendance', 'INSERT_FAILED');
                }
            }
        } catch (\PDOException $e) {
            return ResponseHelper::sendErrorResponse(500, false, 'Database error: ' . $e->getMessage(), 'DB_ERROR');
        } catch (\Exception $e) {
            return ResponseHelper::sendErrorResponse(500, false, 'General error: ' . $e->getMessage(), $e->getCode());
        }
    }
    
    

}
