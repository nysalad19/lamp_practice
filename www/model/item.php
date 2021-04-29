<?php
// 汎用関数ファイルを読み込み
require_once MODEL_PATH . 'functions.php';
// データベース接続用のファイルを読み込み
require_once MODEL_PATH . 'db.php';

// DB利用

function get_item($db, $item_id){
  $sql = "
    SELECT
      item_id, 
      name,
      stock,
      price,
      image,
      status
    FROM
      items
    WHERE
      item_id = ?
  ";
  // レコードを取得する。出来なかった場合、falseを返す。
  // 値をバインドしながら実行
  return fetch_query($db, $sql, array($item_id));
}

function get_items($db, $is_open = false){
  $sql = '
    SELECT
      item_id, 
      name,
      stock,
      price,
      image,
      status
    FROM
      items
  ';
  if($is_open === true){
    $sql .= '
      WHERE status = 1
    ';
  }

  // 全レコードを取得する。出来なかった場合、falseを返す。
  return fetch_all_query($db, $sql);
}

function get_all_items($db){
  return get_items($db);
}

function get_open_items($db){
  return get_items($db, true);
}

function regist_item($db, $name, $price, $stock, $status, $image){
  $filename = get_upload_filename($image);
  if(validate_item($name, $price, $stock, $filename, $status) === false){
    return false;
  }
  return regist_item_transaction($db, $name, $price, $stock, $status, $image, $filename);
}

function regist_item_transaction($db, $name, $price, $stock, $status, $image, $filename){
  $db->beginTransaction();
  if(insert_item($db, $name, $price, $stock, $filename, $status) 
    && save_image($image, $filename)){
    $db->commit();
    return true;
  }
  $db->rollback();
  return false;
  
}

function insert_item($db, $name, $price, $stock, $filename, $status){
  $status_value = PERMITTED_ITEM_STATUSES[$status];
  $sql = "
    INSERT INTO
      items(
        name,
        price,
        stock,
        image,
        status
      )
    VALUES(?, ?, ?, ?, ?);
  ";

  // SQL文を実行する。出来なかった場合、falseを返す。
  // 値をバインドしながら実行
  return execute_query($db, $sql, array($name, $price, $stock, $filename, $status_value));
}

function update_item_status($db, $item_id, $status){
  $sql = "
    UPDATE
      items
    SET
      status = ?
    WHERE
      item_id = ?
    LIMIT 1
  ";
  
  // SQL文を実行する。出来なかった場合、falseを返す。
  // 値をバインドしながら実行
  return execute_query($db, $sql, array($status, $item_id));
}

function update_item_stock($db, $item_id, $stock){
  $sql = "
    UPDATE
      items
    SET
      stock = ?
    WHERE
      item_id = ?
    LIMIT 1
  ";
  
  // SQL文を実行する。出来なかった場合、falseを返す。
  // 値をバインドしながら実行
  return execute_query($db, $sql, array($stock, $item_id));
}

function destroy_item($db, $item_id){
  $item = get_item($db, $item_id);
  if($item === false){
    return false;
  }
  $db->beginTransaction();
  if(delete_item($db, $item['item_id'])
    && delete_image($item['image'])){
    $db->commit();
    return true;
  }
  $db->rollback();
  return false;
}

function delete_item($db, $item_id){
  $sql = "
    DELETE FROM
      items
    WHERE
      item_id = ?
    LIMIT 1
  ";
  
  // SQL文を実行する。出来なかった場合、falseを返す。
  // 値をバインドしながら実行
  return execute_query($db, $sql, array($item_id));
}


// 非DB

// 商品が公開されているかを判定
function is_open($item){
  // 商品のステータスが1の時falseを返す
  return $item['status'] === 1;
}

function validate_item($name, $price, $stock, $filename, $status){
  $is_valid_item_name = is_valid_item_name($name);
  $is_valid_item_price = is_valid_item_price($price);
  $is_valid_item_stock = is_valid_item_stock($stock);
  $is_valid_item_filename = is_valid_item_filename($filename);
  $is_valid_item_status = is_valid_item_status($status);

  return $is_valid_item_name
    && $is_valid_item_price
    && $is_valid_item_stock
    && $is_valid_item_filename
    && $is_valid_item_status;
}

function is_valid_item_name($name){
  // $is_valid変数にtrueを代入
  $is_valid = true;
  if(is_valid_length($name, ITEM_NAME_LENGTH_MIN, ITEM_NAME_LENGTH_MAX) === false){
    set_error('商品名は'. ITEM_NAME_LENGTH_MIN . '文字以上、' . ITEM_NAME_LENGTH_MAX . '文字以内にしてください。');
    $is_valid = false;
  }
  // $is_valid変数を返す
  return $is_valid;
}

function is_valid_item_price($price){
  $is_valid = true;
  if(is_positive_integer($price) === false){
    set_error('価格は0以上の整数で入力してください。');
    $is_valid = false;
  }
  // $is_valid変数を返す
  return $is_valid;
}

function is_valid_item_stock($stock){
  // $is_valid変数にtrueを代入
  $is_valid = true;
  if(is_positive_integer($stock) === false){
    set_error('在庫数は0以上の整数で入力してください。');
    $is_valid = false;
  }
  // $is_valid変数を返す
  return $is_valid;
}

// $filename変数に値があるかどうか判定（ある：true　ブランク：false）
function is_valid_item_filename($filename){
  // $is_valid変数にtrueを代入
  $is_valid = true;
  // $failename変数の値がブランクの場合
  if($filename === ''){
    // $is_valid変数にfalseを代入
    $is_valid = false;
  }
  // $is_valid変数を返す
  return $is_valid;
}

// $status変数がopne又はcloseかどうかを判定（trueかfalseを返す）
function is_valid_item_status($status){
  // $is_valid変数にtrueを代入
  $is_valid = true;
  // $statusの値がアイテム公開ステータス配列のキー名に存在しない場合
  if( array_key_exists($status,PERMITTED_ITEM_STATUSES) === false){
    // $is_valid変数にfalseを代入
    $is_valid = false;
  }
  // $is_valid変数を返す
  return $is_valid;
}