<?php
namespace Models\Requests;

use Helpers\ResponseHelper;

class Request {

    public static function getAll($pdo) {
        try {
            $stmt = $pdo->query('SELECT team_request.*, users.name as requester_name FROM team_request
                INNER JOIN
                    users ON team_request.requester_id = users.id');
            $requests = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            
            if ($requests) {
                ResponseHelper::sendDataResponse(200, true, 'Request retrieved successfully', $requests);
            } else {
                ResponseHelper::sendDataResponse(200, true, 'No request found', []);
            }
        } catch (\PDOException $e) {
            ResponseHelper::sendErrorResponse(500, false, 'Error fetching requests ' . $e->getMessage(), 'DB_ERROR');
        } catch (\Exception $e) {
            return ResponseHelper::sendErrorResponse(500, false, 'General error: ' . $e->getMessage(), $e->getCode());
        }
    }

    public static function getOne($pdo, $id) {
        try {
            $stmt = $pdo->prepare('SELECT team_request.*, users.name as requester_name FROM team_request
                INNER JOIN
                    users ON team_request.requester_id = users.id
                WHERE team_request.id = ?');
            $stmt->execute([$id]);
            $requests = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            
            if ($requests) {
                ResponseHelper::sendDataResponse(200, true, 'Request retrieved successfully', $requests);
            } else {
                ResponseHelper::sendDataResponse(200, true, 'No request found', []);
            }
        } catch (\PDOException $e) {
            ResponseHelper::sendErrorResponse(500, false, 'Error fetching requests ' . $e->getMessage(), 'DB_ERROR');
        } catch (\Exception $e) {
            return ResponseHelper::sendErrorResponse(500, false, 'General error: ' . $e->getMessage(), $e->getCode());
        }
    }

}
