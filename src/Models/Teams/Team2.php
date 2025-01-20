<?php

namespace Models\Teams;

use Helpers\ResponseHelper;

class Team2 {

    public static function shuffle($pdo, $team_id, $new_team_id, $temp_members, $member_id) {
        try {

            $pdo->beginTransaction();
    
            $checkTeamStmt = $pdo->prepare('SELECT id FROM teams WHERE id = ?');
            $checkTeamStmt->execute([$team_id]);
            if (!$checkTeamStmt->fetch(\PDO::FETCH_ASSOC)) {
                $pdo->rollBack();
                return ResponseHelper::sendErrorResponse(404, false, 'No team found with the given team_id', 404);
            }
    
            $checkNewTeamStmt = $pdo->prepare('SELECT id FROM teams WHERE id = ?');
            $checkNewTeamStmt->execute([$new_team_id]);
            if (!$checkNewTeamStmt->fetch(\PDO::FETCH_ASSOC)) {
                $pdo->rollBack();
                return ResponseHelper::sendErrorResponse(404, false, 'No team found with the given new_team_id', 404);
            }

            $updateTempMembersStmt = $pdo->prepare('UPDATE teams SET tempMembers = ? WHERE id = ?');
            $updateTempMembersStmt->execute([json_encode($temp_members), $team_id]);
    
            $fetchTempMembersStmt = $pdo->prepare('SELECT tempMembers FROM teams WHERE id = ?');
            $fetchTempMembersStmt->execute([$new_team_id]);
            $result = $fetchTempMembersStmt->fetch(\PDO::FETCH_ASSOC);
    
            if (!$result) {
                $pdo->rollBack();
                return ResponseHelper::sendErrorResponse(404, false, 'Failed to fetch tempMembers for new_team_id', 404);
            }
    
            $currentTempMembers = json_decode($result['tempMembers'], true) ?: [];
            if (is_array($member_id)) {
                $currentTempMembers = array_merge($currentTempMembers, $member_id);
            } else {
                $currentTempMembers[] = $member_id;
            }

            $currentTempMembers = array_unique($currentTempMembers);
    
            $updateNewTempMembersStmt = $pdo->prepare('UPDATE teams SET tempMembers = ? WHERE id = ?');
            $updateNewTempMembersStmt->execute([json_encode($currentTempMembers), $new_team_id]);
    
            $pdo->commit();
    
            return ResponseHelper::sendDataResponse(
                200,
                true,
                'Member(s) shuffled successfully',
                [
                    'updated_team_id' => $team_id,
                    'updated_new_team_id' => $new_team_id,
                    'new_temp_members' => $currentTempMembers
                ]
            );
        } catch (\PDOException $e) {
            $pdo->rollBack();
            return ResponseHelper::sendErrorResponse(500, false, 'Database error: ' . $e->getMessage(), $e->getCode());
        } catch (\Exception $e) {
            $pdo->rollBack();
            return ResponseHelper::sendErrorResponse(500, false, 'General error: ' . $e->getMessage(), $e->getCode());
        }
    }

    public static function move($pdo, $team_id, $new_team_id, $members, $temp_members, $member_id) {
        try {

            $pdo->beginTransaction();
    
            $checkTeamStmt = $pdo->prepare('SELECT id FROM teams WHERE id = ?');
            $checkTeamStmt->execute([$team_id]);
            if (!$checkTeamStmt->fetch(\PDO::FETCH_ASSOC)) {
                $pdo->rollBack();
                return ResponseHelper::sendErrorResponse(404, false, 'No team found with the given team_id', 404);
            }
    
            $checkNewTeamStmt = $pdo->prepare('SELECT id FROM teams WHERE id = ?');
            $checkNewTeamStmt->execute([$new_team_id]);
            if (!$checkNewTeamStmt->fetch(\PDO::FETCH_ASSOC)) {
                $pdo->rollBack();
                return ResponseHelper::sendErrorResponse(404, false, 'No team found with the given new_team_id', 404);
            }

            $updateTempMembersStmt = $pdo->prepare('UPDATE teams SET members = ?, tempMembers = ? WHERE id = ?');
            $updateTempMembersStmt->execute([json_encode($members), json_encode($temp_members), $team_id]);
    
            $fetchTempMembersStmt = $pdo->prepare('SELECT tempMembers, members FROM teams WHERE id = ?');
            $fetchTempMembersStmt->execute([$new_team_id]);
            $result = $fetchTempMembersStmt->fetch(\PDO::FETCH_ASSOC);
    
            if (!$result) {
                $pdo->rollBack();
                return ResponseHelper::sendErrorResponse(404, false, 'Failed to fetch tempMembers for new_team_id', 404);
            }
    
            $currentTempMembers = json_decode($result['tempMembers'], true) ?: [];
            if (is_array($member_id)) {
                $currentTempMembers = array_merge($currentTempMembers, $member_id);
            } else {
                $currentTempMembers[] = $member_id;
            }

            $currentTempMembers = array_unique($currentTempMembers);

            $currentMembers = json_decode($result['members'], true) ?: [];
            if (is_array($member_id)) {
                $currentMembers = array_merge($currentMembers, $member_id);
            } else {
                $currentMembers[] = $member_id;
            }

            $currentMembers = array_unique($currentMembers);
    
            $updateNewTempMembersStmt = $pdo->prepare('UPDATE teams SET members = ?, tempMembers = ? WHERE id = ?');
            $updateNewTempMembersStmt->execute([json_encode($currentMembers), json_encode($currentTempMembers), $new_team_id]);
    
            $pdo->commit();
    
            return ResponseHelper::sendDataResponse(
                200,
                true,
                'Member(s) moved successfully',
                [
                    'updated_team_id' => $team_id,
                    'updated_new_team_id' => $new_team_id,
                    'new_members' => $currentMembers,
                    'new_temp_members' => $currentTempMembers
                ]
            );
        } catch (\PDOException $e) {
            $pdo->rollBack();
            return ResponseHelper::sendErrorResponse(500, false, 'Database error: ' . $e->getMessage(), $e->getCode());
        } catch (\Exception $e) {
            $pdo->rollBack();
            return ResponseHelper::sendErrorResponse(500, false, 'General error: ' . $e->getMessage(), $e->getCode());
        }
    }

}
