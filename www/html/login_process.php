<?php
// 定数ファイルを読み込み
require_once '../conf/const.php';
// 汎用関数ファイルを読み込み
require_once MODEL_PATH . 'functions.php';
// userデータに関する関数ファイルを読み込み
require_once MODEL_PATH . 'user.php';

// ログインチェックを行うため、セッションを開始する
session_start();

// ログインチェック用関数を利用
if(is_logined() === true){
  // ログインしていない場合はトップページにリダイレクト
  redirect_to(HOME_URL);
}

// POST送信で送られてきた情報を取得
$name = get_post('name');
$password = get_post('password');
$token = get_post('token');

// ポストで送られてきたトークンと、セッションのトークンが一致しない場合
if (is_valid_csrf_token($token) === false) {
  // エラー文をセッションに保存
  set_error('不正なアクセスです。');
  // ログインページへリダイレクト
	redirect_to(LOGIN_URL);
}

// PDOを取得
$db = get_db_connect();


$user = login_as($db, $name, $password);
if( $user === false){
  set_error('ログインに失敗しました。');
  redirect_to(LOGIN_URL);
}

set_message('ログインしました。');
if ($user['type'] === USER_TYPE_ADMIN){
  redirect_to(ADMIN_URL);
}
// トップページへリダイレクト
redirect_to(HOME_URL);