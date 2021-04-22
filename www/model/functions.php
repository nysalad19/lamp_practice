<?php

// 変数の詳細を表示する
function dd($var){
  var_dump($var);
  exit();
}

// 変数のURLまでリダイレクトさせる
function redirect_to($url){
  header('Location: ' . $url);
  exit;
}

// GETリクエストで送られてきた情報を返す。無い場合はブランクを返す。
function get_get($name){
  if(isset($_GET[$name]) === true){
    return $_GET[$name];
  };
  return '';
}

// POSTリクエストで送られてきた情報を返す。無い場合はブランクを返す。
function get_post($name){
  if(isset($_POST[$name]) === true){
    return $_POST[$name];
  };
  return '';
}

// POSTメソッドで送られてきたファイル情報を返す。無い場合は空の配列を返す。
function get_file($name){
  if(isset($_FILES[$name]) === true){
    return $_FILES[$name];
  };
  return array();
}

// セッションに入っている情報を返す。無い場合はブランクを返す。
function get_session($name){
  if(isset($_SESSION[$name]) === true){
    return $_SESSION[$name];
  };
  return '';
}

// セッションの変数名と値を設定する。
function set_session($name, $value){
  $_SESSION[$name] = $value;
}

// セッションの二次元配列に要素を追加する。
function set_error($error){
  $_SESSION['__errors'][] = $error;
}

// セッションに入っているエラー情報を返し、セッションのエラー情報を空にする
function get_errors(){
  // 「$error」変数にセッションの「__errors」キーに入っている情報を代入する
  $errors = get_session('__errors');
  // 「__errors」キーの中身がブランクの場合
  if($errors === ''){
    // 空の配列を返す
    return array();
  }
  // セッションの「__errors」キーの値に空の配列を指定する
  set_session('__errors',  array());
  // セッションの「__errors」キーに入っている情報を返す
  return $errors;
}

// エラーがあるかどうかをブーリアン値で返す
function has_error(){
  // エラー配列が0でない
  return isset($_SESSION['__errors']) && count($_SESSION['__errors']) !== 0;
}

// セッションのメッセージ配列に要素を追加する。
function set_message($message){
  $_SESSION['__messages'][] = $message;
}

// セッションに入っているメッセージ情報を返し、セッションのメッセージ情報を空にする
function get_messages(){
  // $messages変数にセッションの「__messages」変数に入っている情報を代入
  $messages = get_session('__messages');
  // もしセッションの「__messages」変数が空の場合
  if($messages === ''){
    // 空の配列を返す
    return array();
  }
  // セッションの「__errors」キーの値に空の配列を指定する
  set_session('__messages',  array());
  // セッションの「__errors」キーに入っている情報を返す
  return $messages;
}

// セッションの「user_id」変数をブランク以外で返す
function is_logined(){
  return get_session('user_id') !== '';
}

function get_upload_filename($file){
  if(is_valid_upload_image($file) === false){
    return '';
  }
  // 画像のタイプを$mimetype変数に代入
  $mimetype = exif_imagetype($file['tmp_name']);
  $ext = PERMITTED_IMAGE_TYPES[$mimetype];
  return get_random_string() . '.' . $ext;
}

// ランダムな文字列を先頭から20文字を返す
// base_convert：数値の基数を任意に変換する
// substr：文字の一部を返す
// hash：ハッシュ値を生成する
function get_random_string($length = 20){
  return substr(base_convert(hash('sha256', uniqid()), 16, 36), 0, $length);
}

// アップロードされた画像を一時フォルダから画像ディレクトリに移動させる
function save_image($image, $filename){
  return move_uploaded_file($image['tmp_name'], IMAGE_DIR . $filename);
}

function delete_image($filename){
  if(file_exists(IMAGE_DIR . $filename) === true){
    unlink(IMAGE_DIR . $filename);
    return true;
  }
  return false;
  
}



function is_valid_length($string, $minimum_length, $maximum_length = PHP_INT_MAX){
  $length = mb_strlen($string);
  return ($minimum_length <= $length) && ($length <= $maximum_length);
}

// 変数が正の整数かアルファベット1文字以上の場合、返す
function is_alphanumeric($string){
  return is_valid_format($string, REGEXP_ALPHANUMERIC);
}

// 変数が正の整数の場合、返す
function is_positive_integer($string){
  return is_valid_format($string, REGEXP_POSITIVE_INTEGER);
}

// 文字列と正規表現を指定して、マッチングした場合文字列を返す
function is_valid_format($string, $format){
  return preg_match($format, $string) === 1;
}

// 画像が指定の拡張子でアップロードされたかをブーリアン値で返す。
function is_valid_upload_image($image){
  // POSTメソッドでアップロードされたファイルか調べる
  if(is_uploaded_file($image['tmp_name']) === false){
    set_error('ファイル形式が不正です。');
    return false;
  }
  // 画像のタイプを$mimetype変数に代入
  $mimetype = exif_imagetype($image['tmp_name']);
  // $mimetypeに代入された画像タイプ（キー名）が、画像タイプを定義した配列の中にない場合、
  if( array_key_exists($mimetype, PERMITTED_IMAGE_TYPES) === false ){
    // implode：配列を文字列に変換
    set_error('ファイル形式は' . implode('、', PERMITTED_IMAGE_TYPES) . 'のみ利用可能です。');
    return false;
  }
  return true;
}

// 変数をHTMLエスケープして返す
function h($string) {
  return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}