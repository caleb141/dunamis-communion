<?php
namespace Models\Attendance;

use Helpers\ResponseHelper;

class Attendance {
    public $id;
    public $name;
    public $status;

    public function __construct($id, $name, $status) {
        $this->id = $id;
        $this->name = $name;
        $this->status = $status;
    }

    public static function getAll($pdo, $team_id) {
        try {
            // Prepare the SQL query
            $stmt = $pdo->prepare('
                SELECT
                    attendance_logs.*,
                    teams.team_name,
                    COALESCE(json_agg(
                        CASE
                            WHEN u.id IS NOT NULL THEN
                                json_build_object(
                                    \'id\', u.id,
                                    \'name\', u.name,
                                    \'phone\', u.phone
                                )
                        END
                    ) FILTER (WHERE u.id IN (SELECT jsonb_array_elements_text(attendance_logs.members::jsonb)::int)), \'[]\') AS member_details
                FROM
                    attendance_logs
                INNER JOIN
                    teams ON attendance_logs.team_id = teams.id
                LEFT JOIN
                    users u ON u.id = ANY(SELECT jsonb_array_elements_text(attendance_logs.members::jsonb)::int)
                WHERE
                    attendance_logs.team_id = ?
                GROUP BY
                    attendance_logs.id,
                    teams.team_name
            ');
    
            $stmt->execute([$team_id]);
            $datas = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    
            if ($datas) {
                foreach ($datas as &$data) {
                    $data['members'] = json_decode($data['members'], true);
                    $data['member_details'] = json_decode($data['member_details'], true);
                }
                ResponseHelper::sendDataResponse(200, true, 'Retrieved successfully all', $datas);
            } else {
                ResponseHelper::sendDataResponse(200, true, 'None found', []);
            }
        } catch (\PDOException $e) {
            ResponseHelper::sendErrorResponse(500, false, 'Error fetching: ' . $e->getMessage(), 'DB_ERROR');
        } catch (\Exception $e) {
            ResponseHelper::sendErrorResponse(500, false, 'General error: ' . $e->getMessage(), $e->getCode());
        }
    }
    
    
    public static function getOne($pdo, $team_id, $date) {
        try {
            $stmt = $pdo->prepare('
                SELECT
                    attendance_logs.*,
                    teams.team_name,
                    COALESCE(json_agg(
                        CASE
                            WHEN u.id IS NOT NULL THEN
                                json_build_object(
                                    \'id\', u.id,
                                    \'name\', u.name,
                                    \'phone\', u.phone
                                )
                        END
                    ) FILTER (WHERE u.id IN (SELECT jsonb_array_elements_text(attendance_logs.members::jsonb)::int)), \'[]\') AS member_details
                FROM
                    attendance_logs
                INNER JOIN
                    teams ON attendance_logs.team_id = teams.id
                LEFT JOIN
                    users u ON u.id = ANY(SELECT jsonb_array_elements_text(attendance_logs.members::jsonb)::int)
                WHERE
                    team_id = ? AND date = ?
                GROUP BY
                    attendance_logs.id,
                    teams.team_name
            ');
    
            $stmt->execute([$team_id, $date]);
            $data = $stmt->fetch(\PDO::FETCH_ASSOC);
    
            if ($data) {
                $data['members'] = json_decode($data['members'], true);
                $data['member_details'] = json_decode($data['member_details'], true);

                ResponseHelper::sendDataResponse(200, true, 'Retrieved successfully', $data);
            } else {
                ResponseHelper::sendErrorResponse(404, false, 'Not found', 'NOT_FOUND');
            }
        } catch (\PDOException $e) {
            ResponseHelper::sendErrorResponse(500, false, 'Database error: ' . $e->getMessage(), 'DB_ERROR');
        } catch (\Exception $e) {
            ResponseHelper::sendErrorResponse(500, false, 'General error: ' . $e->getMessage(), $e->getCode());
        }
    }
    
    

}
