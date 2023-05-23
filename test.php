<?php

require 'vendor/autoload.php';

use Doctrine\DBAL\DriverManager;

$connectionParams = [
    'dbname' => 'db_3ci',
    'user' => 'root',
    'password' => 'root',
    'host' => 'localhost:8889',
    'driver' => 'pdo_mysql',
];

$conn = DriverManager::getConnection($connectionParams);

$stmt = $conn->prepare('SELECT * FROM mitarbeiter');

$result = $stmt->executeQuery();

var_dump($result->fetchAllAssociative());