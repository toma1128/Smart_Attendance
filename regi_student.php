<?php
/**
 * 生徒登録
 * @author Toma
 */
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
    $_FILES['photo']['name'] = $student_no . '.jpg';

    if (empty($student_no) || empty($class_name) || empty($sname)) {
        echo '<script>alert("すべての項目を入力してください。")</script>';
        exit;
    }

    $photo_name = $_FILES['photo']['name'];
    $uploadDirectory = './.face_images/';

    // ディレクトリが存在しない場合は作成
    if (!file_exists($uploadDirectory)) {
        mkdir($uploadDirectory, 0777, true);
    }
    // ファイルを移動
    $destination = $uploadDirectory . $photo_name;
    if(is_uploaded_file($photo) && is_readable($photo)) {
        try{
            if(move_uploaded_file($photo, $destination)) {
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

        
                // データベースに接続
                $ins_stu = "INSERT INTO STUDENT (STUDENT_NO, CLASS, SNAME, FACE_IMAGE) VALUES ('$student_no', '$class_name', '$sname', '$destination')";
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