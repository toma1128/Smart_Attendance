<?php
session_start();

// 未ログイン時、ログイン画面へリダイレクト
if (!isset($_SESSION['teacher_no'])) {
    header("Location: ./login.php");
    exit();
}

//データベース接続定義
$host = 'localhost';                // ホスト
$dbname = 'teamb';                  // DB名
$username = $password = 'test';     // ユーザ名、パスワード

// DB接続
$conn = new mysqli($host, $username, $password, $dbname);

// クラス番号、クラス名取得
$class_sql = "SELECT CLASS_NO, CNAME FROM CLASS;";

// SQL文実行
$stmt = $conn->prepare($class_sql);
$stmt->execute();

// 実行結果取得
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $class[] = $row;
}

?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>教室選択</title>
    <link rel="stylesheet" href="./Styles/select_attendance.css">
</head>

<body>
    <header>
        <img src="images/logo1.jpg" alt="logo">
        <div class="header-title">出席確認選択</div>
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
                    </li>
                    <li class="drawer-item">
                        <a href="./selectQR.php">QRコード表示</a>
                    </li>
                    <li class="drawer-item">
                        <a href="./login.php" name="logout">ログアウト</a>
                    </li>
                </ul>
            </nav>
        </div>
    </header>
    <main>
        <div class="doors-container">
            <form action="./attendance_check.php" method="POST">
                <?php foreach ($class as $c): ?>
                    <div class="doors-wrapper">
                        <button type="submit" name="class" value="<?= htmlspecialchars($c['CLASS_NO'], ENT_QUOTES, 'UTF-8') ?>" class="door-container">
                            <p><?= htmlspecialchars($c['CNAME'], ENT_QUOTES, 'UTF-8') ?></p>
                            <div class="door-bg"></div>
                            <div class="door"></div>
                        </button>
                    </div>
                <?php endforeach ?>
            </form>
        </div>
    </main>
</body>

</html>