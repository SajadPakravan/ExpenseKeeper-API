<?php
include '_init_.php';

setMethod('GET');
$id = authorization();

$user = Database::select(table: 'users', where: 'id = ?', value: [$id]);
$auth = Database::select(table: 'users_auth', where: 'user_id = ?', value: [$id]);

exit(json_encode([
    'id' => $user[0]['id'],
    'username' => $auth[0]['username'],
    'name' => $user[0]['name'],
    'email' => $user[0]['email'] ?? '',
    'phone' => $user[0]['phone'] ?? '',
    'avatar' => $user[0]['avatar'],
    'create_at' => $user[0]['create_at'],
    'login_time' => $auth[0]['login_time']
]));