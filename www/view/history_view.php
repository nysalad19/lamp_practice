<!DOCTYPE html>
<html lang="ja">
<head>
  <?php include VIEW_PATH . 'templates/head.php'; ?>
  <title>購入履歴</title>
  <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'cart.css'); ?>">
</head>
<body>
  <?php include VIEW_PATH . 'templates/header_logined.php'; ?>
  <h1>購入履歴</h1>
  <div class="container">

    <?php include VIEW_PATH . 'templates/messages.php'; ?>

    <?php if(count($history) > 0){ ?>
      <table class="table table-bordered">
        <thead class="thead-light">
          <tr>
            <th>注文番号</th>
            <th>注文日時</th>
            <th>合計金額</th>
            <th>購入明細</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($history as $value){ ?>
          <tr>
            <!--商品名のタグインジェクションを防ぐためのhtmlエスケープ処理-->
            <td><?php print h($value['order_id']); ?></td>
            <td><?php print h($value['purchased']); ?></td>
            <td><?php print(number_format($value['total_price'])); ?>円</td>
            <td>
              <form method="post" action="details.php">
                <input type="submit" value="購入明細表示" class="btn btn-secondary">
                <input type="hidden" name="cart_id" value="<?php print($value['order_id']); ?>">
                <!--トークンの埋め込み-->
                <input type="hidden" name="token" value="<?php print $token; ?>">
              </form>
            </td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    <?php } else { ?>
      <p>購入履歴はありません。</p>
    <?php } ?> 
  </div>
</body>
</html>