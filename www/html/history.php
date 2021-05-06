<?php
// 不要なファイルは随時削除
// 定数ファイルを読み込み
require_once '../conf/const.php';
// 汎用関数ファイルを読み込み
require_once MODEL_PATH . 'functions.php';
// userデータに関する関数ファイルを読み込み
require_once MODEL_PATH . 'user.php';
// itemデータに関する関数ファイルを読み込み。
require_once MODEL_PATH . 'item.php';

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
// ビューを作って、一旦↑の$historyの中身が何か確認する
// var_dump($history);
// で













// ビュー作成したらコメントアウト解除
// ビューの読み込み。
// include_once VIEW_PATH . 'history_view.php';





// 参考用
// 適宜使用、後に削除
// ログインチェック用関数を利用
if(is_logined() === false){
  // ログインしていない場合はログインページにリダイレクト
  redirect_to(LOGIN_URL);
}

// トークンを生成
$token = get_csrf_token();

// PDOを取得
$db = get_db_connect();
// PDOを利用してログインユーザーのデータを取得
$user = get_login_user($db);

// 商品一覧用の商品データを取得
$items = get_open_items($db);

