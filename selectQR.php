<?php
/**
 * QRコード選択
 * @author Toma
 */
session_start();

// ログインしていない場合はログイン画面にリダイレクト
if (!isset($_SESSION['teacher_no'])) {
    header("Location: ./login.php");
    exit();
}

// エラーメッセージ取得、破棄
if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
}

//データベース接続定義
$host = 'localhost';
$dbname = 'teamb';
$username = 'test';
$password = 'test';

$conn = new mysqli($host, $username, $password, $dbname);
$get_subject = "SELECT SNAME, SUBJECT_NO FROM SUBJECT";
$stmt = $conn->prepare($get_subject);
$stmt->execute();
$result = $stmt->get_result();

// クラス名とクラスIDを取得
$sel_class = "SELECT CLASS_NO, CNAME FROM CLASS";
$stmt = $conn->prepare($sel_class);
$stmt->execute();
$class_result = $stmt->get_result();
while ($row = $class_result->fetch_assoc()) {
    $class[] = $row;
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>授業選択</title>
    <link rel="stylesheet" href="./Styles/selectQR.css">
</head>

<body>
    <!-- ヘッダー部分 -->
    <header>
        <img src="./images/logo1.jpg" alt="logo" >
        <div class="header-title">QRコード選択</div>
        <div class="drawer">
            <!-- ハンバーガーメニューの表示・非表示を切り替えるチェックボックス -->
            <input type="checkbox" id="drawer-check" class="drawer-hidden">
            <!-- ハンバーガーアイコン -->
            <label for="drawer-check" class="drawer-open"><span></span></label>
            <!-- メニュー -->
            <nav class="drawer-content">
                <ul class="drawer-list">
                    <li class="drawer-item">
                        <a href="./select_attendance.php">出席確認</a>
                    </li>
                    <li class="drawer-item">
                        <a href="regi_student.php">生徒登録</a>
                    </li>
                    <li class="drawer-item">
                        <a href="./login.php" name="logout">ログアウト</a>
                    </li>
                </ul>
            </nav>
        </div>
    </header>
    <main>
        <form action="./QRcode.php" method="post">
            <div>
                <select name="subject" size="1">
                    <option value="" selected disabled>コースを選択してください</option>
                    <?php foreach ($result as $row): ?>
                        <option value="<?= $row['SUBJECT_NO'] ?>"><?php echo $row['SNAME']; ?></option>
                    <?php endforeach ?>
                </select>
            </div>
            <div>
                <select name="class" size="1">
                    <option value="" selected disabled>クラスを選択してください</option>
                    <?php foreach ($class as $c): ?>
                        <option value="<?= $c['CLASS_NO'] ?>"><?php echo $c['CNAME']; ?></option>
                    <?php endforeach ?>
                </select>
            </div>
            <?php if (isset($error)) { ?>
                <p class="error"><?php echo $error ?></p>
            <?php } ?>
            <div id="button">
                <button onclick="location.href='./QRcode.php'">QRコードを表示</button>
            </div>
        </form>
    </main>
</body>

</html>