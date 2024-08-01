<?php
/**
* File : exchange.php
* Date : 2024/06/25
* Author : H.Kitagawa,Toma
*/
date_default_timezone_set('Asia/Tokyo');

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jsonData = file_get_contents('php://input');
    $data = json_decode($jsonData, true);
}

// Pythonからデータ受け取り
if(isset($data['data'])){
    //データベース接続定義
    $host = 'localhost';
    $dbname = 'teamb';
    $username = 'test';
    $password = 'test';

    session_start();
    $header_no = $_SESSION['header_ID'];
    $conn = new mysqli($host, $username, $password, $dbname);

    $student_no = json_decode($data['data'], true);

    foreach ($student_no as $data) {
        $check_stuNo = "SELECT STUDENT_NO FROM STUDENT WHERE STUDENT_NO = ?";
        $stmt = $conn->prepare($check_stuNo);
        $stmt->bind_param("s", $data);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows > 0) {

            $check_detail = "SELECT * FROM ATTENDANCEDETAIL WHERE HEADER_NO = ? AND STUDENT = ?";
            $stmt2 = $conn->prepare($check_detail);
            $stmt2->bind_param("is", $header_no, $data);
            $stmt2->execute();
            $result2 = $stmt2->get_result();

            if($result2->num_rows > 0) {
            $update = "UPDATE ATTENDANCEDETAIL SET CAMERA = 1, CAMERA_TIME = NOW() WHERE HEADER_NO = ? AND STUDENT = ?";
            $stmt = $conn->prepare($update);
            $stmt->bind_param("is", $header_no, $data);
            $stmt->execute();
            }else{
            $insert = "INSERT INTO ATTENDANCEDETAIL VALUES (?, ?, 1, NOW(), 2, NULL)";
            $stmt = $conn->prepare($insert);
            $stmt->bind_param("is", $header_no, $data);
            $stmt->execute();
            }
        }
        $conn->close();
    }
}