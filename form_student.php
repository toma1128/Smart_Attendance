<?php
//データベース接続定義
$host = 'localhost';
$dbname = 'teamb';
$username = 'test';
$password = 'test';

if(isset($_GET['id'])){
  $subject = $_GET['id'];  //授業ID取得
}

if(isset($_POST['stu_no'])) {
  // DB接続
  $conn = new mysqli($host, $username, $password, $dbname);

  $getID = "SELECT MAX(HEADER_NO) FROM ATTENDANCEHEADER;";
  $result = $conn->query($getID);
  $row = $result->fetch_row();
  $header_no = $row[0] + 1;

  // 学籍番号存在確認
  $get_stuNo = "SELECT * FROM STUDENT WHERE STUDENT_NO = ?";
  $stmt = $conn->prepare($get_stuNo);
  $stmt->bind_param('i', $_POST['stu_no']);
  $stmt->execute();
  if($result = $stmt->get_result()) {
    $row = $result->fetch_assoc();
    $class_no = $row['CLASS'];

    $exist = "SELECT * from ATTENDANCEDETAIL WHERE HEADER_NO = ? AND STUDENT = ?";
    $stmt = $conn->prepare($exist);
    $stmt->bind_param('ii', $header_no, $_POST['stu_no']);
    $stmt->execute();
    
    $msg = "出席登録しました。";
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
  <title>出席登録</title>
  <link rel="stylesheet" href="./Styles/form_student.css">

</head>
<body>
  <h1>出席登録</h1>
  <form action="./form_student.php" method="post">
    <label for="stu_no">学籍番号</label>
    <input type="text" id="stu_no" name="stu_no" placeholder="例:0000" required>
    <div id="button">
      <button type="submit">出席</button>
    </div>
  </form>
</body>
</html>