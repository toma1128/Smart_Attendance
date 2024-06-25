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
<header>
  <div class="header-content">
    <h1 class="header-title">ECCコンピュータ専門学校</h1>
  </div>
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