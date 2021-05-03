<?php
// 定数ファイルの読み込み
require_once '../conf/const.php';
// 汎用関数ファイルを読み込み
require_once MODEL_PATH . 'functions.php';
// userデータに関する関数ファイルを読み込み
require_once MODEL_PATH . 'user.php';
// itemデータに関する関数ファイルを読み込み
require_once MODEL_PATH . 'item.php';

// ログインチェックを行うため、セッションを開始する
session_start();

if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

$db = get_db_connect();

$user = get_login_user($db);

if(is_admin($user) === false){
  redirect_to(LOGIN_URL);
}

// POST送信で送られてきた情報を取得
$item_id = get_post('item_id');
$changes_to = get_post('changes_to');
$token = get_post('token');

// ポストで送られてきたトークンと、セッションのトークンが一致しない場合
if (is_valid_csrf_token($token) === false) {
  // エラー文をセッションに保存
  set_error('不正なアクセスです。');
  // ログインページへリダイレクト
	redirect_to(LOGIN_URL);
}

if($changes_to === 'open'){
  update_item_status($db, $item_id, ITEM_STATUS_OPEN);
  set_message('ステータスを変更しました。');
}else if($changes_to === 'close'){
  update_item_status($db, $item_id, ITEM_STATUS_CLOSE);
  set_message('ステータスを変更しました。');
}else {
  set_error('不正なリクエストです。');
}


redirect_to(ADMIN_URL);