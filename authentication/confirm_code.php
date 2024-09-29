<?php
//global $pdo;
//include '../tools/db_connect.php';
//
//$response = '';
//
//if (postMethod()) {
//    $emailPhone = $_POST['emailPhone'] ?? '';
//    $code = $_POST['code'] ?? '';
//
//    if (empty($email)) {
//        http_response_code(400);
//        $response = ['status' => 'Email Null', 'message' => 'ایمیل را وارد کنید'];
//        exit(json_encode($response));
//    }
//
//    if (empty($code)) {
//        http_response_code(400);
//        $response = ['status' => 'Code Null', 'message' => 'کد تایید را وارد کنید'];
//        exit(json_encode($response));
//    }
//
//    $query = $pdo->prepare('SELECT * FROM users_verify_code WHERE email = ? AND code = ?');
//    $query->execute([$email, $code]);
//    $verification = $query->fetch();
//
//    if ($verification) {
//
//    } else {
//        http_response_code(400);
//        $response = ['status' => 'Verification Code False', 'message' => 'کد تایید نامعتبر است'];
//    }
//}
//echo json_encode($response);