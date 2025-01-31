<?php
namespace Models\Audit;

use Helpers\ResponseHelper;

class Audit {

    public static function getAll($pdo) {
        try {
            $stmt = $pdo->query('SELECT audit_logs.*, users.name as user_name FROM audit_logs
                INNER JOIN
                    users ON audit_logs.user_id = users.id');
            $logs = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            
            if ($logs) {
                ResponseHelper::sendDataResponse(200, true, 'Log retrieved successfully', $logs);
            } else {
                ResponseHelper::sendDataResponse(200, true, 'No log found', []);
            }
        } catch (\PDOException $e) {
            ResponseHelper::sendErrorResponse(500, false, 'Error fetching logs ' . $e->getMessage(), 'DB_ERROR');
        } catch (\Exception $e) {
            return ResponseHelper::sendErrorResponse(500, false, 'General error: ' . $e->getMessage(), $e->getCode());
        }
    }

    public static function getOne($pdo, $id) {
        try {
            $stmt = $pdo->prepare('SELECT audit_logs.*, users.name as user_name FROM audit_logs
                INNER JOIN
                    users ON audit_logs.user_id = users.id
                WHERE audit_logs.id = ?');
            $stmt->execute([$id]);
            $requests = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            
            if ($requests) {
                ResponseHelper::sendDataResponse(200, true, 'Log retrieved successfully', $requests);
            } else {
                ResponseHelper::sendDataResponse(200, true, 'No log found', []);
            }
        } catch (\PDOException $e) {
            ResponseHelper::sendErrorResponse(500, false, 'Error fetching logs ' . $e->getMessage(), 'DB_ERROR');
        } catch (\Exception $e) {
            return ResponseHelper::sendErrorResponse(500, false, 'General error: ' . $e->getMessage(), $e->getCode());
        }
    }

}
