<?php
namespace Helpers;

class EnvHelper {
    public static function loadEnv($filePath = null, $override = false) {
        
        if ($filePath && file_exists($filePath)) {
            $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (strpos(trim($line), '#') === 0) {
                    continue;
                }
    
                [$key, $value] = explode('=', $line, 2);
                $key = trim($key);
                $value = trim($value);
    
                if (preg_match('/^"(.*)"$/', $value, $matches)) {
                    $value = $matches[1];
                } elseif (preg_match("/^'(.*)'$/", $value, $matches)) {
                    $value = $matches[1];
                }
    
                if ($override || !array_key_exists($key, $_ENV)) {
                    $_ENV[$key] = $value;
                    putenv("$key=$value");
                }
            }
        }
    
        if (function_exists('getenv')) {
            foreach ($_ENV as $key => $value) {
                if ($override || !isset($_SERVER[$key])) {
                    $_SERVER[$key] = getenv($key);
                }
            }
        }
    
    }

}
