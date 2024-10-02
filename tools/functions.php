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
    $insertToken->execute([$id, $token, $expire_at]);
    return $token;
}

function authorization(): int
{
    global $pdo;
    $headers = getallheaders();

    if (!isset($headers['Authorization'])) setError(401, 'Authorization Null');

    $authHeader = $headers['Authorization'];
    $token = str_replace('Bearer ', '', $authHeader);

    $checkToken = $pdo->prepare('SELECT * FROM users_token WHERE token = ? AND expire_at > NOW()');
    $checkToken->execute([$token]);
    $userToken = $checkToken->fetch();
    if (!$userToken) setError(401, 'Unauthorized');

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
        $mail->setFrom('info@yademansystem.ir', 'Expense Keeper');
        $mail->addAddress($email);
        $mail->Subject = 'Email Verification Code';
        $mail->Body = str_replace('{{code}}', $code, $bodyView);
        $mail->AltBody = $code;
        $mail->send();
        return ['status' => true];
    } catch (Exception $e) {
        return ['status' => false, 'error' => $e->getMessage()];
    }
}

function createVerifyCode($param): int
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
    return $code;
}