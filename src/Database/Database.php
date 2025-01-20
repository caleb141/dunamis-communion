<?php

namespace Database;
use Helpers\EnvHelper;

class Database {
    private $host;
    private $port;
    private $dbname;
    private $user;
    private $pass;

    public function __construct() {
        
        $envFilePath = __DIR__ . '/../../.env';
        EnvHelper::loadEnv($envFilePath);

        $this->host = getenv('DB_HOST') ?: ($_ENV['DB_HOST'] ?? 'localhost');
        $this->port = getenv('DB_PORT') ?: ($_ENV['DB_PORT'] ?? '5432');
        $this->dbname = getenv('DB_NAME') ?: ($_ENV['DB_NAME'] ?? 'dunamis');
        $this->user = getenv('DB_USER') ?: ($_ENV['DB_USER'] ?? 'postgres');
        $this->pass = getenv('DB_PASSWORD') ?: ($_ENV['DB_PASSWORD'] ?? '1234');
    }

    public function connect() {
        try {
            $dsn = "pgsql:host=$this->host;port=$this->port;dbname=$this->dbname";
            $pdo = new \PDO($dsn, $this->user, $this->pass);
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (\PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }
}

