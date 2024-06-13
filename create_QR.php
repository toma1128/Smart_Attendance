<?php
session_start();
$QR_ID = $_SESSION['QR_ID'];

// ライブラリ読み込み
require_once "phpqrcode/qrlib.php";

// 画像の保存場所
$filepath = './qrcode/qr.png';

// QRコードの内容
$contents = "http://100.126.131.15/form_student.php?id=$QR_ID";

// QRコード画像を出力
QRcode::png($contents, $filepath, QR_ECLEVEL_M, 6);

//このファイルを画像ファイルとして扱う宣言
header('Content-Type: qrcode/png');
readfile('./qrcode/qr.png');
?>