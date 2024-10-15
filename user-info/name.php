<?php
include '_init_.php';

if (!(setMethod('POST'))) setError(405, 'Wrong Method');
$id = authorization();
$name = param('name');

Database::update(table: 'users', set: ['name' => $name], where: ['id' => $id]);
exit(json_encode(['message' => 'نام شما با موفقیت تغییر کرد', 'name' => $name]));