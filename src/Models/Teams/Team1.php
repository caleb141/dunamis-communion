<?php

namespace Models\Teams;

use Helpers\ResponseHelper;

class Team1 {

    public static function getAll($pdo) {
        try {
            $stmt = $pdo->query('SELECT t.id AS team_id, t.team_name, t.sections, t.teamlead_id, t.ass_teamlead_id,
            t.members, t.tempmembers, t.distpositions_0, t.distpositions_1, t.distpositions_2, t.distpositions_3,
                    COALESCE(json_agg(
                        CASE
                            WHEN u.id IS NOT NULL THEN
                                json_build_object(
                                    \'id\', u.id,
                                    \'name\', u.name,
                                    \'phone\', u.phone
                                )
                        END
                    ) FILTER (WHERE u.id IN (SELECT jsonb_array_elements_text(t.members::jsonb)::int)) , \'[]\') AS member_details,
                    COALESCE(json_agg(
                        CASE
                            WHEN u.id IS NOT NULL THEN
                                json_build_object(
                                    \'id\', u.id,
                                    \'name\', u.name,
                                    \'phone\', u.phone
                                )
                        END
                    ) FILTER (WHERE u.id IN (SELECT jsonb_array_elements_text(t.tempmembers::jsonb)::int)) , \'[]\') AS temp_member_details
                FROM teams t
                LEFT JOIN users u ON u.id = ANY(SELECT jsonb_array_elements_text(t.members::jsonb)::int) 
                OR u.id = ANY(SELECT jsonb_array_elements_text(t.tempmembers::jsonb)::int)
                GROUP BY t.id
            ');
    
            $teams = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    
            if ($teams) {
                $teamObjects = array_map(function ($team) {
                    return [
                        'id' => $team['team_id'],
                        'team_name' => $team['team_name'],
                        'sections' => json_decode($team['sections'], true),
                        'teamlead_id' => $team['teamlead_id'],
                        'ass_teamlead_id' => $team['ass_teamlead_id'],
                        'members' => json_decode($team['members'], true),
                        'tempMembers' => json_decode($team['tempmembers'], true),
                        'distPositions_0' => json_decode($team['distpositions_0'], true),
                        'distPositions_1' => json_decode($team['distpositions_1'], true),
                        'distPositions_2' => json_decode($team['distpositions_2'], true),
                        'distPositions_3' => json_decode($team['distpositions_3'], true),
                        'member_details' => json_decode($team['member_details'], true),
                        'temp_member_details' => json_decode($team['temp_member_details'], true),
                    ];
                }, $teams);
    
                return ResponseHelper::sendDataResponse(200, true, 'Teams retrieved successfully', $teamObjects);
            } else {
                return ResponseHelper::sendDataResponse(200, true, 'No teams found', []);
            }
        } catch (\PDOException $e) {
            return ResponseHelper::sendErrorResponse(500, false, 'Database error: ' . $e->getMessage(), $e->getCode());
        } catch (\Exception $e) {
            return ResponseHelper::sendErrorResponse(500, false, 'General error: ' . $e->getMessage(), $e->getCode());
        }
    }
    
    
    public static function getById($pdo, $id) {
        try {
            $stmt = $pdo->prepare('SELECT t.id AS team_id, t.team_name, t.sections, t.teamlead_id, t.ass_teamlead_id,
             t.members, t.tempmembers, t.distpositions_0, t.distpositions_1, t.distpositions_2, t.distpositions_3,
                    COALESCE(json_agg(
                        CASE
                            WHEN u.id IS NOT NULL THEN
                                json_build_object(
                                    \'id\', u.id,
                                    \'name\', u.name,
                                    \'phone\', u.phone
                                )
                        END
                    ) FILTER (WHERE u.id IN (SELECT jsonb_array_elements_text(t.members::jsonb)::int)) , \'[]\') AS member_details,
                    COALESCE(json_agg(
                        CASE
                            WHEN u.id IS NOT NULL THEN
                                json_build_object(
                                    \'id\', u.id,
                                    \'name\', u.name,
                                    \'phone\', u.phone
                                )
                        END
                    ) FILTER (WHERE u.id IN (SELECT jsonb_array_elements_text(t.tempmembers::jsonb)::int)) , \'[]\') AS temp_member_details
                FROM teams t
                LEFT JOIN users u ON u.id = ANY(SELECT jsonb_array_elements_text(t.members::jsonb)::int)
                                 OR u.id = ANY(SELECT jsonb_array_elements_text(t.tempmembers::jsonb)::int)
                WHERE t.id = ?
                GROUP BY t.id
            ');
    
            $stmt->execute([$id]);
            $team = $stmt->fetch(\PDO::FETCH_ASSOC);
    
            if ($team) {
                $teamObject = [
                    'id' => $team['team_id'],
                    'team_name' => $team['team_name'],
                    'sections' => json_decode($team['sections'], true),
                    'teamlead_id' => $team['teamlead_id'],
                    'ass_teamlead_id' => $team['ass_teamlead_id'],
                    'members' => json_decode($team['members'], true),
                    'tempMembers' => json_decode($team['tempmembers'], true),
                    'distPositions_0' => json_decode($team['distpositions_0'], true),
                    'distPositions_1' => json_decode($team['distpositions_1'], true),
                    'distPositions_2' => json_decode($team['distpositions_2'], true),
                    'distPositions_3' => json_decode($team['distpositions_3'], true),
                    'member_details' => json_decode($team['member_details'], true),
                    'temp_member_details' => json_decode($team['temp_member_details'], true),
                ];
    
                return ResponseHelper::sendDataResponse(200, true, 'Team retrieved successfully', $teamObject);
            }
    
            return ResponseHelper::sendErrorResponse(404, false, 'Team not found', 'NOT_FOUND');
        } catch (\PDOException $e) {
            return ResponseHelper::sendErrorResponse(500, false, 'Database error: ' . $e->getMessage(), $e->getCode());
        } catch (\Exception $e) {
            return ResponseHelper::sendErrorResponse(500, false, 'General error: ' . $e->getMessage(), $e->getCode());
        }
    }

}
