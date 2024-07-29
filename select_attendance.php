<?php

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
        <img src="images/logo1.jpg" alt="logo" >
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
        </div>
    </header>
</body>
</html>