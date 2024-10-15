<?php
header('Content-Type: multipart/form-data');
include '_init_.php';

if (!(setMethod('POST'))) setError(405, 'Wrong Method');
$avatar = $_FILES['avatar'] ?? '';
$id = authorization();
if (empty($avatar)) setError(400, 'avatar Empty');

$avatar = upload($avatar, 'image', $id, 1024 * 1024, 'avatars');
Database::update(table: 'users', set: ['avatar' => $avatar], where: ['id' => $id]);
exit(json_encode(['message' => 'آواتار شما با موفقیت تغییر کرد', 'avatar' => $avatar]));