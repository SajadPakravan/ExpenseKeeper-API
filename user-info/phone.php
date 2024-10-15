<?php
include '_init_.php';

if (!(setMethod('POST'))) setError(405, 'Wrong Method');
authorization();
$phone = param('phone');

if (checkPhone($phone)) {
    $user = Database::select(table: 'users', where: 'phone = ?', value: [$phone]);
    if (!empty($user)) setError(400, 'Phone Used');
    sendPhoneCode($phone);
}
setError(400, 'Invalid Phone');