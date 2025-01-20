<?php

namespace Models\Teams;

use Helpers\ResponseHelper;

class Team {

    public $id;
    public $team_name;
    public $sections;
    public $team_lead_id;
    public $ass_team_lead_id;
    public $dist_positions;
    public $is_path;
    public $members;
    public $temp_members;

    public function __construct($id, $team_name, $sections,
    $team_lead_id, $ass_team_lead_id, $members, $temp_members, $dist_positions = []) {
        $this->id = $id;
        $this->team_name = $team_name;
        $this->sections = $sections;
        $this->team_lead_id = $team_lead_id;
        $this->ass_team_lead_id = $ass_team_lead_id;
        $this->dist_positions = $dist_positions;
        $this->members = $members;
        $this->temp_members = $temp_members;
    }

    public static function create($pdo, $team_name, $sections, $team_lead_id,
        $ass_team_lead_id, $dist_positions, $members) {
        try {
            $stmt = $pdo->prepare('INSERT INTO teams (team_name, sections, teamlead_id, ass_teamlead_id, distPositions_0,
                distPositions_1, distPositions_2, distPositions_3, members, tempMembers) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
            $stmt->execute([
                $team_name,
                json_encode($sections),
                $team_lead_id,
                $ass_team_lead_id,
                json_encode($dist_positions[0]),
                json_encode($dist_positions[1]),
                json_encode($dist_positions[2]),
                json_encode($dist_positions[3]),
                json_encode($members),
                json_encode($members)
            ]);

            return ResponseHelper::sendDataResponse(201, true, 'Team created successfully', ['team_id' => $pdo->lastInsertId()]);
        } catch (\PDOException $e) {
            return ResponseHelper::sendErrorResponse(500, false, 'Database error: ' . $e->getMessage(), $e->getCode());
        } catch (\Exception $e) {
            return ResponseHelper::sendErrorResponse(500, false, 'General error: ' . $e->getMessage(), $e->getCode());
        }
    }

    public static function update($pdo, $id, $team_name, $sections, $team_lead_id, $ass_team_lead_id,
        $dist_positions, $members, $temp_members) {
        try {
            $stmt = $pdo->prepare('UPDATE teams SET team_name = ?, sections = ?, teamlead_id = ?,
            ass_teamlead_id = ?, distPositions_0 = ?,
            distPositions_1 = ?, distPositions_2 = ?, distPositions_3 = ?,
            members = ?, tempMembers = ? WHERE id = ?');
            $stmt->execute([
                $team_name,
                json_encode($sections),
                $team_lead_id,
                $ass_team_lead_id,
                json_encode($dist_positions[0]),
                json_encode($dist_positions[1]),
                json_encode($dist_positions[2]),
                json_encode($dist_positions[3]),
                json_encode($members),
                json_encode($temp_members),
                $id
            ]);
    
            $rowsAffected = $stmt->rowCount();
    
            if ($rowsAffected > 0) {
                return ResponseHelper::sendDataResponse(200, true, 'Team updated successfully', ['team_id' => $id, 'rows_affected' => $rowsAffected]);
            } else {
                return ResponseHelper::sendErrorResponse(404, false, 'No team found or no changes made', 404);
            }
        } catch (\PDOException $e) {
            return ResponseHelper::sendErrorResponse(500, false, 'Database error: ' . $e->getMessage(), $e->getCode());
        } catch (\Exception $e) {
            return ResponseHelper::sendErrorResponse(500, false, 'General error: ' . $e->getMessage(), $e->getCode());
        }
    }

    public static function delete($pdo, $id) {
        try {
            $stmt = $pdo->prepare('DELETE FROM teams WHERE id = ?');
            $stmt->execute([$id]);

            if ($stmt->rowCount() > 0) {
                return ResponseHelper::sendDataResponse(200, true, 'Team deleted successfully', ['team_id' => $id]);
            }

            return ResponseHelper::sendErrorResponse(404, false, 'Team not found', 'NOT_FOUND');
        } catch (\PDOException $e) {
            return ResponseHelper::sendErrorResponse(500, false, 'Database error: ' . $e->getMessage(), $e->getCode());
        } catch (\Exception $e) {
            return ResponseHelper::sendErrorResponse(500, false, 'General error: ' . $e->getMessage(), $e->getCode());
        }
    }

    
}
