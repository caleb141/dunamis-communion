<?php

namespace Helpers;

class ResponseHelper{

    public static function sendResponse($statusCode, $status, $message) {
        http_response_code($statusCode);

        $response = [
            'status_code' => $statusCode,
            'status' => $status,
            'message' => $message
        ];

        echo json_encode($response);
    }

    public static function sendDataResponse($statusCode, $status, $message, $dataMessage) {
        http_response_code($statusCode);

        $response = [
            'status_code' => $statusCode,
            'status' => $status,
            'message' => $message,
            'data' => $dataMessage
        ];

        echo json_encode($response);
    }

    public static function sendErrorResponse($statusCode, $status, $message, $errorCode) {
        http_response_code($statusCode);

        $response = [
            'status_code' => $statusCode,
            'status' => $status,
            'message' => $message,
            'error_code' => $errorCode
        ];

        echo json_encode($response);
    }


}
