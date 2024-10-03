<?php
global $db;
include '../tools/db_connect.php';

setMethod('POST');
$id = authorization();
$name = param('name');

$updateName = $db->prepare('UPDATE users SET name = ? WHERE id = ?');
$updateName->execute([$name, $id]);
exit(json_encode(['message' => 'نام شما با موفقیت تغییر کرد', 'name' => $name]));