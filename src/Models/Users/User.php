<?php
namespace Models\Users;

use Helpers\ResponseHelper;

class User {
    public $id;
    public $name;
    public $phone;
    public $team_lead;
    public $role;


    public function __construct($id, $name, $phone, $team_lead, $role) {
        $this->id = $id;
        $this->name = $name;
        $this->phone = $phone;
        $this->team_lead = $team_lead;
        $this->role = $role;
    }

    public static function getAll($pdo, $role) {
        try {
            $stmt = $pdo->prepare('SELECT * FROM users WHERE role = ?');
            $stmt->execute([$role]);
            $members = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            
            if ($members) {
                ResponseHelper::sendDataResponse(200, true, 'Users retrieved successfully', $members);
            } else {
                ResponseHelper::sendDataResponse(200, true, 'No users found', []);
            }
        } catch (\PDOException $e) {
            ResponseHelper::sendErrorResponse(500, false, 'Error fetching users: ' . $e->getMessage(), 'DB_ERROR');
        } catch (\Exception $e) {
            return ResponseHelper::sendErrorResponse(500, false, 'General error: ' . $e->getMessage(), $e->getCode());
        }
    }

    public static function getById($pdo, $id, $role) {
        try {
            $stmt = $pdo->prepare('SELECT * FROM users WHERE id = ? and role = ?');
            $stmt->execute([$id, $role]);
            $member = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            if ($member) {
                ResponseHelper::sendDataResponse(200, true, 'Users retrieved successfully', $member);
            } else {
                ResponseHelper::sendErrorResponse(404, false, 'Users not found', 'NOT_FOUND');
            }
        } catch (\PDOException $e) {
            ResponseHelper::sendErrorResponse(500, false, 'Error fetching user: ' . $e->getMessage(), 'DB_ERROR');
        } catch (\Exception $e) {
            return ResponseHelper::sendErrorResponse(500, false, 'General error: ' . $e->getMessage(), $e->getCode());
        }
    }
    
    public static function getUserTeam($pdo, $id) {
        try {
            $stmt = $pdo->prepare('
                SELECT *
                FROM teams
                WHERE members::jsonb @> ?::jsonb OR tempMembers::jsonb @> ?::jsonb
            ');
    
            $jsonId = json_encode([$id]);
            $stmt->execute([$jsonId, $jsonId]);
    
            $teams = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    
            if ($teams) {
                $teamObjects = array_map(function ($team) {
                
                    return [
                        'id' => $team['id'],
                        'team_name' => $team['team_name'],
                        'sections' => json_decode($team['sections'], true),
                        'teamlead_id' => $team['teamlead_id'],
                        'members' => json_decode($team['members'], true),
                        'tempMembers' => json_decode($team['tempmembers'], true),
                        'distPositions_0' => json_decode($team['distpositions_0'], true),
                        'distPositions_1' => json_decode($team['distpositions_1'], true),
                        'distPositions_2' => json_decode($team['distpositions_2'], true),
                        'distPositions_3' => json_decode($team['distpositions_3'], true),
                    ];
                }, $teams);
                return ResponseHelper::sendDataResponse(200, true, 'User\'s teams retrieved successfully', $teamObjects);
            } else {
                return ResponseHelper::sendErrorResponse(404, false, 'User not found in any team', 'NOT_FOUND');
            }
        } catch (\PDOException $e) {
            return ResponseHelper::sendErrorResponse(500, false, 'Database error: ' . $e->getMessage(), 'DB_ERROR');
        } catch (\Exception $e) {
            return ResponseHelper::sendErrorResponse(500, false, 'General error: ' . $e->getMessage(), $e->getCode());
        }
    }

    public static function getAllUsers($pdo) {
        try {
            $role = 1;
            $stmt = $pdo->prepare('SELECT * FROM users WHERE role <> ?');
            $stmt->execute([$role]);
            $members = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            
            if ($members) {
                ResponseHelper::sendDataResponse(200, true, 'Users retrieved successfully', $members);
            } else {
                ResponseHelper::sendDataResponse(200, true, 'No users found', []);
            }
        } catch (\PDOException $e) {
            ResponseHelper::sendErrorResponse(500, false, 'Error fetching users: ' . $e->getMessage(), 'DB_ERROR');
        } catch (\Exception $e) {
            return ResponseHelper::sendErrorResponse(500, false, 'General error: ' . $e->getMessage(), $e->getCode());
        }
    }

}
