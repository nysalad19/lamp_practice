<?php
// 汎用関数ファイルを読み込み
require_once MODEL_PATH . 'functions.php';
// データベース接続用のファイルを読み込み
require_once MODEL_PATH . 'db.php';

function get_user($db, $user_id){
  $sql = "
    SELECT
      user_id, 
      name,
      password,
      type
    FROM
      users
    WHERE
      user_id = {$user_id}
    LIMIT 1
  ";

  // レコードを取得する。出来なかった場合、falseを返す。
  return fetch_query($db, $sql);
}

function get_user_by_name($db, $name){
  $sql = "
    SELECT
      user_id, 
      name,
      password,
      type
    FROM
      users
    WHERE
      name = '{$name}'
    LIMIT 1
  ";

  // レコードを取得する。出来なかった場合、falseを返す。
  return fetch_query($db, $sql);
}

function login_as($db, $name, $password){
  $user = get_user_by_name($db, $name);
  if($user === false || $user['password'] !== $password){
    // falseを返す
    return false;
  }
  // セッションにキーと値をセットする
  set_session('user_id', $user['user_id']);
  return $user;
}

function get_login_user($db){
  // 変数にセッションの「user_id」キーに入っている値を代入する
  $login_user_id = get_session('user_id');

  return get_user($db, $login_user_id);
}

function regist_user($db, $name, $password, $password_confirmation) {
  if( is_valid_user($name, $password, $password_confirmation) === false){
    return false;
  }
  
  return insert_user($db, $name, $password);
}

// ユーザーが管理者かどうか判定
function is_admin($user){
  // ユーザーが管理者（1）ならtrue、違う場合はfalseを返す
  return $user['type'] === USER_TYPE_ADMIN;
}

function is_valid_user($name, $password, $password_confirmation){
  // 短絡評価を避けるため一旦代入。
  $is_valid_user_name = is_valid_user_name($name);
  $is_valid_password = is_valid_password($password, $password_confirmation);
  return $is_valid_user_name && $is_valid_password ;
}

// $nameの値がユーザー名の規定に沿っているか判定
function is_valid_user_name($name) {
  // 変数にtrueを代入
  $is_valid = true;
  // $nameの値の長さが、規定の長さの範囲内じゃない場合
  if(is_valid_length($name, USER_NAME_LENGTH_MIN, USER_NAME_LENGTH_MAX) === false){
    // セッションにエラー文をセットする
    set_error('ユーザー名は'. USER_NAME_LENGTH_MIN . '文字以上、' . USER_NAME_LENGTH_MAX . '文字以内にしてください。');
    // 変数にfalseを代入
    $is_valid = false;
  }
  // 変数が正の整数かアルファベット1文字以上ではない場合
  if(is_alphanumeric($name) === false){
    // セッションにエラー文をセットする
    set_error('ユーザー名は半角英数字で入力してください。');
    // 変数にfalseを代入
    $is_valid = false;
  }
  // $is_valid変数を返す
  return $is_valid;
}

function is_valid_password($password, $password_confirmation){
  $is_valid = true;
  if(is_valid_length($password, USER_PASSWORD_LENGTH_MIN, USER_PASSWORD_LENGTH_MAX) === false){
    set_error('パスワードは'. USER_PASSWORD_LENGTH_MIN . '文字以上、' . USER_PASSWORD_LENGTH_MAX . '文字以内にしてください。');
    $is_valid = false;
  }
  if(is_alphanumeric($password) === false){
    set_error('パスワードは半角英数字で入力してください。');
    $is_valid = false;
  }
  if($password !== $password_confirmation){
    set_error('パスワードがパスワード(確認用)と一致しません。');
    $is_valid = false;
  }
  return $is_valid;
}

function insert_user($db, $name, $password){
  $sql = "
    INSERT INTO
      users(name, password)
    VALUES ('{$name}', '{$password}');
  ";

  return execute_query($db, $sql);
}

