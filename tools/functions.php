<?php

use Random\RandomException;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

function setError(int $code, string $error): void
{
    http_response_code($code);
    exit(json_encode(['error' => $error]));
}

function setMethod(string $method): void
{
    if (!($_SERVER['REQUEST_METHOD'] === $method)) setError(405, 'Wrong Method');
}

function param($name): string
{
    $data = json_decode(file_get_contents('php://input'), true);
    $value = $data[$name] ?? '';
    $value = trim($value);
    if (empty($value)) setError(400, "$name Empty");
    return $value;
}

function createToken($id): string
{
    $checkUserToken = Database::select(table: 'users_token', where: 'user_id = ?', value: [$id]);
    if (!empty($checkUserToken)) Database::delete(table: 'users_token', where: ['user_id' => $id]);

    try {
        $token = bin2hex(random_bytes(16));
    } catch (RandomException $e) {
        echo $e;
    }
    $expire_at = date('Y-m-d H:i:s', time() + (60 * 60));
    Database::insert(table: 'users_token', data: [
        'user_id' => $id,
        'token' => $token,
        'expire_at' => $expire_at,
    ]);
    return $token;
}

function authorization(): int
{
    $headers = getallheaders();

    if (!isset($headers['Authorization'])) setError(401, 'Authorization Null');

    $authHeader = $headers['Authorization'];
    $token = str_replace('Bearer ', '', $authHeader);

    $userToken = Database::select(table: 'users_token', where: 'token = ? AND expire_at > ' . date('Y-m-d H:i:s'), value: [$token]);
    if (empty($userToken)) setError(401, 'Unauthorized');
    return $userToken['user_id'];
}

function upload($file, $type, $name, $size, $folder): string
{
    $format = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if ($type === 'image' && (!in_array($format, ['png', 'jpg']))) setError(400, 'Image Format Error');

    if ($file['size'] > $size) {
        $fileSize = number_format($file['size'] / (1024 * 1024), 2);
        setError(400, "File Large | $fileSize MB");
    }

    if ($file['error'] !== UPLOAD_ERR_OK) setError(500, 'Upload Error | ' . $file['error']);

    if (!(move_uploaded_file($file['tmp_name'], "../uploads/$folder/$name.$format"))) setError(500, 'Upload Error');
    return UploadAvatarUrl . "$name.$format";
}

function sendEmailCode($email): void
{
    $code = createVerifyCode($email);
    date_default_timezone_set('Asia/Tehran');
    $bodyView = file_get_contents('../views/email-verify-code-view.html');
    try {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = HostEmail;
        $mail->SMTPAuth = true;
        $mail->Username = UserEmail;
        $mail->Password = PassEmail;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->setFrom('info@yademansystem.ir', 'Expense Keeper');
        $mail->addAddress($email);
        $mail->Subject = 'Email Verification Code';
        $mail->Body = str_replace('{{code}}', $code, $bodyView);
        $mail->AltBody = $code;
        $mail->send();
        exit(json_encode(['message' => 'کد تایید به ایمیل شما با موفقیت ارسال شد']));
    } catch (Exception $e) {
        setError(503, 'Send Code False | ' . $e);
    }
}

function sendPhoneCode($phone): void
{
    $code = createVerifyCode($phone);
    $url = 'https://smspanel.trez.ir/SendPatternCodeWithUrl.ashx';
    $data = array(
        'AccessHash' => '8377d671-44e4-4560-ab25-d6d3e9e1b267',
        'Mobile' => $phone,
        'PatternId' => '1348bcee-e71a-4556-a304-d6ef6843da96',
        'token1' => $code
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);

    if (curl_errno($ch)) setError(500, 'Send Code False | ' . curl_error($ch));
    curl_close($ch);
    exit(json_encode(['message' => 'کد تایید به شماره شما با موفقیت ارسال شد', 'smsCode' => $response]));
}

function createVerifyCode($param): int
{
    $checkParam = Database::select(table: 'users_verify_code', where: 'data = ?', value: [$param]);
    if (!empty($checkParam)) Database::delete(table: 'users_verify_code', where: ['data' => $param]);

    $code = rand(100000, 999999);
    Database::insert(table: 'users_verify_code', data: [
        'data' => $param,
        'code' => $code,
        'create_at' => date('Y-m-d H:i:s'),
    ]);
    return $code;
}

function checkEmail($email): bool
{
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) return true;
    return false;
}

function checkPhone($phone): bool
{
    if (preg_match('/^09[0-9]{9}$/', $phone)) return true;
    return false;
}