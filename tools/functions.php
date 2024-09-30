<?php

use Random\RandomException;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

function postMethod(): void
{
    if (!($_SERVER['REQUEST_METHOD'] === 'POST')) {
        http_response_code(405);
        exit(json_encode(['error' => 'Wrong Method', 'message' => 'از متد POST استفاده کنید']));
    }
}

function nullCheck($param, $paramName): void
{
    if (empty($param)) {
        http_response_code(400);
        exit(json_encode(['error' => "$paramName Empty", 'message' => "$paramName وارد نشده است"]));
    }
}

function createToken($id): void
{
    global $pdo;
    $checkUserToken = $pdo->prepare('SELECT * FROM users_token WHERE user_id = ?');
    $checkUserToken->execute([$id]);
    if ($checkUserToken->fetch()) {
        $deleteToken = $pdo->prepare('DELETE FROM users_token WHERE user_id = ?');
        $deleteToken->execute([$id]);
    }

    try {
        $token = bin2hex(random_bytes(16));
    } catch (RandomException $e) {
        echo $e;
    }
    $expire_at = time() + (60 * 60);
    $insertToken = $pdo->prepare('INSERT INTO users_token (user_id, token, expire_at) VALUES (?, ?, FROM_UNIXTIME(?))');

    if ($insertToken->execute([$id, $token, $expire_at])) {
        $response = ['status' => 'SignIn True', 'message' => 'ورود با موفقیت انجام شد', 'token' => $token];
    } else {
        $response = ['status' => 'Create Token Error', 'message' => 'خطا در ایجاد توکن'];
    }
    exit(json_encode($response));
}

function authorization(): int
{
    global $pdo;
    $headers = getallheaders();

    if (!isset($headers['Authorization'])) {
        http_response_code(401);
        exit(json_encode(['error' => 'Authorization Null', 'message' => 'توکن ارسال نشده است']));
    }

    $authHeader = $headers['Authorization'];
    $token = str_replace('Bearer ', '', $authHeader);

    $checkToken = $pdo->prepare('SELECT * FROM users_token WHERE token = ? AND expire_at > NOW()');
    $checkToken->execute([$token]);
    $userToken = $checkToken->fetch();

    if (!$userToken) {
        http_response_code(401);
        exit(json_encode(['error' => 'Unauthorized', 'message' => 'توکن معتبر نیست']));
    }

    return $userToken['user_id'];
}

function upload($file, $type, $name, $size, $folder): array
{
    $format = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if ($type === 'image') {
        $imgFormats = ['png', 'jpg'];
        if (!in_array($format, $imgFormats)) {
            http_response_code(400);
            exit(json_encode(['error' => 'File Format Error', 'message' => 'پسوند فایل نا معتبر است', 'format' => strtoupper(implode(', ', $imgFormats))]));
        }
    }

    if ($file['size'] > $size) {
        http_response_code(400);
        $fileSize = number_format($file['size'] / (1024 * 1024), 2);
        exit(json_encode(['error' => 'File Big', 'message' => 'عکس شما بیشتر از ۱ مگابایت است', 'fileSize' => $fileSize]));
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        http_response_code(500);
        exit(json_encode(['error' => 'Upload Error', 'message' => 'مشکلی در آپلود فایل پیش آمده است: ' . $file['error']]));
    }

    if (!(move_uploaded_file($file['tmp_name'], "../uploads/$folder/$name.$format"))) {
        http_response_code(500);
        exit(json_encode(['error' => 'Upload Error', 'message' => 'مشکلی در آپلود فایل پیش آمده است']));
    }
    return ['url' => UploadAvatarUrl . $name . '.' . $format];
}

function sendEmailCode($email, $code): array
{
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
        $mail->setFrom('info@yademansystem.ir', 'Expense Keeper | هزینه بان');
        $mail->addAddress($email);
        $mail->Subject = 'Email Verification Code';
        $mail->Body = str_replace('{{code}}', $code, $bodyView);
        $mail->AltBody = '888888888888888';
        $mail->send();
        return ['status' => true];
    } catch (Exception $e) {
        return ['status' => false, 'error' => $e->getMessage()];
    }
}

function createVerifyCode($param): array
{
    global $pdo;

    $checkParam = $pdo->prepare('SELECT * FROM users_verify_code WHERE data = ?');
    $checkParam->execute([$param]);
    $checkParam = $checkParam->fetch();
    if ($checkParam) {
        $deleteCode = $pdo->prepare('DELETE FROM users_verify_code WHERE data = ?');
        $deleteCode->execute([$param]);
    }

    $code = rand(100000, 999999);
    $insertCode = $pdo->prepare('INSERT INTO users_verify_code (data, code, create_at) VALUES (?, ?, NOW())');
    $insertCode->execute([$param, $code]);
    return ['status' => true, 'code' => $code];
}