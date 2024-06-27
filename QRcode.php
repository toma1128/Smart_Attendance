<?php
/**
 * QRコード表示画面
 * @author Toma
 */
session_start();
date_default_timezone_set('Asia/Tokyo');

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

$conn = new mysqli($host, $username, $password, $dbname);

if($_SERVER['REQUEST_METHOD'] === 'POST') {
//attendanceheaderのIDを取得
$getID = "SELECT MAX(HEADER_NO) FROM ATTENDANCEHEADER;";
$result = $conn->query($getID);
$row = $result->fetch_row();
$header_no = $row[0] + 1;
$_SESSION['header_ID'] = $header_no;

//attendanceheaderに挿入
$ins_header = "INSERT INTO ATTENDANCEHEADER VALUES ($header_no, $_POST[subject], $_SESSION[teacher_no], $_POST[class], NOW())";
$stmt = $conn->prepare($ins_header);
$stmt->execute();
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>QRコード</title>
    <link rel="stylesheet" href="./Styles/QRcode.css">
</head>
<body>
<header>
  <div class="header-content">
    <h1 class="header-title">ECCコンピュータ専門学校</h1>
  </div>
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
          <a href="./selectQR.php">QRコード選択</a>
        </li><!-- /.drawer-item -->
        <li class="drawer-item">
          <a href="attendance_check.php">出席確認</a>
        </li><!-- /.drawer-item -->
        <li class="drawer-item">
          <a href="./login.php" name="logout">ログアウト</a>
        </li>
      </ul><!-- /.drawer-list -->
    </nav>
  </div>
</header>
    <div id="QRcode">
        <?php echo '<img src="create_QR.php" />'; ?>
    </div>
</body>
</html>