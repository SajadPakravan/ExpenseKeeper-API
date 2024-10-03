<?php
global $pdo;
include '../tools/db_connect.php';

setMethod('POST');
$id = authorization();
$data = param('data');

checkPhone($data);
checkEmail($data);