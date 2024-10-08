<?php
/**
 * 生徒登録
 * @author Toma
 */
require '../.config/forwarding_address.php';
session_start();
date_default_timezone_set('Asia/Tokyo');

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

// クラス名とクラスIDを取得
$sel_class = "SELECT CLASS_NO, CNAME FROM CLASS";
$stmt = $conn->prepare($sel_class);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $class[] = $row;
}

if($_SERVER['REQUEST_METHOD'] === 'POST') {

    $student_no = $_POST['student_no'];
    $class_name = $_POST['class_name'];
    $sname = $_POST['sname'];
    $photo = $_FILES['photo']['tmp_name'];

    if (!(preg_match('/^\d{7}$/', $student_no))) {
        // 有効な7桁の数字の場合の処理
        echo '<script>
        alert("学籍番号は7桁の数字で入力してください。");
        window.location.href = "./regi_student.php";
        </script>';
        exit;
    }

    if (empty($student_no) || empty($class_name) || empty($sname)) {
        echo '<script>alert("すべての項目を入力してください。");
        window.location.href = "./regi_student.php";
        </script>';
        exit;
    }

    $photo = $_FILES['photo'];
    if (!isset($photo['name']) || !is_string($photo['name'])) {
        echo '<script>
        alert("ファイルのアップロードに問題がありました。");
        window.location.href = "./regi_student.php";
        </script>';
        exit;
    }
    
    $file_extension = strtolower(pathinfo($photo['name'], PATHINFO_EXTENSION));

    // 許可する拡張子
    $allowed_extensions = ['jpg', 'jpeg', 'png'];
    $file_extension = strtolower(pathinfo($photo['name'], PATHINFO_EXTENSION));

    // ファイルが画像かどうかをチェック
    if (!in_array($file_extension, $allowed_extensions) || !getimagesize($photo['tmp_name'])) {
        echo '<script>
        alert("アップロードできるのは画像ファイル（jpg, jpeg, png）のみです。");
        window.location.href = "./regi_student.php";
        </script>';
        exit;
    }

    $photo_name = $student_no . '.' . $file_extension;
    $ftp_connect = ftp_connect($ftp_server_address, 21);

    if(@ftp_login($ftp_connect, $ftp_username, $ftp_password)) {
        ftp_pasv($ftp_connect, true);
        $destination = $photo_name;
    }else{
        echo '<script>
        alert("FTP接続に失敗しました。");
        window.location.href = "./regi_student.php";
        </script>';
        exit;
    }
    
    if(is_uploaded_file($photo['tmp_name']) && is_readable($photo['tmp_name'])) {
        try{
            // 学生番号の重複チェック
            $check_stmt = $conn->prepare("SELECT COUNT(*) FROM STUDENT WHERE STUDENT_NO = ?");
            $check_stmt->bind_param("s", $student_no);
            $check_stmt->execute();
            $check_stmt->bind_result($count);
            $check_stmt->fetch();
            $check_stmt->close();

            if($count > 0) {
                echo '<script>alert("この学籍番号は既に登録されています。")</script>';
                exit;
            }
            //if(move_uploaded_file($photo['tmp_name'], $destination)) {
            if(ftp_put($ftp_connect, $face_send_pass.$photo_name, $photo['tmp_name'], FTP_BINARY)) {
                // データベースに接続
                $ins_stu = "INSERT INTO STUDENT (STUDENT_NO, CLASS, SNAME, FACE_IMAGE) VALUES ('$student_no', '$class_name', '$sname', '$face_send_pass')";
                $stmt = $conn->prepare($ins_stu);
                if($stmt->execute()){
                    echo '<script>alert("生徒アカウントを作成しました。")</script>';
                    $result = $stmt->get_result();
                }else{
                    echo '<script>alert("生徒アカウントの作成に失敗しました。")</script>';
                }
            }else {
                throw new Exception('ファイルの移動に失敗しました。');
            }
        } catch (Exception $e) {
            $conn->rollback();
            echo $e->getMessage();
            error_log($e->getMessage());
            if(file_exists($destination)) {
                unlink($destination);
            }
        }
    }else {
        echo '<script>alert("アップロードされたファイルが見つからないか、読み取れません。")</script>';
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>生徒アカウント作成</title>
    <link rel="stylesheet" href="./Styles/regi_student.css">
</head>
<body>
    <!-- ヘッダー部分 -->
    <header>
        <img src="images/logo1.jpg" alt="logo" >
        <div class="header-title">生徒アカウント作成</div>
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
    <form action="./regi_student.php" method="POST" enctype="multipart/form-data">
        <label for="student_name">名前</label>
        <input type="text" name="sname" id="student_name" placeholder="学生名を入力ください" required>
        <label for="photo">顔写真</label>
        <input type="file" id="photo" name="photo" required>
        <label for="student_no">学籍番号</label>
        <input type="text" id="student_no" name="student_no" placeholder="学籍番号を入力してください" required>
        <label for="class_name">クラス</label>
        <select name="class_name" id="class_name">
            <?php foreach ($class as $c) : ?>
            <option value="<?= $c['CLASS_NO'] ?>"><?php echo $c['CNAME']; ?></option>
            <?php endforeach ?>
        </select>
        <div id="button">
            <button type="submit">登録</button>
        </div>
    </form>
</body>
</html>