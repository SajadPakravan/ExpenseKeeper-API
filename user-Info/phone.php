<?php
global $pdo;
include '../tools/db_connect.php';

setMethod();

$phone = $_POST['phone'] ?? '';

$id = authorization();

nullCheck($phone, 'phone');

if (preg_match('/^09[0-9]{9}$/', $phone)) {
    $verifyCode = createVerifyCode($phone);

    $url = 'https://smspanel.trez.ir/SendPatternCodeWithUrl.ashx';
    $data = array(
        'AccessHash' => '8377d671-44e4-4560-ab25-d6d3e9e1b267',
        'Mobile' => $phone,
        'PatternId' => '1348bcee-e71a-4556-a304-d6ef6843da96',
        'token1' => $verifyCode['code']
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);

    if (curl_errno($ch)) exit(json_encode(['error' => 'Send Code False', 'message' => 'ارسال کد تایید با خطا مواجه شد: ' . curl_error($ch)]));
    curl_close($ch);
    exit(json_encode(['message' => 'کد تایید به شماره شما با موفقیت ارسال شد', 'smsCode' => $response]));
}