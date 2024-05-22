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

    $GLOBALS['pdo'] = new PDO($dsn, $dbUsername, $dbPassword, $options);
} catch (PDOException $e) {
    throw new PDOException((string) $e->getMessage(), (int) $e->getCode());
}
