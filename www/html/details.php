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

// 今までのトークンを削除するために、トークンを生成
$token = get_csrf_token();

// 商品購入履歴の取得
$history = get_purchased_history($db, $user);

// 商品購入明細の取得
$details = get_purchased_details($db, $user, $history);

// ビューの読み込み。
include_once VIEW_PATH . 'details_view.php';