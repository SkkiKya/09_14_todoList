<?php
session_start();
include("functions.php");
check_session_id();

if (
  !isset($_POST['todo']) || $_POST['todo'] == '' ||
  !isset($_POST['deadline']) || $_POST['deadline'] == ''
) {
  // 項目が入力されていない場合はここでエラーを出力し，以降の処理を中止する
  echo json_encode(["error_msg" => "no input"]);
  exit();
}

// 受け取ったデータを変数に入れる
$todo = $_POST['todo'];
$deadline = $_POST['deadline'];


// ここからファイルアップロード&DB登録の処理を追加しよう！！！
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
        // $img='<img src="'. $fileNameToSave  . '">"';
      }
    }else {
      exit('Error:画像がありません');
    }
  }else {
    // 送られていない，エラー発生，などの場合
    exit('画像が送信されていません');
  }


  // DB接続
$pdo = connect_to_db();
  // データ登録SQL作成
// `created_at`と`updated_at`には実行時の`sysdate()`関数を用いて実行時の日時を入力する
$sql = 'INSERT INTO todo_table2(id, todo, deadline,image, created_at, updated_at) VALUES(NULL, :todo, :deadline,:imagepath, sysdate(), sysdate())';

// SQL準備&実行
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':todo', $todo, PDO::PARAM_STR);
$stmt->bindValue(':deadline', $deadline, PDO::PARAM_STR);
$stmt->bindValue(':imagepath', $fileNameToSave, PDO::PARAM_STR);
$status = $stmt->execute();

// データ登録処理後
if ($status == false) {
  // SQL実行に失敗した場合はここでエラーを出力し，以降の処理を中止する
  $error = $stmt->errorInfo();
  echo json_encode(["error_msg" => "{$error[2]}"]);
  exit();
} else {
  // 正常にSQLが実行された場合は入力ページファイルに移動し，入力ページの処理を実行する
  header("Location:todo_input.php");
  exit();
}