<?php
//データベース接続定義
$host = 'localhost';
$dbname = 'teamb';
$username = 'test';
$password = 'test';

if(isset($_POST['stu_no'])) {
  // DB接続
  $conn = new mysqli($host, $username, $password, $dbname);
  $sql = "SELECT STUDENT_NO FROM STUDENT WHERE STUDENT_NO = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('i', $_POST['stu_no']);
  $stmt->execute();
  // if($result = $stmt->get_result()) {
  //   $row = $result->fetch_assoc();
  //   $class_no = $row['CLASS_NO'];
  //   $sql = "UPDATE STUDENT SET ATTEND = 1 WHERE STUDENT_NO = ?";
  // }
  $conn->close();
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