<?php
include("functions.php");

// DB接続
$pdo = connect_to_db();

$search_word = $_GET['searchword'];
// $search_word = "業";

$sql = "SELECT * FROM todo_table2 WHERE todo LIKE '%{$search_word}%'";
// SQL準備&実行
$stmt = $pdo->prepare($sql);
$status = $stmt->execute();

// データ登録処理後
if ($status == false) {
  // SQL実行に失敗した場合はここでエラーを出力し，以降の処理を中止する
  $error = $stmt->errorInfo();
  echo json_encode(["error_msg" => "{$error[2]}"]);
  exit();
} else {
  // 正常にSQLが実行された場合は入力ページファイルに移動し，入力ページの処理を実行する
  $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
  // var_dump($result);
  // header("Content-Type: application/json; charset=UTF-8");
  echo json_encode($result);
  exit();
}