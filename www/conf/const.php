<?php
// 「model」ディレクトリまでのパスを定義
define('MODEL_PATH', $_SERVER['DOCUMENT_ROOT'] . '/lamp_practice/www/model/');
// 「view」ディレクトリまでのパスを定義
define('VIEW_PATH', $_SERVER['DOCUMENT_ROOT'] . '/lamp_practice/www/view/');

// 
define('IMAGE_PATH', '/lamp_practice/www/html/assets/images/');
// 
define('STYLESHEET_PATH', '/lamp_practice/www/html/assets/css/');
// 
define('IMAGE_DIR', $_SERVER['DOCUMENT_ROOT'] . '/lamp_practice/www/html/assets/images/' );

// 
define('DB_HOST', '127.0.0.1');
// 
define('DB_NAME', 'ec_site');
define('DB_USER', 'ec_site');
define('DB_PASS', 'ec_site');
define('DB_CHARSET', 'utf8');

define('SIGNUP_URL', '/lamp_practice/www/html/signup.php');
define('LOGIN_URL', '/lamp_practice/www/html/login.php');
define('LOGOUT_URL', '/lamp_practice/www/html/logout.php');
define('HOME_URL', '/lamp_practice/www/html/index.php');
define('CART_URL', '/lamp_practice/www/html/cart.php');
define('FINISH_URL', '/lamp_practice/www/html/finish.php');
define('ADMIN_URL', '/lamp_practice/www/html/admin.php');

// 正の整数と、小文字大文字のアルファベットの正規表現（数字かアルファベット1文字以上）
define('REGEXP_ALPHANUMERIC', '/\A[0-9a-zA-Z]+\z/');
// 正の整数の正規表現（1～9のいずれか1文字から始まる1桁以上の数字か、0）
define('REGEXP_POSITIVE_INTEGER', '/\A([1-9][0-9]*|0)\z/');


define('USER_NAME_LENGTH_MIN', 6);
define('USER_NAME_LENGTH_MAX', 100);
define('USER_PASSWORD_LENGTH_MIN', 6);
define('USER_PASSWORD_LENGTH_MAX', 100);

define('USER_TYPE_ADMIN', 1);
define('USER_TYPE_NORMAL', 2);

define('ITEM_NAME_LENGTH_MIN', 1);
define('ITEM_NAME_LENGTH_MAX', 100);

define('ITEM_STATUS_OPEN', 1);
define('ITEM_STATUS_CLOSE', 0);

const PERMITTED_ITEM_STATUSES = array(
  'open' => 1,
  'close' => 0,
);

// 画像タイプを配列に定義する
const PERMITTED_IMAGE_TYPES = array(
  IMAGETYPE_JPEG => 'jpg',
  IMAGETYPE_PNG => 'png',
);