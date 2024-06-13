<?php
session_start();

$_SESSION['QR_ID'] = $_POST['QR_ID'];   //QRコードのIDをセッションに保存
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>QRコード</title>
    <link rel="stylesheet" href="./Styles/QRcode.css">
</head>
<body>
    <h3>QRコード</h3>
    <div id="QRcode">
        <?php echo '<img src="create_QR.php" />'; ?>
    </div>
    <button onclick="location.href='./selectQR.php'">戻る</button>
</body>
</html>