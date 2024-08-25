<?php
/**
 * QRコード表示画面
 * @author Toma
 */
require '../.config/forwarding_address.php';
session_start();
date_default_timezone_set('Asia/Tokyo');

// ログインしていない場合はログイン画面にリダイレクト
if(!isset($_SESSION['teacher_no'])) {
  header("Location: ./login.php");
  exit();
}

if(isset($_POST['subject']) && isset($_POST['class'])) {
  $_SESSION['QR_subject'] = $_POST['subject'];
  $_SESSION['QR_class'] = $_POST['class'];
}

// 教科・クラス未選択の場合
if ($_SESSION['QR_subject'] == null || $_SESSION['QR_class'] == null) {
  // セッション変数でエラーメッセージ引渡
  $_SESSION['error'] = "教科・クラスの選択が完了していません。";

  // 選択画面へリダイレクト
  header("Location: ./selectQR.php");
  exit();
}

//データベース接続定義
$host = 'localhost';
$dbname = 'teamb';
$username = 'test';
$password = 'test';

$conn = new mysqli($host, $username, $password, $dbname);


if($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['face_attend'])) {
  //attendanceheaderのIDを取得
  $getID = "SELECT MAX(HEADER_NO) FROM ATTENDANCEHEADER;";
  $result = $conn->query($getID);
  $row = $result->fetch_row();
  $header_no = $row[0] + 1;
  $_SESSION['header_ID'] = $header_no;

  //attendanceheaderに挿入
  $ins_header = "INSERT INTO ATTENDANCEHEADER VALUES (?, ?, ?, ?, NOW())";
  $stmt = $conn->prepare($ins_header);
  $stmt->bind_param('iiii', $header_no, $_POST['subject'], $_SESSION['teacher_no'], $_POST['class']);
  $stmt->execute();
}
$conn->close();

if(isset($_POST['face_attend'])) {
  $ch = curl_init();
  // リクエストURLを設定
  curl_setopt($ch, CURLOPT_URL, $camera_address);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
  curl_setopt($ch, CURLOPT_TIMEOUT, 150);

  // リクエストを送信し、レスポンスを取得
  $response = curl_exec($ch);

  if(curl_errno($ch)) {
    echo "<script>alert('エラー発生')</script>";
  } else {
    echo "<script>alert('正常終了')</script>";
  }
  // cURLリソースを閉じる
  curl_close($ch);
}
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
<img src="images/logo1.jpg" alt="logo" >
    <h1 class="header-title">ECCコンピュータ専門学校</h1>
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
          <a href="./select_attendance.php">出席確認</a>
        </li><!-- /.drawer-item -->
        <li class="drawer-item">
          <a href="./login.php" name="logout">ログアウト</a>
        </li>
      </ul><!-- /.drawer-list -->
    </nav>
  </div>
</header>
  <main>
    <div id="qr_container">
      <div id="QRcode">
        <?php echo '<img src="create_QR.php" />'; ?>
      </div>
      <form id="faceAttendForm" action="./QRcode.php" method="POST">
        <input type="hidden" name="face_attend" value="1">
        <a href="./QRcode.php" onclick="document.getElementById('faceAttendForm').submit(); return false;" class="attend_btn">顔認証</a>
      </form>
    </div>
  </main>
</body>
</html>