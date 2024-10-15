<?php
include '_init_.php';

setMethod('POST');
$id = authorization();
$name = param('name');

Database::update(table: 'users', set: ['name' => $name], where: ['id' => $id]);
exit(json_encode(['message' => 'نام شما با موفقیت تغییر کرد', 'name' => $name]));