<?php
/**
 * ログイン画面
 * @author Toma
 */
session_start();

//データベース接続定義
$host = 'localhost';
$dbname = 'teamb';
$username = 'test';
$password = 'test';
unset($_SESSION['teacher_no']);

if(isset($_POST['teacher_no'])) {
    // DB接続
    $conn = new mysqli($host, $username, $password, $dbname);
    $sql = "SELECT PASS FROM TEACHER WHERE TEACHER_NO = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $_POST['teacher_no']);
    $stmt->execute();
    $result = $stmt->get_result();

    $row = $result->fetch_assoc();
    $pass = $row['PASS'];
    if($pass == $_POST['password']){
        $_SESSION['teacher_no'] = $_POST['teacher_no'];
        header("Location: ./attendance_check.php");
        exit();
    }
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>教員ログイン</title>
    <link rel="stylesheet" href="./Styles/login.css">
</head>
<body>
    <h1>教員ログイン</h1>
    <form action="./login.php" method="post">
        <label for="teacher_no">教員番号</label>
        <input type="text" id="teacher_no" name="teacher_no" placeholder="教員番号を入力ください" required> <br>
        <label for="password">パスワード</label>
        <input type="text" id="password" name="password" placeholder="パスワードを入力ください" required>
        <div id="button">
            <button type="submit">ログイン</button>
        </div>
    </form>
</body>
</html>