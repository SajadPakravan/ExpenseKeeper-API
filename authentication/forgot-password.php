<?php
include '_init_.php';

if (!(setMethod('POST'))) setError(405, 'Wrong Method');
$data = param('data');

if (checkEmail($data)) {
    if (!(checkData($data, 'email'))) setError(400, 'Email Not');
    sendEmailCode($data);
}
if (checkPhone($data)) {
    if (!(checkData($data, 'phone'))) setError(400, 'Phone Not');
    sendPhoneCode($data);
}
setError(400, 'Invalid Input');

function checkData($param, $paramName): bool
{
    $user = Database::select(table: 'users', where: "$paramName = ?", value: [$param]);
    if (empty($user)) return false;
    return true;
}