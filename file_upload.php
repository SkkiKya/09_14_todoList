<?php
// var_dump($_FILES);
//送信されたときにエラーが出てないかチェック
if(isset($_FILES['upfile']) && $_FILES['upfile']['error'] == 0){
// 正常にファイルが送られてきたときの処理

  $uploadedFileName = $_FILES['upfile']['name'];  //ファイルネームを取得
  $tempPathName = $_FILES['upfile']['tmp_name'];  //tmpファイルの場所
  $fileDirectoryPath = 'upload/';               //アップロード先のフォルダ

  $extension = pathinfo($uploadedFileName, PATHINFO_EXTENSION);    // ファイルごとに拡張子の種類を取得
  $uniqueName = date('YmdHis').md5(session_id()) . "." . $extension; //ファイルごとにユニークな名前を作成
  $fileNameToSave = $fileDirectoryPath.$uniqueName;                 //ファイルの保存場所にファイルを保存

  // [upload/hogehoge.png]のようになる
// print_r($fileNameToSave);
// exit();
  if(is_uploaded_file($tempPathName)) {
    if(move_uploaded_file($tempPathName, $fileNameToSave)){
      chmod($fileNameToSave, 0644);
      $img='<img src="'. $fileNameToSave  . '">"';
    }
  }else {
    exit('Error:画像がありません');
  }
}else {
  // 送られていない，エラー発生，などの場合
  exit('画像が送信されていません');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>file_upload</title>
</head>

<body>
  <?= $img ?>

</body>

</html>