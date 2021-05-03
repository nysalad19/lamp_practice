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

// ログインしていない場合、ログインページまでリダイレクト
// （セッションにユーザーIDが入っていない場合）
if(is_logined() === false){
  // ログインページまでリダイレクト
  redirect_to(LOGIN_URL);
}

// PDOを取得
$db = get_db_connect();

// ログインユーザーの情報を代入
$user = get_login_user($db);

if(is_admin($user) === false){
  redirect_to(LOGIN_URL);
}

$items = get_all_items($db);

// トークンを生成
$token = get_csrf_token();

// ビューの読み込み
include_once VIEW_PATH . 'admin_view.php';