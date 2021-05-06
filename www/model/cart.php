<?php 
// 汎用関数ファイルを読み込み
require_once MODEL_PATH . 'functions.php';
// データベース接続用のファイルを読み込み
require_once MODEL_PATH . 'db.php';

// ユーザーの情報をカートテーブルとアイテムテーブルから取得する
function get_user_carts($db, $user_id){
  $sql = "
    SELECT
      items.item_id,
      items.name,
      items.price,
      items.stock,
      items.status,
      items.image,
      carts.cart_id,
      carts.user_id,
      carts.amount
    FROM
      carts
    JOIN
      items
    ON
      carts.item_id = items.item_id
    WHERE
      carts.user_id = ?
  "
  ;
  // 全レコードを取得して返す、取得できなかった場合falseを返す
  // 値をバインドしながら実行
  return fetch_all_query($db, $sql, array($user_id));
}

// ユーザーIDとアイテムIDを指定してアイテムテーブルと
// カートテーブルの情報を取得する
function get_user_cart($db, $user_id, $item_id){
  $sql = "
    SELECT
      items.item_id,
      items.name,
      items.price,
      items.stock,
      items.status,
      items.image,
      carts.cart_id,
      carts.user_id,
      carts.amount
    FROM
      carts
    JOIN
      items
    ON
      carts.item_id = items.item_id
    WHERE
      carts.user_id = ?
    AND
      items.item_id = ?
  ";
  // レコードを取得して返す
  // 値をバインドしながら実行
  return fetch_query($db, $sql, array($user_id, $item_id));
}

// カートに追加（カートテーブルに新規で行を追加するか、数量を更新）
function add_cart($db, $user_id, $item_id ) {
  // $cart変数に指定のユーザーの商品情報を代入する
  $cart = get_user_cart($db, $user_id, $item_id);
  // 情報が取得できなかった場合（$cart変数にfalseが入った場合）
  if($cart === false){
    // カートテーブルに、商品、ユーザー、数量を指定して行を追加
    return insert_cart($db, $user_id, $item_id);
  }
  // カートテーブルで、カートを指定して数量を更新
  return update_cart_amount($db, $cart['cart_id'], $cart['amount'] + 1);
}

// カートテーブルに、商品、ユーザー、数量を指定して行を追加
function insert_cart($db, $user_id, $item_id, $amount = 1){
  $sql = "
    INSERT INTO
      carts(
        item_id,
        user_id,
        amount
      )
    VALUES(?, ?, ?)
  ";
  // SQLの実行を返す
  // 値をバインドしながら実行
  return execute_query($db, $sql, array($item_id, $user_id, $amount));
}

// カートテーブルで、カートを指定して数量を更新
function update_cart_amount($db, $cart_id, $amount){
  $sql = "
    UPDATE
      carts
    SET
      amount = ?
    WHERE
      cart_id = ?
    LIMIT 1
  ";
  // SQLの実行を返す
  // 値をバインドしながら実行
  return execute_query($db, $sql, array($amount, $cart_id));
}

// カートテーブルから、カートを指定して行を削除
function delete_cart($db, $cart_id){
  $sql = "
    DELETE FROM
      carts
    WHERE
      cart_id = ?
    LIMIT 1 
  ";
  // SQLの実行を返す
  // 値をバインドしながら実行
  return execute_query($db, $sql, array($cart_id));
}

// カートの中身を購入、購入履歴テーブルと購入明細テーブルにデータ作成、カート情報削除
// 不備がある場合、falseを返す
function purchase_carts($db, $carts, $user_id){
  // トランザクション開始
  $db->beginTransaction();
  
  // 購入履歴テーブルにデータを作成（カートの中身に不備がない場合）
  if(validate_cart_purchase($carts) === false) {
  // falseを返す
  return false;
  } else {
    $sql = "
    INSERT INTO history
      (purchased, user_id)
    VALUES
      (NOW(), ?)
    ";
    // SQLの実行
    // 値をバインドしながら実行
    execute_query($db, $sql, array($user_id));
  }
  
  //order_idを取得
  $order_id = $db->lastInsertId();
  
  // 購入履歴テーブルにデータを作成
  foreach($carts as $cart){
    $sql = "
    INSERT INTO details
      (order_id, item_id,	price, amount)
    VALUES
      ($order_id, ?, ?, ?)
    ";
    // SQLの実行
    // 値をバインドしながら実行
    execute_query($db, $sql, array($cart['item_id'], $cart['price'], $cart['amount']));
  }
  
  // 商品の在庫数更新
  if(update_item_stock(
      $db, 
      $cart['item_id'], 
      $cart['stock'] - $cart['amount']
    // 購入数が在庫数を上回る場合
    ) === false){
    // セッションにエラー文をセットする
    set_error($cart['name'] . 'の購入に失敗しました。');
    }
    
  // 上記の処理中にエラーがない場合
  if(count(get_errors()) === 0) {
    // コミット処理
    $db->commit();
    
    // カート情報を消す
    delete_user_carts($db, $carts[0]['user_id']);
  } else {
    // ロールバック処理
    $db->rollback();
    // falseを返す
    return false;
  }
}

// 指定したユーザーのcartsテーブルの情報を消す
function delete_user_carts($db, $user_id){
  $sql = "
    DELETE FROM
      carts
    WHERE
      user_id = ?
  "
  ;
  // SQLの実行を返す
  // 値をバインドしながら実行
  return execute_query($db, $sql, array($user_id));
}

// カートの合計金額を返す
function sum_carts($carts){
  // 変数に0を代入する
  $total_price = 0;
  // 変数に入っている値を繰り返し取り出す
  foreach($carts as $cart){
    // $total_price変数に、合計金額(price*amount)を足していく
    $total_price += $cart['price'] * $cart['amount'];
  }
  // 合計金額を返す
  return $total_price;
}

// カートの中身を検証する
// カートの中身がない、商品が公開されていない、在庫数が足りない、エラー文がセットされているときはfalse
// それ以外のときはtrueを返す
function validate_cart_purchase($carts){
  // $cart変数に値が入っていない場合
  if(count($carts) === 0){
    // セッションにエラー文を追加する
    set_error('カートに商品が入っていません。');
    // falseを返す
    return false;
  }
  // 変数に入っている値を繰り返し取り出す
  foreach($carts as $cart){
    // 商品が公開されていない場合
    if(is_open($cart) === false){
      // セッションにエラー文を追加する
      set_error($cart['name'] . 'は現在購入できません。');
    }
    // 変数の「stock」キーの値－「amount」キーの値がマイナスになる場合
    if($cart['stock'] - $cart['amount'] < 0){
      // セッションにエラー文を追加する
      set_error($cart['name'] . 'は在庫が足りません。購入可能数:' . $cart['stock']);
    }
  }
  // エラー文がある場合
  if(has_error() === true){
    // falseを返す
    return false;
  }
  // trueを返す
  return true;
}