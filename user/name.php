<?php
global $pdo;
include '../tools/db_connect.php';

setMethod('POST');
$id = authorization();
$name = param('name');

$updateName = $pdo->prepare('UPDATE users SET name = ? WHERE id = ?');
$updateName->execute([$name, $id]);
exit(json_encode(['message' => 'نام شما با موفقیت تغییر کرد', 'name' => $name]));