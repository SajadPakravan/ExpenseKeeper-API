<?php
include '_init_.php';

setMethod('POST');
$id = authorization();
$name = param('name');

$updateName = db()->prepare('UPDATE users SET name = ? WHERE id = ?');
$updateName->execute([$name, $id]);
exit(json_encode(['message' => 'نام شما با موفقیت تغییر کرد', 'name' => $name]));