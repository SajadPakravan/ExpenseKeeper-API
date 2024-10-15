<?php
include '_init_.php';

if (!(setMethod('POST'))) setError(405, 'Wrong Method');
authorization();
$email = param('email');

if (checkEmail($email)) {
    $user = Database::select(table: 'users', where: 'email = ?', value: [$email]);
    if (!empty($user)) setError(400, 'Email Used');
    sendEmailCode($email);
}
setError(400, 'Invalid Email');