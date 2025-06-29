<?php

$dsn = "mysql:host=localhost;dbname=iran;charset=utf8mb4";
try {
    $pdo = new PDO($dsn,'root','');
} catch (PDOException $e) {
    die($e->getMessage());
}