<?php

namespace Models;

use PDO;
use Helpers\ResponseHelper;

class TeamLead {
    public $id;
    public $name;
    public $phone;

    public function __construct($id, $name, $phone) {
        $this->id = $id;
        $this->name = $name;
        $this->phone = $phone;
    }

    public static function getAll($pdo) {
        try {
            $stmt = $pdo->query('SELECT * FROM teamleads');
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($data) {
                ResponseHelper::sendDataResponse(200, true, 'Team leads retrieved successfully', $data);
            } else {
                ResponseHelper::sendDataResponse(200, true, 'Team leads is empty', []);
            }
        } catch (\PDOException $e) {
            return ResponseHelper::sendErrorResponse(500, false, 'Database error: ' . $e->getMessage(), $e->getCode());
        }catch (\Exception $e) {
            ResponseHelper::sendErrorResponse(500, false, 'Failed to fetch team leads: ' . $e->getMessage(), $e->getCode());
        }
    }

    public static function getById($pdo, $id) {
        try {
            $stmt = $pdo->prepare('SELECT * FROM teamleads WHERE id = ?');
            $stmt->execute([$id]);
            $teamLead = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($teamLead) {
                ResponseHelper::sendDataResponse(200, true, 'Team lead found', $teamLead);
            } else {
                ResponseHelper::sendErrorResponse(404, false, 'Team lead not found', 'NOT_FOUND');
            }
        } catch (\PDOException $e) {
            return ResponseHelper::sendErrorResponse(500, false, 'Database error: ' . $e->getMessage(), $e->getCode());
        }catch (\Exception $e) {
            ResponseHelper::sendErrorResponse(500, false, 'Failed to fetch team lead: ' . $e->getMessage(), $e->getCode());
        }
    }

    public static function getTeamLeadMembers($pdo, $id) {
        try {
            $stmt = $pdo->prepare('SELECT * FROM members WHERE teamlead_id = ?');
            $stmt->execute([$id]);
            $teamLead = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if ($teamLead) {
                ResponseHelper::sendDataResponse(200, true, 'Members found', $teamLead);
            } else {
                ResponseHelper::sendDataResponse(200, true, 'Team lead has no member', []);
            }
        } catch (\PDOException $e) {
            return ResponseHelper::sendErrorResponse(500, false, 'Database error: ' . $e->getMessage(), $e->getCode());
        }catch (\Exception $e) {
            ResponseHelper::sendErrorResponse(500, false, 'Failed to fetch team lead: ' . $e->getMessage(), $e->getCode());
        }
    }
 
    public static function create($pdo, $name, $phone) {
        try {
            $stmt = $pdo->prepare('INSERT INTO teamleads (`name`, phone) VALUES (?, ?)');
            $stmt->execute([$name, $phone]);
            $teamLeadId = $pdo->lastInsertId();
            ResponseHelper::sendDataResponse(201, true, 'Team lead created successfully', ['id' => $teamLeadId]);
        } catch (\PDOException $e) {
            return ResponseHelper::sendErrorResponse(500, false, 'Database error: ' . $e->getMessage(), $e->getCode());
        }catch (\Exception $e) {
            ResponseHelper::sendErrorResponse(500, false, 'Failed to create team lead: ' . $e->getMessage(), $e->getCode());
        }
    }

    public static function update($pdo, $id, $name, $phone) {
        try {
            $stmt = $pdo->prepare('UPDATE teamleads SET `name` = ?, phone = ? WHERE id = ?');
            $stmt->execute([$name, $phone, $id]);
    
            $rowsAffected = $stmt->rowCount();
            
            if ($rowsAffected > 0) {
                ResponseHelper::sendDataResponse(200, true, 'Team lead updated successfully', ['id' => $id, 'rows_affected' => $rowsAffected]);
            } else {
                ResponseHelper::sendErrorResponse(404, false, 'No team lead found or no changes made', 404);
            }
        } catch (\PDOException $e) {
            return ResponseHelper::sendErrorResponse(500, false, 'Database error: ' . $e->getMessage(), $e->getCode());
        } catch (\Exception $e) {
            ResponseHelper::sendErrorResponse(500, false, 'Failed to update team lead: ' . $e->getMessage(), $e->getCode());
        }
    }
    
    public static function delete($pdo, $id) {
        try {
            $pdo->beginTransaction();
    
            $stmt = $pdo->prepare('DELETE FROM members WHERE teamlead_id = ?');
            $stmt->execute([$id]);
            $membersDeleted = $stmt->rowCount();
    
            $stmt = $pdo->prepare('DELETE FROM teamleads WHERE id = ?');
            $stmt->execute([$id]);
            $teamLeadDeleted = $stmt->rowCount();
    
            if ($teamLeadDeleted > 0) {
                $pdo->commit();
                $responseMessage = 'Team lead deleted successfully';
                $details = [
                    'id' => $id,
                    'members_deleted' => $membersDeleted,
                    'team_lead_deleted' => $teamLeadDeleted
                ];
                ResponseHelper::sendDataResponse(200, true, $responseMessage, $details);
            } else {

                $pdo->rollBack();
                ResponseHelper::sendErrorResponse(404, false, 'No team lead found with the provided ID', 404);
            }
        } catch (\PDOException $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            return ResponseHelper::sendErrorResponse(500, false, 'Database error: ' . $e->getMessage(), $e->getCode());
        } catch (\Exception $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            ResponseHelper::sendErrorResponse(500, false, 'Failed to delete team lead: ' . $e->getMessage(), $e->getCode());
        }
    }
    
    
}
