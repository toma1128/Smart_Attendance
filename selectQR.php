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
        <title>ドロップダウンメニュー</title>
        <link rel="stylesheet" href="./Styles/selectQR.css">
    </head>
    <body>
        <form action="./QRcode.php" method="post">
            <select name="QR_ID">
                <?php foreach ($result as $row) : ?>
                <option value="<?= $i ?>"><?php echo $row['SNAME']; ?></option>
                <?php $i++; endforeach ?>
            </select>
            <br>
            <div>
                <button onclick="location.href='./QRcode.php'">QRコードを表示</button>
            </div>
        </form>
        <div>
            <button onclick="location.href='./home.php'">戻る</button>
        </div>
    </body>
</html>