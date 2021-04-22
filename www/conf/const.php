<?php
// modelディレクトリまでのパスを定義
define('MODEL_PATH', $_SERVER['DOCUMENT_ROOT'] . '/lamp_practice/www/model/');
// viewディレクトリまでのパスを定義
define('VIEW_PATH', $_SERVER['DOCUMENT_ROOT'] . '/lamp_practice/www/view/');

// WEBページを基準としたときの画像ディレクトリまでのパスを定義
define('IMAGE_PATH', '/lamp_practice/www/html/assets/images/');
// CSSディレクトリまでのパスを定義
define('STYLESHEET_PATH', '/lamp_practice/www/html/assets/css/');
// サーバー内の画像があるディレクトリまでのパスを定義
// 内部で画像を保存するときに参照
define('IMAGE_DIR', $_SERVER['DOCUMENT_ROOT'] . '/lamp_practice/www/html/assets/images/' );

// データベースに接続する情報
// ローカルホストを定義
define('DB_HOST', '127.0.0.1');
// データベース名を定義
define('DB_NAME', 'ec_site');
define('DB_USER', 'ec_site');
define('DB_PASS', 'ec_site');
define('DB_CHARSET', 'utf8');

// サインアップページのURLを定義
define('SIGNUP_URL', '/lamp_practice/www/html/signup.php');
// ログインページのURLを定義
define('LOGIN_URL', '/lamp_practice/www/html/login.php');
// ログアウトページのURLを定義
define('LOGOUT_URL', '/lamp_practice/www/html/logout.php');
// インデックスページのURLを定義
define('HOME_URL', '/lamp_practice/www/html/index.php');
// カートページのURLを定義
define('CART_URL', '/lamp_practice/www/html/cart.php');
// 購入完了ページのURLを定義
define('FINISH_URL', '/lamp_practice/www/html/finish.php');
// 管理ページのURLを定義
define('ADMIN_URL', '/lamp_practice/www/html/admin.php');

// 正の整数と、小文字大文字のアルファベットの正規表現（数字かアルファベット1文字以上）
define('REGEXP_ALPHANUMERIC', '/\A[0-9a-zA-Z]+\z/');
// 正の整数の正規表現（1～9のいずれか1文字から始まる1桁以上の数字か、0）
define('REGEXP_POSITIVE_INTEGER', '/\A([1-9][0-9]*|0)\z/');


// ユーザーネームの最小の長さを定義
define('USER_NAME_LENGTH_MIN', 6);
// ユーザーネームの最大の長さを定義
define('USER_NAME_LENGTH_MAX', 100);
// パスワードの最小の長さを定義
define('USER_PASSWORD_LENGTH_MIN', 6);
// パスワードの最大の長さを定義
define('USER_PASSWORD_LENGTH_MAX', 100);

// 管理ユーザーをタイプ1として定義
define('USER_TYPE_ADMIN', 1);
// 一般ユーザーをタイプ2として定義
define('USER_TYPE_NORMAL', 2);

// アイテム名の最小の長さを定義
define('ITEM_NAME_LENGTH_MIN', 1);
// アイテム名の最大の長さを定義
define('ITEM_NAME_LENGTH_MAX', 100);

// アイテムが公開されている時をステータス1として定義
define('ITEM_STATUS_OPEN', 1);
// アイテムが非公開の時をステータス2として定義
define('ITEM_STATUS_CLOSE', 0);

// アイテムの公開ステータスを配列に定義する
const PERMITTED_ITEM_STATUSES = array(
  'open' => 1,
  'close' => 0,
);

// 画像タイプを配列に定義する
const PERMITTED_IMAGE_TYPES = array(
  IMAGETYPE_JPEG => 'jpg',
  IMAGETYPE_PNG => 'png',
);