<?php
include '_init_.php';

setMethod('GET');
$id = authorization();

$user = Database::select(table: 'users', where: 'id = ?', value: [$id]);
$auth = Database::select(table: 'users', where: 'user_id = ?', value: [$id]);

exit(json_encode([
    'id' => $user['id'],
    'username' => $auth['username'],
    'name' => $user['name'],
    'email' => $user['email'] ?? '',
    'phone' => $user['phone'] ?? '',
    'avatar' => $user['avatar'],
    'create_at' => $user['create_at'],
    'login_time' => $auth['login_time']
]));