<?php
global $pdo;
include '../user/email.php';
include '../user/phone.php';

setMethod('POST');
$data = param('data');

checkPhone($data);
checkEmail($data);