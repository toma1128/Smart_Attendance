<?php 
session_start();

// ログインしていない場合はログイン画面にリダイレクト
if(!isset($_SESSION['teacher_no'])) {
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
  <header>
    <div class="drawer">
      <!-- ハンバーガーメニューの表示・非表示を切り替えるチェックボックス -->
      <input type="checkbox" id="drawer-check" class="drawer-hidden">

      <!-- ハンバーガーアイコン -->
      <label for="drawer-check" class="drawer-open"><span></span></label>

      <!-- メニュー -->
      <nav class="drawer-content">
        <ul class="drawer-list">
          <li class="drawer-item">
            <a href="./regi_student.php">生徒登録</a>
          </li><!-- /.drawer-item -->
          <li class="drawer-item">
            <a href="./selectQR.php">QRコード表示</a>
          </li><!-- /.drawer-item -->
          <li class="drawer-item">
            <a href="./login.php" name="logout">ログアウト</a>
          </li>
        </ul><!-- /.drawer-list -->
      </nav>
    </div>
  </header>
  <h1>出席確認</h1>
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


  