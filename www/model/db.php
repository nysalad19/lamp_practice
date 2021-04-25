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

/*

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
*/

function fetch_all_query($db, $sql, $params = array()){
  try{
    $statement = $db->prepare($sql);
    $statement->execute($params);
    return $statement->fetchAll();
  }catch(PDOException $e){
    set_error('データ取得に失敗しました。');
  }
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


/*

//PDOオブジェクトの生成
02
$pdo = new PDO("mysql:dbname=test;host=localhost",USERNAME,PASSWORD);
03
 
04
//prepareメソッドでSQLをセット
05
$stmt = $pdo->prepare("select name from test where id = ? and num = ?");
06
 
07
//bindValueメソッドでパラメータをセット
08
$stmt->bindValue(1,2);
09
$stmt->bindValue(2,10);
10
 
11
//executeでクエリを実行
12
$stmt->execute();
13
 
14
//結果を表示
15
$result = $stmt->fetch();
16
echo "name = ".$result['name'];



*/