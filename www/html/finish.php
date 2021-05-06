<?php
// 定数ファイルを読み込み
require_once '../conf/const.php';
// 汎用関数ファイルを読み込み
require_once MODEL_PATH . 'functions.php';
// userデータに関する関数ファイルを読み込み
require_once MODEL_PATH . 'user.php';
// itemデータに関する関数ファイルを読み込み
require_once MODEL_PATH . 'item.php';
// cartデータに関する関数ファイルを読み込み
require_once MODEL_PATH . 'cart.php';

// ログインチェックを行うため、セッションを開始する
session_start();

// ログインチェック用関数を利用
if(is_logined() === false){
  // ログインしていない場合はログインページにリダイレクト
  redirect_to(LOGIN_URL);
}

// PDOを取得
$db = get_db_connect();
// PDOを利用してログインユーザーのデータを取得
$user = get_login_user($db);

// 今までのトークンを削除するために、トークンを生成
$token = get_csrf_token();

// ユーザーの情報をカートテーブルとアイテムテーブルから取得する
$carts = get_user_carts($db, $user['user_id']);


// カートの中身を購入（不備がない場合）
if(purchase_carts($db, $carts, $user['user_id']) === false){
  // セッションにエラー文をセットする
  set_error('商品が購入できませんでした。');
  // カートページにリダイレクト
  redirect_to(CART_URL);
}

// カートの合計金額を代入
$total_price = sum_carts($carts);

// ビューの読み込み
include_once '../view/finish_view.php';