<?php
global $pdo;
include '../tools/db_connect.php';

postMethod();

$name = $_POST['name'] ?? '';

$id = authorization();

nullCheck($name, 'name');

$updateName = $pdo->prepare('UPDATE users SET name = ? WHERE id = ?');
if (!($updateName->execute([$name, $id]))) exit(json_encode(['error' => 'name Error', 'message' => 'تغییر نام با مشکل مواجه شد']));
exit(json_encode(['message' => 'نام با موفقیت تغییر کرد', 'name' => $name]));