<?php
/**
* File : exchange.php
* Date : 2024/06/25
* Author : H.Kitagawa
*/

// Pythonからデータ受け取り

print("communication test");

// 実行するPythonファイルのパス
$command = "python ./getData.py";

// 指定したパスの実行結果を配列(第2引数)に格納
exec($command, $stuNo);

// 受け取りデータ確認
var_dump($stuNo);
