<?php
//global $pdo;
//include '../tools/db_connect.php';
//
//$code = 0;
//$username = '';
//$password = '';
//$response = '';
//
//if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//    $username = $_POST['emailPhone'] ?? '';
//
//    if (empty($username)) {
//        http_response_code(400);
//        $response = ['status' => 'emailPhone Null', 'message' => 'ایمیل یا شماره تلفن همراه را وارد کنید'];
//        exit(json_encode($response));
//    }
//
//    if (preg_match('/^09[0-9]{9}$/', $username)) {
//        if (createCode('p')) {
//            $url = 'https://smspanel.trez.ir/SendPatternCodeWithUrl.ashx';
//            $data = array(
//                'AccessHash' => '8377d671-44e4-4560-ab25-d6d3e9e1b267',
//                'Mobile' => $username,
//                'PatternId' => '1348bcee-e71a-4556-a304-d6ef6843da96',
//                'token1' => $code
//            );
//
//            $ch = curl_init();
//            curl_setopt($ch, CURLOPT_URL, $url);
//            curl_setopt($ch, CURLOPT_POST, 1);
//            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
//            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//
//            $response = curl_exec($ch);
//
//            if (curl_errno($ch)) {
//                echo 'خطا در ارسال: ' . curl_error($ch);
//            } else {
//                echo 'پاسخ وب سرویس: ' . $response;
//            }
//
//            curl_close($ch);
//            $response = ['status' => 'emailPhone Null', 'message' => 'ایمیل یا شماره تلفن همراه را وارد کنید'];
//            exit(json_encode($response));
//        }
//    }
//
//    if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
//        $code = rand(100000, 999999);
//        $query = $pdo->prepare('INSERT INTO users_verify_code (email, phone, code, create_at) VALUES (?, null, ?, NOW())');
//
//        if ($query->execute([$username, $code])) {
//            date_default_timezone_set('Asia/Tehran');
//
//            require '../tools/send_email.php';
//
//            $bodyView = file_get_contents('../views/email_verification_code_view.html');
//            $body = str_replace('{{code}}', $code, $bodyView);
//            $body = str_replace('{{expire}}', date('H:i', $create_at), $body);
//            $result = sendEmail($username, 'Email Verification Code', $body);
//
//            if ($result['status']) {
//                $response = ['status' => 'Send Code True', 'message' => 'کد تایید به ایمیل شما ارسال شد'];
//            } else {
//                http_response_code(405);
//                $response = ['status' => 'Send Code False', 'message' => 'ارسال کد تایید با خطا مواجه شد', 'error' => $result['error']];
//            }
//        }
//    } else {
//        http_response_code(400);
//        $response = ['status' => 'Invalid Input', 'message' => 'ایمیل یا شماره تلفن همراه وارد شده معتبر نیست'];
//        exit(json_encode($response));
//    }
//} else {
//    http_response_code(401);
//    $response = ['status' => 'Wrong Method', 'message' => 'باید از متد POST استفاده کنید'];
//}
//echo json_encode($response);
//
//function createCode($ep): bool
//{
//    global $pdo, $username;
//    $query = '';
//    $code = rand(100000, 999999);
//    if ($ep == 'e') $query = $pdo->prepare('INSERT INTO users_verify_code (email, phone, code, create_at) VALUES (?, null, ?, NOW())');
//    if ($ep == 'p') $query = $pdo->prepare('INSERT INTO users_verify_code (email, phone, code, create_at) VALUES (null, ?, ?, NOW())');
//    if (!($query->execute([$username, $code]))) {
//        $response = ['status' => 'Create Code Error', 'message' => 'خطا در ایجاد کد احراز هویت'];
//        exit(json_encode($response));
//    }
//    return true;
//}
//
//function sendCodePhone()
//{
//
//}