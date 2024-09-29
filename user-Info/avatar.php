<?php

$avatar = $_FILES['avatar'] ?? '';
function editAvatar($avatar, int $id): void
{
    global $response;
    if (!(empty($avatar))) $response += ['avatar' => upload($avatar, 'image', $id, 1024 * 1024)];
}