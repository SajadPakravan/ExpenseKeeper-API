<?php
global $db;
include '../tools/db_connect.php';

setMethod('GET');
$id = authorization();

$user = $db->prepare('SELECT * FROM users WHERE id = ?');
$user->execute([$id]);
$user = $user->fetch();

$auth = $db->prepare('SELECT * FROM users_auth WHERE user_id = ?');
$auth->execute([$id]);
$auth = $auth->fetch();

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