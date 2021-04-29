<?php

function get_db_connect(){
  // MySQL用のDSN文字列
  $dsn = 'mysql:dbname='. DB_NAME .';host='. DB_HOST .';charset='.DB_CHARSET;
 
  try {
    // データベースに接続
    $dbh = new PDO($dsn, DB_USER, DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4'));
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
  } catch (PDOException $e) {
    exit('接続できませんでした。理由：'.$e->getMessage() );
  }
  return $dbh;
}

// レコードを取得する。出来なかった場合、falseを返す。
function fetch_query($db, $sql, $params = array()){
  try{
    // SQL文を実行する準備
    $statement = $db->prepare($sql);
    // SQL文を実行
    $statement->execute($params);
    // 取得したレコードを返す
    return $statement->fetch();
  // SQL文の実行ができなかった場合
  }catch(PDOException $e){
    // セッションにエラー文を追加する。
    set_error('データ取得に失敗しました。');
  }
  // faleseを返す
  return false;
}

// 全レコードを取得する。出来なかった場合、falseを返す。
function fetch_all_query($db, $sql, $params = array()){
  try{
    // SQL文を実行する準備
    $statement = $db->prepare($sql);
    // SQL文を実行
    $statement->execute($params);
    // 取得した全レコードを返す
    return $statement->fetchAll();
  // SQL文の実行ができなかった場合
  }catch(PDOException $e){
    // セッションにエラー文を追加する。
    set_error('データ取得に失敗しました。');
  }
  // faleseを返す
  return false;
}

// SQL文を実行する。出来なかった場合、falseを返す。
function execute_query($db, $sql, $params = array()){
  try{
    // SQL文を実行する準備
    $statement = $db->prepare($sql);
    // SQL文の実行を返す
    return $statement->execute($params);
    // SQL文の実行ができなかった場合
  }catch(PDOException $e){
    // セッションにエラー文を追加する。
    set_error('更新に失敗しました。');
  }
  // faleseを返す
  return false;
}