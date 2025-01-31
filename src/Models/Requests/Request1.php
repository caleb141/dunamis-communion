<?php
namespace Models\Requests;

use Helpers\ResponseHelper;

class Request1 {
 
    public static function addRequest($pdo, $requester_id, $num_members, $req_date) {
        try {
            
            $insertStmt = $pdo->prepare('INSERT INTO team_request (requester_id, num_members_needed, request_date, status, created_at, updated_at) VALUES (?, ?, ?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)');
            $insertStmt->execute([$requester_id, $num_members, $req_date, 0]);

            if ($insertStmt->rowCount() > 0) {
                return ResponseHelper::sendResponse(200, true, 'Request logged successfully');
            } else {
                return ResponseHelper::sendErrorResponse(400, false, 'Failed to log request', 'INSERT_FAILED');
            }

        } catch (\PDOException $e) {
            return ResponseHelper::sendErrorResponse(500, false, 'Database error: ' . $e->getMessage(), 'DB_ERROR');
        } catch (\Exception $e) {
            return ResponseHelper::sendErrorResponse(500, false, 'General error: ' . $e->getMessage(), $e->getCode());
        }
    }

    public static function updateRequest($pdo, $id) {
        try {

            $updateStmt = $pdo->prepare('UPDATE team_request SET status = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?');
            $updateStmt->execute([1, $id]);

            if ($updateStmt->rowCount() > 0) {
                return ResponseHelper::sendResponse(200, true, 'Request updated successfully');
            } else {
                return ResponseHelper::sendErrorResponse(400, false, 'Failed to update request', 'UPDATE_FAILED');
            }
    
        } catch (\PDOException $e) {
            return ResponseHelper::sendErrorResponse(500, false, 'Database error: ' . $e->getMessage(), 'DB_ERROR');
        } catch (\Exception $e) {
            return ResponseHelper::sendErrorResponse(500, false, 'General error: ' . $e->getMessage(), $e->getCode());
        }
    }
    

}
