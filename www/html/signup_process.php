<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';

session_start();

if(is_logined() === true){
  redirect_to(HOME_URL);
}

// POST送信で送られてきた情報を取得
$name = get_post('name');
$password = get_post('password');
$password_confirmation = get_post('password_confirmation');
$token = get_post('token');

// ポストで送られてきたトークンと、セッションのトークンが一致しない場合
if (is_valid_csrf_token($token) === false) {
  // エラー文をセッションに保存
  set_error('不正なアクセスです。');
  // サインアップページへリダイレクト
	redirect_to(SIGNUP_URL);
}

$db = get_db_connect();

try{
  $result = regist_user($db, $name, $password, $password_confirmation);
  if( $result=== false){
    set_error('ユーザー登録に失敗しました。');
    redirect_to(SIGNUP_URL);
  }
}catch(PDOException $e){
  set_error('ユーザー登録に失敗しました。');
  redirect_to(SIGNUP_URL);
}

set_message('ユーザー登録が完了しました。');
login_as($db, $name, $password);
redirect_to(HOME_URL);