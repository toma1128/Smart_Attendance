<?php
/**
 * QRコード生成
 * @author Toma
 */
require './.config/forwarding_address.php';
session_start();
$header_ID = $_SESSION['header_ID'];

// ライブラリ読み込み
require_once "phpqrcode/qrlib.php";

// 画像の保存場所
$filepath = './qrcode/qr.png';

// QRコードの内容
$contents = $QR_form_address.$header_ID;
// QRコード画像を出力
QRcode::png($contents, $filepath, QR_ECLEVEL_M, 6);

//このファイルを画像ファイルとして扱う宣言
header('Content-Type: qrcode/png');
readfile('./qrcode/qr.png');
?>