<?php
session_start();

// ログアウトボタンが押された時の処理
if(isset($_POST['logout'])) {
  unset($_SESSION['teacher_no']);
  header("Location: ./login.php");
  exit();
}

// ログインしていない場合はログイン画面にリダイレクト
if(!isset($_SESSION['teacher_no'])) {
  header("Location: ./login.php");
  exit();
}
?>
<!doctype html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>Button Sample</title>
  <link rel="stylesheet" href="./Styles/home.css">

</head>
<body>
  <form action="./home.php" method="post">
    <button name="logout">ログアウト</button>
  </form>
  <button onclick="location.href='./regi_student.php'">生徒登録</button><br>
  <button onclick="location.href='./selectQR.php'">QRコード表示</button><br>
  <button onclick="location.href='./attendance_check.php'">出席確認</button><br>
</body>
</html>
