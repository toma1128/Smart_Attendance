<?php 
session_start();

// ログインしていない場合はログイン画面にリダイレクト
if(!isset($_SESSION['teacher_no'])) {
  header("Location: ./login.php");
  exit();
}

// ログアウトボタンが押された時の処理
if(isset($_POST['logout'])) {
  unset($_SESSION['teacher_no']);
  header("Location: ./login.php");
  exit();
}

//データベース接続定義
$host = 'localhost';
$dbname = 'teamb';
$username = 'test';
$password = 'test';

// DB接続 

$conn = new mysqli($host, $username, $password, $dbname);
$sql = "SELECT DETAIL.STUDENT AS STUDENT, DETAIL.CAMERA AS CAMERA, DETAIL.QR AS QR, STU.SNAME AS NAME, C.CNAME AS CLASS FROM ATTENDANCEDETAIL AS DETAIL LEFT JOIN STUDENT AS STU ON (DETAIL.STUDENT = STU.STUDENT_NO) LEFT JOIN CLASS AS C ON (STU.CLASS = C.CLASS_NO)";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

//出欠区分を取得
$getAttendSQL = "SELECT ANAME FROM ATTENDANCE";
$getAttendStmt = $conn->prepare($getAttendSQL);
$getAttendStmt->execute();
$getAttendResult = $getAttendStmt->get_result();
foreach ($getAttendResult as $row2) {
  $attendance[] = $row2['ANAME'];
}
$conn->close(); //接続切断
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>出席確認</title>
    <link rel="stylesheet" href="./Styles/attendance_check.css">
</head>
<body>
    <h1>出席確認</h1>
    <button onclick="location.href='./home.php'">ホーム画面へ戻る</button>
    <form action="./home.php" method="post">
      <button name="logout">ログアウト</button>
    </form>
    <table>
      <tr>
        <th>学籍番号</th>
        <th>名前</th>
        <th>クラス</th>
        <th>カメラ出席</th>
        <th>QRコード出席</th>
      </tr>
      <?php foreach ($result as $row) : ?>
      <tr>
        <td><?= $row['STUDENT'] ?></td>
        <td><?= $row['NAME'] ?></td>
        <td><?= $row['CLASS'] ?></td>
        <td><?= $attendance[$row['CAMERA']-1] ?></td>
        <td><?= $attendance[$row['QR']-1] ?></td>
      </tr>
      <?php endforeach ?>
    </table>
</body>
</html>


  