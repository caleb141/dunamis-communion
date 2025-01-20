<?php

function autoload($className) {
    $baseDir = __DIR__ . '/src/';

    $classFile = $baseDir . str_replace("\\", "/", $className) . ".php";

    if (file_exists($classFile)) {
        require_once $classFile;
    } else {
        echo "File not found: $classFile\n";
    }
}

spl_autoload_register("autoload");
