<?php
/**
* File : exchange.php
* Date : 2024/06/25
* Author : H.Kitagawa
*/

// Pythonからデータ受け取り
if(isset($_POST['testdata'])){
    echo "Pythonから受け取ったテストデータ : ".$_POST['testdata'];
    $faceData = $_POST['testdata'];
    $faceData = json_decode($faceData, true);
    var_dump(is_int($faceData));
}