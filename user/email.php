<?php
global $pdo;
include '../tools/db_connect.php';

setMethod('POST');
authorization();
$email = param('email');

if (filter_var($email, FILTER_VALIDATE_EMAIL)) sendEmailCode($email);
setError(400, 'Invalid Email');