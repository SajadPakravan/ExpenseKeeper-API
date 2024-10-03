<?php
global $pdo;
include '../tools/db_connect.php';

setMethod('POST');
$data = param('data');

checkPhone($data);
checkEmail($data);