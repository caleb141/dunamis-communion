<?php

require_once __DIR__ . '/autoload.php';
use Middleware\CorsMiddleware;

CorsMiddleware::handle();

use Helpers\ResponseHelper;
use Helpers\UtilityHelper;

#$main_url = '/dunamis-com';
$main_url = '/dunamis';

$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
echo "first request url is :".$requestUri; // Add this to debug the URI
$requestUri = str_replace($main_url, '', $requestUri);
echo "last request url is :".$requestUri; // Add this to debug the URI
$method = $_SERVER['REQUEST_METHOD'];
error_log("Processed Request URI: " . $requestUri);

switch (true) {
    //Attendance route
    case preg_match('/^\/api\/attendance(?:\/\d+)?(?:\/\d{4}-\d{2}-\d{2})?$/', $requestUri, $matches):
        $controller = new \Controllers\AttendanceController();
        [$id, $date] = UtilityHelper::getIdFromUriDate($requestUri);

        if ($method === 'GET') {
            if ($id !== null && $date !== null) {
                $controller->getOne($id, $date);
            } else {
                $controller->getAll($id);
            }
        } elseif ($method === 'POST') {
            $controller->attendanceUser();
        } else {
            ResponseHelper::sendResponse(405, false, UtilityHelper::getStatusFromCode(405));
        }
        break;
    
    //Availability route
    case preg_match('/^\/api\/availability(?:\/\d+)?(?:\/\d{4}-\d{2}-\d{2})?$/', $requestUri, $matches):
        $controller = new \Controllers\AvailabilityController();
        [$id, $date] = UtilityHelper::getIdFromUriDate($requestUri);

        if ($method === 'GET') {
            if ($id !== null && $date !== null) {
                $controller->getOne($id, $date);
            } else {
                $controller->getAll($id);
            }
        } elseif ($method === 'POST') {
            $controller->availabilityUser();
        } else {
            ResponseHelper::sendResponse(405, false, UtilityHelper::getStatusFromCode(405));
        }
        break;

    //Request route
    case preg_match('/^\/api\/team-request(?:\/\d+)?(?:\/\d{4}-\d{2}-\d{2})?$/', $requestUri, $matches):
        $controller = new \Controllers\RequestController();
        [$id, $none] = UtilityHelper::getIdFromUriDate($requestUri);

        if ($method === 'GET') {
            if ($id !== null) {
                $controller->getOne($id);
            } else {
                $controller->getAll();
            }
        } elseif ($method === 'POST') {
            $controller->addRequest();
        } elseif ($method === 'PUT') {
            $controller->updateRequest($id);
        }else {
            ResponseHelper::sendResponse(405, false, UtilityHelper::getStatusFromCode(405));
        }
        break;

    // User routes
    case preg_match('/^\/api\/users(\/\d+)?(\/\d+)?$/', $requestUri, $matches):
        $controller = new \Controllers\UserController();
        [$id, $role] = UtilityHelper::getIdFromUri($requestUri);
    
        if ($method === 'GET') {
            if ($id !== null && $role !== null) {
                $controller->getById($id, $role);
            } elseif ($id !== null) {
                $controller->getAll($id);
            } else{
                $controller->getAllUsers();
            }
        } elseif ($method === 'PUT' && $id !== null) {
            $controller->updateUser($id);
        } elseif ($method === 'DELETE' && $id !== null) {
            $controller->deleteUser($id);
        } else {
            ResponseHelper::sendResponse(405, false, UtilityHelper::getStatusFromCode(405));
        }
        break;
    
    case preg_match('/^\/api\/user-teams(\/\d+)?(\/\d+)?$/', $requestUri, $matches):
        $controller = new \Controllers\UserController();
        [$id, $role] = UtilityHelper::getIdFromUri($requestUri);
    
        if ($method === 'GET' && $id !== null) {
            $controller->getUserTeam($id);
        } else {
            ResponseHelper::sendResponse(405, false, UtilityHelper::getStatusFromCode(405));
        }
        break;

    // Team routes
    case preg_match('/^\/api\/teams(\/\d+)?$/', $requestUri, $matches):
        $controller = new \Controllers\TeamController();
        [$id, $role] = UtilityHelper::getIdFromUri($requestUri);

        if ($method === 'GET') {
            if ($id !== null) {
                $controller->getById($id);
            } else {
                $controller->getAll();
            }
        } elseif ($method === 'POST') {
            $controller->createTeam();
        }  elseif ($method === 'PATCH') {
            $controller->shuffleTeam();
        } elseif ($method === 'PUT' && $id !== null) {
            $controller->updateTeam($id);
        } elseif ($method === 'DELETE' && $id !== null) {
            $controller->deleteTeam($id);
        } else {
            ResponseHelper::sendResponse(405, false, UtilityHelper::getStatusFromCode(405));
        }
        break;

    // Auth routes
    case $requestUri ===  '/api/auth/register' && $method === 'POST':
        $controller = new \Controllers\AuthController();
        $controller->register();
        break;
    case $requestUri === '/api/auth/login' && $method === 'POST':
        $controller = new \Controllers\AuthController();
        $controller->login();
        break;
    case $requestUri === '/api/auth/logout' && $method === 'GET':
        $controller = new \Controllers\AuthController();
        $controller->logout();
        break;

    default:
        ResponseHelper::sendResponse(404, false, 'Endpoint Not Found');
        break;
}
