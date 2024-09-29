<?php
//global $pdo;
//include '../tools/db_connect.php';
//
//try {
//    $updateQuery = 'UPDATE authentication a JOIN tokens t ON a.id = t.user_id SET a.status = 0, a.signout_time = NOW() WHERE t.expire_time < NOW()';
//    $update = $pdo->prepare($updateQuery);
//    $update->execute();
//
//    if ($update->rowCount() > 0) {
//        $deleteQuery = 'DELETE FROM tokens WHERE expire_time < NOW()';
//        $delete = $pdo->prepare($deleteQuery);
//        $delete->execute();
//    } else {
//        error_log('No Update Status and SignOutTime >>>> ' . date('Y-m-d H:i:s'));
//    }
//} catch (PDOException $e) {
//    error_log('Expire Token Error >>>> ' . $e->getMessage() . ' >>>> ' . date('Y-m-d H:i:s'));
//}