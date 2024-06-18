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

$conn = new mysqli($host, $username, $password, $dbname);
$sql = "SELECT SNAME FROM SUBJECT";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

$conn->close();

$i = 1;
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
            <div class="header-content">
                <h1><div class="header-title">ECCコンピュータ専門学校</div></h1>
                <div class="drawer">
                    <!-- ハンバーガーメニューの表示・非表示を切り替えるチェックボックス -->
                    <input type="checkbox" id="drawer-check" class="drawer-hidden">
                    <!-- ハンバーガーアイコン -->
                    <label for="drawer-check" class="drawer-open"><span></span></label>
                    <!-- メニュー -->
                    <nav class="drawer-content">
                        <ul class="drawer-list">
                            <li class="drawer-item">
                                <a href="attendance_check.php">出席確認</a>
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
            </div>
        </header>
        <main>
            <form action="./QRcode.php" method="post">
                <select name="QR_ID">
                    <?php foreach ($result as $row) : ?>
                    <option value="<?= $i ?>"><?php echo $row['SNAME']; ?></option>
                    <?php $i++; endforeach ?>
                </select>
                <br>
                <div id="button">
                    <button onclick="location.href='./QRcode.php'">QRコードを表示</button>
                </div>
            </form>
        </main>
    </body>
</html>