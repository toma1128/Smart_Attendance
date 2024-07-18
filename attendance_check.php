<?php
/**
 * 出席確認
 * @author Toma
 */
session_start();

// ログインしていない場合はログイン画面にリダイレクト
if (!isset($_SESSION['teacher_no'])) {
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

try {
  //code...

  //出席データを取得
$attend_sql = "SELECT DETAIL.STUDENT AS STUDENT, DETAIL.CAMERA AS CAMERA, DETAIL.CAMERA_TIME AS CTIME, DETAIL.QR AS QR, DETAIL.QR_TIME AS QTIME, STU.SNAME AS NAME, C.CNAME AS CLASS, SUB.SNAME AS SUBJECT, HEADER.LESSON_DATE AS DATE
FROM ATTENDANCEDETAIL AS DETAIL LEFT JOIN STUDENT AS STU ON (DETAIL.STUDENT = STU.STUDENT_NO) 
LEFT JOIN CLASS AS C ON (STU.CLASS = C.CLASS_NO) 
LEFT JOIN ATTENDANCEHEADER AS HEADER ON (DETAIL.HEADER_NO = HEADER.HEADER_NO) 
LEFT JOIN SUBJECT AS SUB ON (HEADER.SUBJECT = SUB.SUBJECT_NO)";

$where = " WHERE 1=1";
$params = array();

$order = " ORDER BY HEADER.LESSON_DATE, DETAIL.STUDENT";

if (!empty($_POST['class'])) {
  $where .= " AND C.CLASS_NO = ?";
  $params[] = $_POST['class'];
}
if (!empty($_POST['subject'])) {
  $where .= " AND SUB.SUBJECT_NO = ?";
  $params[] = $_POST['subject'];
}
if (!empty($_POST['date'])){
  $where .= " AND HEADER.LESSON_DATE = ?";
  $params[] = $_POST['date'];
}

$stmt = $conn->prepare($attend_sql . $where . $order);

if (!empty($params)) {
  if (!empty($_POST['date'])){
    $types = str_repeat('i', count($params)-1);
    $types .= "s";
  }else {
  $types = str_repeat('i', count($params));
  }
  $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

//出欠区分を取得
$getAttendSQL = "SELECT ANAME FROM ATTENDANCE";
$getAttendStmt = $conn->prepare($getAttendSQL);
$getAttendStmt->execute();
$getAttendResult = $getAttendStmt->get_result();
foreach ($getAttendResult as $row) {
  $attendance[] = $row['ANAME'];
}

//授業IDを取得
$getSubject = "SELECT SUBJECT_NO, SNAME FROM SUBJECT";
$getSubjectStmt = $conn->prepare($getSubject);
$getSubjectStmt->execute();
$getSubjectResult = $getSubjectStmt->get_result();
while ($row = $getSubjectResult->fetch_assoc()) {
  $subject[] = $row;
}

$getClass = "SELECT * FROM CLASS";
$getclassStmt = $conn->prepare($getClass);
$getclassStmt->execute();
$getclassResult = $getclassStmt->get_result();
while ($row = $getclassResult->fetch_assoc()) {
  $class[] = $row;
}
} catch (Exception $e) {
  echo $e;
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
    <img src="images/logo1.jpg" alt="logo">
    <div class="header-content">
      <h1 class="header-title">出席確認</h1>
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
    </div>
  </header>

  <form action="./attendance_check.php" class="search-form" name="search" method="POST">
    <select name="class" class="class-select">
      <option value="">クラスを選択</option>
      <?php foreach ($class as $c): ?>
        <option value="<?= $c['CLASS_NO'] ?>"><?php echo $c['CNAME']; ?></option>
      <?php endforeach ?>
    </select>
    <select name="subject" class="course-select">
      <option value="">授業を選択</option>
      <?php foreach ($subject as $s): ?>
        <option value="<?= $s['SUBJECT_NO'] ?>"><?= $s['SNAME'] ?></option>
      <?php endforeach ?>
    </select>
    <label for="date">日付を選択</label>
    <input type="date" id="date" name="date" value="">
    <button type="submit" class="search-button">検索</button>
  </form>

  <table>
    <tr>
      <th>学籍番号</th>
      <th>名前</th>
      <th>クラス</th>
      <th>授業</th>
      <th>日付</th>
      <th>カメラ出席</th>
      <th>カメラ時刻</th>
      <th>QR出席</th>
      <th>QR時刻</th>
    </tr>
    <?php foreach ($result as $r): ?>
      <tr>
        <td><?= htmlspecialchars($r['STUDENT']) ?></td>
        <td><?= htmlspecialchars($r['NAME']) ?></td>
        <td><?= htmlspecialchars($r['CLASS']) ?></td>
        <td class="subject"><?= htmlspecialchars($r['SUBJECT']) ?></td>
        <td><?= $r['DATE'] ?></td>
        <td><?= htmlspecialchars($attendance[$r['CAMERA'] - 1]) ?></td>
        <td><?php
        if ($r['CTIME'] != null) {
          echo substr($r['CTIME'], 0, -3);
        } else {
          echo $r['CTIME'];
        } ?></td>
        <td><?= htmlspecialchars($attendance[$r['QR'] - 1]) ?></td>
        <td><?php
        if ($r['QTIME'] != null) {
          echo substr($r['QTIME'], 0, -3);
        } else {
          echo $r['QTIME'];
        } ?></td>
      </tr>
    <?php endforeach ?>
  </table>
</body>

</html>