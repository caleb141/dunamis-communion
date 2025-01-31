<?php
namespace Models\Availability;

use Helpers\ResponseHelper;

class Availability1 {
 
    public static function logAvailability($pdo, $team_id, $date, $members) {
        try {
            $stmt = $pdo->prepare('SELECT id, members FROM users_availability WHERE team_id = ? AND the_date = ?');
            $stmt->execute([$team_id, $date]);
            $avaLog = $stmt->fetch(\PDO::FETCH_ASSOC);
    
            if ($avaLog) {

                $newMembers = json_decode($avaLog['members'], true) ?: [];
                if (is_array($members)) {
                    $newMembers = array_merge($newMembers, $members);
                } else {
                    $newMembers[] = $members;
                }
                
                $newMembers = array_unique($newMembers);

                $updateStmt = $pdo->prepare('UPDATE users_availability SET members = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?');
                $updateStmt->execute([json_encode($newMembers), $avaLog['id']]);
    
                if ($updateStmt->rowCount() > 0) {
                    return ResponseHelper::sendDataResponse(200, true, 'User availability updated successfully', [
                        'team_id' => $team_id,
                        'date' => $date,
                        'members' => $members,
                    ]);
                } else {
                    return ResponseHelper::sendErrorResponse(500, false, 'Failed to update user availability', 'UPDATE_FAILED');
                }
            } else {
              
                $insertStmt = $pdo->prepare('INSERT INTO users_availability (team_id, the_date, members, created_at, updated_at) VALUES (?, ?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)');
                $insertStmt->execute([$team_id, $date, json_encode($members)]);
    
                if ($insertStmt->rowCount() > 0) {
                    return ResponseHelper::sendDataResponse(200, true, 'User availability logged successfully', [
                        'team_id' => $team_id,
                        'date' => $date,
                        'members' => $members,
                    ]);
                } else {
                    return ResponseHelper::sendErrorResponse(500, false, 'Failed to log user availability', 'INSERT_FAILED');
                }
            }
        } catch (\PDOException $e) {
            return ResponseHelper::sendErrorResponse(500, false, 'Database error: ' . $e->getMessage(), 'DB_ERROR');
        } catch (\Exception $e) {
            return ResponseHelper::sendErrorResponse(500, false, 'General error: ' . $e->getMessage(), $e->getCode());
        }
    }
    
    

}
