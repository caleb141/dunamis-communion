<?php
// Case for handling teamleads with members (e.g., /api/teamleads/{id}/members)
// case preg_match('/^\/api\/teamleads(\/\d+)\/members$/', $requestUri, $matches):
//     $controller = new \Controllers\TeamLeadController();
//     [$id, $role] = UtilityHelper::getIdFromUri($requestUri);
    
//     if ($method === 'GET' && $id !== null) {
//         $controller->getTeamLeadMembers($id);
//     } else {
//         ResponseHelper::sendResponse(405, false, UtilityHelper::getStatusFromCode(405));
//     }
//     break;
    
// Case for handling teamleads without members (e.g., /api/teamleads/{id} or /api/teamleads)
// case preg_match('/^\/api\/teamleads(\/\d+)?$/', $requestUri, $matches):
//     $controller = new \Controllers\TeamLeadController();
//     [$id, $role] = UtilityHelper::getIdFromUri($requestUri);

//     if ($method === 'GET') {
//         if ($id !== null) {
//             $controller->getById($id);
//         } else {
//             $controller->getAll();
//         }
//     } elseif ($method === 'POST') {
//         $controller->createTeamLead();
//     } elseif ($method === 'PUT' && $id !== null) {
//         $controller->updateTeamLead($id);
//     } elseif ($method === 'DELETE' && $id !== null) {
//         $controller->deleteTeamLead($id);
//     } else {
//         ResponseHelper::sendResponse(405, false, UtilityHelper::getStatusFromCode(405));
//     }
//     break;
