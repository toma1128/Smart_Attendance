<?php
session_start();
date_default_timezone_set('Asia/Tokyo');

// ログインしていない場合はログイン画面にリダイレクト
if(!isset($_SESSION['teacher_no'])) {
    header("Location: ./login.php");
    exit();
}

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_no = $_POST['student_no'];
    $class_name = $_POST['class_name'];
    $sname = $_POST['sname'];
    $photo = $_FILES['photo']['tmp_name'];

    $photo_name = basename($_FILES['photo']['name']);
    $uploadDirectory = './face_images/';
    // ファイルを移動
    $destination = $uploadDirectory . $photo_name;
    move_uploaded_file($photo, $destination);

    //データベース接続定義
    $host = 'localhost';
    $dbname = 'teamb';
    $username = 'test';
    $password = 'test';

    // データベースに接続
    $conn = new mysqli($host, $username, $password, $dbname);
    $sql = "INSERT INTO STUDENT (STUDENT_NO, CLASS, SNAME, FACE_IMAGE) VALUES ('$student_no', '$class_name', '$sname', '$destination')";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>生徒アカウント作成</title>
    <link rel="stylesheet" href="./Styles/regi_student.css">
</head>
<body>
    <h1>生徒アカウント作成</h1>
    <form action="./regi_student.php" method="POST" enctype="multipart/form-data">
        <label for="student_name">名前</label>
        <input type="text" name="sname" id="student_name" placeholder="学生名を入力ください" required>
        <label for="photo">顔写真</label>
        <input type="file" id="photo" name="photo" required>
        <label for="student_no">学籍番号</label>
        <input type="text" id="student_no" name="student_no" placeholder="学籍番号を入力してください" required>
        <label for="class_name">クラス番号</label>
        <input type="text" id="class_name" name="class_name" placeholder="クラス番号を選んでください" required> 
        <div id="button">
            <button type="submit">登録</button>
        </div>
    </form>
    <button onclick="location.href='./home.php'">ホーム画面へ戻る</button>
</body>
</html>