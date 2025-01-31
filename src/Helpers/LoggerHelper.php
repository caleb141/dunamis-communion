<?php
namespace Helpers;

class LoggerHelper {
    private static function getLogFilePath() {
        $logDir = __DIR__ . '/../logs/' . date('Y');
        if (!is_dir($logDir)) {
            mkdir($logDir, 0777, true);
        }

        return $logDir . '/' . date('Y-m') . '.log';
    }

    public static function logError($message) {
        $logFile = self::getLogFilePath();
        $timestamp = date('Y-m-d H:i:s');
        $formattedMessage = "[$timestamp] ERROR: $message" . PHP_EOL;

        file_put_contents($logFile, $formattedMessage, FILE_APPEND);
    }

    public static function logInfo($message) {
        $logFile = self::getLogFilePath();
        $timestamp = date('Y-m-d H:i:s');
        $formattedMessage = "[$timestamp] INFO: $message" . PHP_EOL;

        file_put_contents($logFile, $formattedMessage, FILE_APPEND);
    }
}
