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

// 教科・クラス未選択の場合
if ($_POST['subject'] == null || $_POST['class'] == null) {
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

$ch = curl_init();
// リクエストURLを設定
curl_setopt($ch, CURLOPT_URL, "http://100.78.13.89:5000/face_attend");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
curl_setopt($ch, CURLOPT_TIMEOUT, 150);

// リクエストを送信し、レスポンスを取得
$response = curl_exec($ch);

if(curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}

// cURLリソースを閉じる
curl_close($ch);

// echo $response;

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