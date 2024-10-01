<?php
global $pdo;
include '../tools/db_connect.php';

setMethod();

$email = $_POST['email'] ?? '';

authorization();

nullCheck($email, 'email');

if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $verifyCode = createVerifyCode($email);
    if ($verifyCode['status']) {
        $result = sendEmailCode($email, $verifyCode['code']);
        if ($result['status']) exit(json_encode(['message' => 'کد تایید به ایمیل شما با موفقیت ارسال شد']));
        http_response_code(503);
        exit(json_encode(['error' => 'Send Code False', 'message' => 'ارسال کد تایید با خطا مواجه شد: ' . $result['error']]));
    }
} else {
    http_response_code(400);
    exit(json_encode(['error' => 'Invalid Email', 'message' => 'ایمیل نامعتبر است']));
}