<?php
include 'settings.php';
include 'functions.php';

try {
    $pdo = new PDO(DSN, USER, PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    exit(json_encode(['error' => 'Connection failed: ' . $e->getMessage()]));
}

