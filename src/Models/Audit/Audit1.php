<?php
namespace Models\Audit;

use Helpers\UtilityHelper;
use Helpers\LoggerHelper;

class Audit1 {
    private const ROLE_MAPPING = [
        1 => 'admin',
        2 => 'teamlead',
        22 => 'assistant teamlead',
        3 => 'member'
    ];

    public static function addToLog($pdo, $action, $description) {
        try {
            $user_id = UtilityHelper::getUserIdFromHeaders();
            if (!$user_id) {
                LoggerHelper::logError('Audit Log: Missing user_id in request headers.');
                return;
            }

            $stmt = $pdo->prepare('SELECT role FROM users WHERE id = ? LIMIT 1');
            $stmt->execute([$user_id]);
            $user = $stmt->fetch(\PDO::FETCH_ASSOC);

            if (!$user) {
                LoggerHelper::logError("Audit Log: User with ID $user_id not found.");
                return;
            }

            if (!$user_type = self::mapUserType($user['role'])) {
                LoggerHelper::logError("Audit Log: Invalid role '{$user['role']}' for user ID $user_id.");
                return;
            }

            $pdo->prepare('INSERT INTO audit_logs (user_id, user_type, action, description, created_at)
                         VALUES (?, ?, ?, ?, CURRENT_TIMESTAMP)')
                ->execute([$user_id, $user_type, $action, $description]);

            LoggerHelper::logInfo("Audit Log: added for user ID $user_id.");

        } catch (\PDOException $e) {
            LoggerHelper::logError("Audit Log: Database error - " . $e->getMessage());
        } catch (\Exception $e) {
            LoggerHelper::logError("Audit Log: Internal error - " . $e->getMessage());
        }
    }

    private static function mapUserType($role) {
        return self::ROLE_MAPPING[$role] ?? null;
    }
}
