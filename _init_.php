<?php
header('Content-Type: application/json');
header('Accept: application/json');

include 'tools/functions.php';
include 'tools/db_connect.php';
include 'tools/settings.php';

$pdo = Database::getConnection();