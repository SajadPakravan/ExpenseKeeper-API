<?php
header('Content-Type: multipart/form-data');
include '_init_.php';

setMethod('POST');
$avatar = $_FILES['avatar'] ?? '';
$id = authorization();
if (empty($avatar)) setError(400, 'avatar Empty');

$upload = upload($avatar, 'image', $id, 1024 * 1024, 'avatars');
$updateAvatar = db()->prepare('UPDATE users SET avatar = ? WHERE id = ?');
$updateAvatar->execute([$upload, $id]);
exit(json_encode(['message' => 'آواتار شما با موفقیت تغییر کرد', 'avatar' => $upload]));