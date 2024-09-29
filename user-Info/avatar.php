<?php
global $pdo;
include '../tools/db_connect.php';

postMethod();

$avatar = $_FILES['avatar'] ?? '';

if (!(empty($avatar))) exit(json_encode(upload($avatar, 'image', $id, 1024 * 1024)));