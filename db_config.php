<?php
    define('DB_HOST', 'your_db_host');
    define('DB_NAME', 'your_db_name');
    define('DB_USER', 'your_db_user');
    define('DB_PASS', 'your_db_password');

    function getDB(): PDO
    {
        try {
            $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME;
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
            return $pdo;
        } catch (PDOException $e) {
            error_log('Connection failed: ' . $e->getMessage());
            throw new Exception('Database connection failed');
        }
    }
?>