<?php 
// 汎用関数ファイルを読み込み
require_once MODEL_PATH . 'functions.php';
// データベース接続用のファイルを読み込み
require_once MODEL_PATH . 'db.php';

// 指定したカートのユーザーIDの情報を
// カートテーブルとアイテムテーブルから取得する
function get_user_carts($db, $user_id){
  $statement = $db->prepare(
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
  );
  // SQL文のプレースホルダに値をバインド
  $statement->bindParam(1, h($user_id), PDO::PARAM_INT);
  // 全レコードを取得して返す
  return fetch_all_query($db, $sql);
}

// ユーザーIDとアイテムIDを指定してアイテムテーブルと
// カートテーブルの情報を取得する
function get_user_cart($db, $user_id, $item_id){
  $statement = $db->prepare(
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
  "
  );
  // SQL文のプレースホルダに値をバインド
  $statement->bindParam(1, h($user_id), PDO::PARAM_INT);
  $statement->bindParam(2, h($item_id), PDO::PARAM_INT);
  // レコードを取得して返す
  return fetch_query($db, $sql);
}

function add_cart($db, $user_id, $item_id ) {
  $cart = get_user_cart($db, $user_id, $item_id);
  if($cart === false){
    return insert_cart($db, $user_id, $item_id);
  }
  return update_cart_amount($db, $cart['cart_id'], $cart['amount'] + 1);
}

// カートテーブルに、商品、ユーザー、数量を指定して行を追加
function insert_cart($db, $user_id, $item_id, $amount = 1){
  $statement = $db->prepare(
  $sql = "
    INSERT INTO
      carts(
        item_id,
        user_id,
        amount
      )
    VALUES(?, ?, ?)
  "
  );
  // SQL文のプレースホルダに値をバインド
  $statement->bindParam(1, h($item_id), PDO::PARAM_INT);
  $statement->bindParam(2, h($user_id), PDO::PARAM_INT);
  $statement->bindParam(3, h($amount), PDO::PARAM_INT);
  // SQLの実行を返す
  return execute_query($db, $sql);
}

// カートテーブルで、カートを指定して数量を更新
function update_cart_amount($db, $cart_id, $amount){
  $statement = $db->prepare(
  $sql = "
    UPDATE
      carts
    SET
      amount = ?
    WHERE
      cart_id = ?
    LIMIT 1
  "
  );
  // SQL文のプレースホルダに値をバインド
  $statement->bindParam(1, h($amount), PDO::PARAM_INT);
  $statement->bindParam(2, h($cart_id), PDO::PARAM_INT);
  // SQLの実行を返す
  return execute_query($db, $sql);
}

// カートテーブルから、カートを指定して行を削除
function delete_cart($db, $cart_id){
  $statement = $db->prepare(
  $sql = "
    DELETE FROM
      carts
    WHERE
      cart_id = ?
    LIMIT 1 
  "
  );
  // SQL文のプレースホルダに値をバインド
  $statement->bindParam(1, h($cart_id), PDO::PARAM_INT);
  // SQLの実行を返す
  return execute_query($db, $sql);
}

function purchase_carts($db, $carts){
  if(validate_cart_purchase($carts) === false){
    return false;
  }
  foreach($carts as $cart){
    if(update_item_stock(
        $db, 
        $cart['item_id'], 
        $cart['stock'] - $cart['amount']
      ) === false){
      set_error($cart['name'] . 'の購入に失敗しました。');
    }
  }
  
  delete_user_carts($db, $carts[0]['user_id']);
}

function delete_user_carts($db, $user_id){
  $statement = $db->prepare(
  $sql = "
    DELETE FROM
      carts
    WHERE
      user_id = ?
  "
  );
  // SQL文のプレースホルダに値をバインド
  $statement->bindParam(1, h($user_id), PDO::PARAM_INT);
  // SQLの実行を返す
  return execute_query($db, $sql);
}

function sum_carts($carts){
  $total_price = 0;
  foreach($carts as $cart){
    $total_price += $cart['price'] * $cart['amount'];
  }
  return $total_price;
}

function validate_cart_purchase($carts){
  // 変数に値が入っていない場合
  if(count($carts) === 0){
    // セッションにエラー文を追加する
    set_error('カートに商品が入っていません。');
    // falseを返す
    return false;
  }
  // 変数に入っている値を繰り返し取り出す
  foreach($carts as $cart){
    if(is_open($cart) === false){
      set_error($cart['name'] . 'は現在購入できません。');
    }
    if($cart['stock'] - $cart['amount'] < 0){
      set_error($cart['name'] . 'は在庫が足りません。購入可能数:' . $cart['stock']);
    }
  }
  if(has_error() === true){
    return false;
  }
  return true;
}

