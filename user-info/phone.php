<?php
include '_init_.php';

setMethod('POST');
authorization();
$phone = param('phone');

if (checkPhone($phone)) {
    $user = Database::select(table: 'users', where: 'phone = ?', value: [$phone]);
    if (!empty($user)) setError(400, 'Phone Used');
    sendPhoneCode($phone);
}
setError(400, 'Invalid Phone');