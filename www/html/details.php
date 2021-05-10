<?php
// 定数ファイルを読み込み
require_once '../conf/const.php';
// 汎用関数ファイルを読み込み
require_once MODEL_PATH . 'functions.php';
// userデータに関する関数ファイルを読み込み
require_once MODEL_PATH . 'user.php';
// cartデータに関する関数ファイルを読み込み
require_once MODEL_PATH . 'cart.php';

// ログインチェックを行うため、セッションを開始する
session_start();

// PDOを取得
$db = get_db_connect();
// PDOを利用してログインユーザーのデータを取得
$user = get_login_user($db);

// POST送信で送られてきた情報を取得
$token = get_post('token');
$order_id = get_post('order_id');

// $order_idが空もしくは整数以外の場合
if ($order_id === '' || $order_id !== REGEXP_POSITIVE_INTEGER) {
  // エラー文をセッションに保存
  set_error('不正なアクセスです。');
  // 履歴ページへリダイレクト
	redirect_to(HISTORY_URL);
}

// ポストで送られてきたトークンと、セッションのトークンが一致しない場合
if (is_valid_csrf_token($token) === false) {
  // エラー文をセッションに保存
  set_error('不正なアクセスです。');
  // ログインページへリダイレクト
	redirect_to(LOGIN_URL);
}

// 特定の商品購入履歴の取得
$history = get_specific_purchased_history($db, $order_id, $user);

// 商品購入明細の取得
$details = get_purchased_details($db, $order_id, $user);

// ビューの読み込み。
include_once VIEW_PATH . 'details_view.php';