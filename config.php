<?php

try {
    $dbHost = getenv('DB_HOST');
    $dbUsername = getenv('DB_USERNAME');
    $dbPassword = getenv('DB_PASSWORD');
    $dbName = getenv('DB_DATABASE');
    $dbPort = getenv('DB_PORT');
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$dbHost:$dbPort;dbname=$dbName;charset=$charset";

    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    $pdo = new PDO($dsn, $dbUsername, $dbPassword, $options);
    $GLOBALS['pdo'] = $pdo;

    $tableName = 'tracked_visits';
    $sql = "SHOW TABLES LIKE '$tableName'";
    $stmt = $pdo->query($sql);
    $tableExists = $stmt->rowCount() > 0;
    if (!$tableExists) {
        $sqlCreateTable = "CREATE TABLE $tableName (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            ip_address VARCHAR(45) NOT NULL,
            user_agent VARCHAR(255) NOT NULL,
            visited_page VARCHAR(255) NOT NULL,
            referrer VARCHAR(255) DEFAULT NULL,
            visit_datetime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            country VARCHAR(100) DEFAULT NULL,
            city VARCHAR(100) DEFAULT NULL,
            device VARCHAR(50) DEFAULT NULL,
            screen_resolution VARCHAR(20) DEFAULT NULL,
            browser VARCHAR(50) DEFAULT NULL,
            browser_version VARCHAR(255) DEFAULT NULL,
            operating_system VARCHAR(50) DEFAULT NULL
        )";
        $pdo->exec($sqlCreateTable);
    }
} catch (PDOException $e) {
    throw new PDOException((string) $e->getMessage(), (int) $e->getCode());
}
