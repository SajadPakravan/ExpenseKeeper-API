<?php
global $pdo;
include '../tools/db_connect.php';

setMethod('POST');
authorization();
$email = param('email');

if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $verifyCode = createVerifyCode($email);
    $result = sendEmailCode($email, $verifyCode);
    if ($result['status']) exit(json_encode(['message' => 'کد تایید به ایمیل شما با موفقیت ارسال شد']));
    setError(503, 'Send Code False | ' . $result['error']);
}
setError(400, 'Invalid Email');