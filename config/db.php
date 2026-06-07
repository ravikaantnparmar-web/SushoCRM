<?php
require_once __DIR__ . '/config.php';

class Database {
    private static ?PDO $instance = null;

    private function __construct() {}

    public static function getInstance(): PDO {
        if (self::$instance === null) {
            // Automatically switch credentials based on the environment
            if ($_SERVER['HTTP_HOST'] === 'localhost' || $_SERVER['HTTP_HOST'] === '127.0.0.1') {
                // Local XAMPP Credentials
                $host   = 'localhost';
                $dbname = 'sushobha_crm';
                $user   = 'root';
                $pass   = '';
            } else {
                // Hostinger Production Credentials
                $host   = 'localhost';
                $dbname = 'u738172090_sushodb';
                $user   = 'u738172090_sushousr';
                $pass   = 'Hanumaan@102';
            }
            $charset = 'utf8mb4';

            $dsn = "mysql:host={$host};dbname={$dbname};charset={$charset}";
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            try {
                self::$instance = new PDO($dsn, $user, $pass, $options);
            } catch (PDOException $e) {
                die(json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]));
            }
        }
        return self::$instance;
    }
}

function db(): PDO {
    return Database::getInstance();
}
