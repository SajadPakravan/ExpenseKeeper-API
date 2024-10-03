<?php
global $pdo;
include '../tools/db_connect.php';

setMethod('POST');
authorization();
$email = param('email');

checkEmail($email);