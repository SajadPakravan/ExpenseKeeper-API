<?php
include '_init_.php';

setMethod('POST');
$data = param('data');

if (checkEmail($data)) {
    if (!(checkData($data, 'email'))) setError(400, 'Email Used');
    sendEmailCode($data);
}
if (checkPhone($data)) {
    if (!(checkData($data, 'phone'))) setError(400, 'Phone Used');
    sendPhoneCode($data);
}
setError(400, 'Invalid Input');

function checkData($param, $paramName): bool
{
    $user = Database::select(table: 'users', where: "$paramName = ?", value: [$param]);
    if (!empty($user)) return false;
    return true;
}