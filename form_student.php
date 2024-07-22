<?php
/**
 * QR出席登録
 * @author Toma
 */
//データベース接続定義
$host = 'localhost';
$dbname = 'teamb';
$username = 'test';
$password = 'test';

date_default_timezone_set('Asia/Tokyo');

if(isset($_GET['id'])){
  $header_no = $_GET['id'];  //ヘッダーID取得
}
if(isset($_POST['id'])){
  $header_no = $_POST['id'];
}

if(isset($_POST['stu_no'])) {
  // DB接続
  $conn = new mysqli($host, $username, $password, $dbname);

  // 学籍番号存在確認
  $check_stuNo = "SELECT STUDENT_NO FROM STUDENT WHERE STUDENT_NO = ?";
  $stmt = $conn->prepare($check_stuNo);
  $stmt->bind_param('s', $_POST['stu_no']);
  $stmt->execute();
  $result = $stmt->get_result();
  if($result->num_rows > 0) {

    $check_detail = "SELECT * FROM ATTENDANCEDETAIL WHERE HEADER_NO = ? AND STUDENT = ?";
    $stmt2 = $conn->prepare($check_detail);
    $stmt2->bind_param('is', $header_no, $_POST['stu_no']);
    $stmt2->execute();
    $result2 = $stmt2->get_result();
    
    if($result2->num_rows > 0) {
      $update = "UPDATE ATTENDANCEDETAIL SET QR = 1, QR_TIME = NOW() WHERE HEADER_NO = ? AND STUDENT = ?";
      $stmt = $conn->prepare($update);
      $stmt->bind_param('is', $header_no, $_POST['stu_no']);
      $stmt->execute();
      $msg = "出席を更新しました。";
    }else{
      $insert = "INSERT INTO ATTENDANCEDETAIL VALUES (?, ?, 2, NULL, 1, NOW())";
      $stmt = $conn->prepare($insert);
      $stmt->bind_param('is', $header_no, $_POST['stu_no']);
      $stmt->execute();
      $msg = "出席を登録しました。";
    }
  }else{
    $msg = "学籍番号が存在しません。";
  }
  $conn->close();

  echo "<script>alert('".$msg."')</script>";
}

?>
<!doctype html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>出席登録</title>
  <link rel="stylesheet" href="./Styles/form_student.css">
</head>

<body>
  <header>
    <img src="./images/logo1.jpg" alt="logo" >
    <div class="header-content">
      <h1 class="header-title">ECCコンピュータ専門学校</h1>
    </div>
  </header>
  <div class="container">
    <form action="./form_student.php?id=<?=$header_no ?>" method="post">
      <h1>出席登録</h1>
      <div class="form-group">
        <label for="stu_no">学籍番号</label>
        <input type="text" id="stu_no" name="stu_no" placeholder="例:0000" required>
      </div>
      <div class="button-container">
        <button type="submit">出席</button>
      </div>
    </form>
  </div>
</body>
</html>