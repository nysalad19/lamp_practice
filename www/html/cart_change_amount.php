<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';

session_start();

if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

$db = get_db_connect();
$user = get_login_user($db);

// POST送信で送られてきた情報を取得
$cart_id = get_post('cart_id');
$amount = get_post('amount');
$token = get_post('token');

// ポストで送られてきたトークンと、セッションのトークンが一致しない場合
if (is_valid_csrf_token($token) === false) {
  // エラー文をセッションに保存
  set_error('不正なアクセスです。');
  // ログインページへリダイレクト
	redirect_to(LOGIN_URL);
}

if(update_cart_amount($db, $cart_id, $amount)){
  set_message('購入数を更新しました。');
} else {
  set_error('購入数の更新に失敗しました。');
}

// カートページへリダイレクト
redirect_to(CART_URL);