<?php
global $pdo;
include '../tools/db_connect.php';

setMethod('POST');
authorization();
$phone = param('phone');

checkPhone($phone);