<?php
global $pdo;
include '../tools/db_connect.php';

getMethod();

$id = authorization();

$user = $pdo->prepare('SELECT * FROM users WHERE id = ?');
$user->execute([$id]);
$user = $user->fetch();

$auth = $pdo->prepare('SELECT * FROM users_auth WHERE user_id = ?');
$auth->execute([$id]);
$auth = $auth->fetch();

if ($user && $auth) exit(json_encode([
    'id' => $user['id'],
    'username' => $auth['username'],
    'name' => $user['name'],
    'email' => $user['email'] ?? '',
    'phone' => $user['phone'] ?? '',
    'avatar' => $user['avatar'],
    'create_at' => $user['create_at'],
    'login_time' => $auth['login_time']
]));
exit(json_encode(['error' => 'Get Info False', 'message' => 'دریافت اطلاعات با مشکل موجه شد']));