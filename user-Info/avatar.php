<?php
global $pdo;
include '../tools/db_connect.php';

postMethod();

$avatar = $_FILES['avatar'] ?? '';

$id = authorization();

nullCheck($avatar, 'avatar');

$upload = upload($avatar, 'image', $id, 1024 * 1024, 'avatars');

$updateAvatar = $pdo->prepare('UPDATE users SET avatar = ? WHERE id = ?');
if (!($updateAvatar->execute([$upload['url'], $id]))) exit(json_encode(['error' => 'avatar Error', 'message' => 'تغییر آواتار با مشکل مواجه شد']));
exit(json_encode(['message' => 'آواتار با موفقیت تغییر کرد', 'avatar' => $upload['url']]));