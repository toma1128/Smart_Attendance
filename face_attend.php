<?php
/**
* File : exchange.php
* Date : 2024/06/25
* Author : H.Kitagawa,Toma
*/
date_default_timezone_set('Asia/Tokyo');

// Pythonからデータ受け取り
if(isset($_POST['data'])){
    //データベース接続定義
    $host = 'localhost';
    $dbname = 'teamb';
    $username = 'test';
    $password = 'test';

    session_start();
    $header_no = $_SESSION['header_ID'];
    $conn = new mysqli($host, $username, $password, $dbname);

    $faceData = $_POST['data'];
    foreach ($faceData as $data) {

        $check_stuNo = "SELECT STUDENT_NO FROM STUDENT WHERE STUDENT_NO = $data";
        $stmt = $conn->prepare($check_stuNo);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows > 0) {

            $check_detail = "SELECT * FROM ATTENDANCEDETAIL WHERE HEADER_NO = $header_no AND STUDENT = $data";
            $stmt2 = $conn->prepare($check_detail);
            $stmt2->execute();
            $result2 = $stmt2->get_result();

            if($result2->num_rows > 0) {
            $update = "UPDATE ATTENDANCEDETAIL SET CAMERA = 1, CAMERA_TIME = NOW() WHERE HEADER_NO = $header_no AND STUDENT = $data";
            $stmt = $conn->prepare($update);
            $stmt->execute();
            $msg = "出席を更新しました。";
            }else{
            $insert = "INSERT INTO ATTENDANCEDETAIL VALUES ($header_no, $data, 1, NOW(), 2, NULL)";
            $stmt = $conn->prepare($insert);
            $stmt->execute();
            $msg = "出席を登録しました。";
            }
        }
        $conn->close();
    }
}