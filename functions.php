<?php

// セッションスタート
session_start();

// データベース接続関数
function connect_to_db() {
  
  $db = 'mysql';
  $dbname = 'gsacf_d06_db14';
  $chart = 'utf8';
  $port = '3308';
  $host = 'localhost';
  
  // DB接続の設定
  $dbn = "$db:dbname=$dbname;charset=$chart;port=$port;host=$host";
  $user = 'root';
  $pwd = '';
  // DB接続を確認
  try {
    // ここでDB接続処理を実行する
    $pdo = new PDO($dbn, $user, $pwd);
    return $pdo;
  } catch (PDOException $e) {
    // DB接続に失敗した場合はここでエラーを出力し，以降の処理を中止する
    echo json_encode(["db error" => "{$e->getMessage()}"]);
    exit();
  }  
}

// ログイン状態のチェック関数
function check_session_id()
{
  if (
    !isset($_SESSION["session_id"]) ||
    $_SESSION["session_id"] != session_id()
  ) {
    header("Location:todo_login.php");
  } else {
    session_regenerate_id(true);
    $_SESSION["session_id"] = session_id();
  }
}
