<?php

namespace Helpers;

class UtilityHelper{
    public static function getIdFromUri($uri) {
        $parts = explode('/', $uri);
        $id1 = isset($parts[3]) ? intval($parts[3]) : null;
        $id2 = isset($parts[4]) ? intval($parts[4]) : null;

        return [$id1, $id2];
    }

    public static function getIdFromUriDate($uri) {
        $parts = explode('/', $uri);
        $id1 = isset($parts[3]) ? intval($parts[3]) : null;
        $id2 = isset($parts[4]) ? $parts[4] : null;
    
        if ($id2 !== null && preg_match('/^\d{4}-\d{2}-\d{2}$/', $id2)) {
            $id2 = date('Y-m-d', strtotime($id2));
        } else {
            $id2 = null;
        }
    
        return [$id1, $id2];
    }

    public static function getStatusFromCode($statusCode) {
        
        $statusMessages = [
            400 => 'Bad Request',
            401 => 'Unauthorized',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            500 => 'Internal Server Error',
        ];

        return $statusMessages[$statusCode] ?? 'Unknown Status';
    }

}
