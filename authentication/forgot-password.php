<?php
global $pdo;
include '../tools/db_connect.php';

setMethod('POST');
$data = param('data');

if (filter_var($data, FILTER_VALIDATE_EMAIL)) sendEmailCode($data);
if (preg_match('/^09[0-9]{9}$/', $data)) sendPhoneCode($data);
setError(400, 'Invalid Input');