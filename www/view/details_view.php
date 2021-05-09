<!DOCTYPE html>
<html lang="ja">
<head>
  <?php include VIEW_PATH . 'templates/head.php'; ?>
  <title>購入明細</title>
  <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'cart.css'); ?>">
</head>
<body>
  <?php include VIEW_PATH . 'templates/header_logined.php'; ?>
  <h1>購入明細</h1>
  <div class="container">

    <?php include VIEW_PATH . 'templates/messages.php'; ?>
    
    <table class="table table-bordered">
        <thead class="thead-light">
          <tr>
            <th>注文番号</th>
            <th>購入日時</th>
            <th>合計金額</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <!--商品名のタグインジェクションを防ぐためのhtmlエスケープ処理-->
            <td><?php print h($order_id); ?></td>
            <td><?php print h($purchased); ?></td>
            <td><?php print(number_format($total_price)); ?>円</td>
          </tr>
        </tbody>
    </table>
    <table class="table table-bordered">
        <thead class="thead-light">
          <tr>
            <th>商品名</th>
            <th>購入時の商品価格</th>
            <th>購入数</th>
            <th>小計</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($details as $value){ ?>
          <tr>
            <!--商品名のタグインジェクションを防ぐためのhtmlエスケープ処理-->
            <td><?php print h($value['name']); ?></td>
            <td><?php print(number_format($value['price'])); ?>円</td>
            <td><?php print(number_format($value['amount'])); ?></td>
            <td><?php print(number_format($value['subtotal'])); ?>円</td>
          </tr>
          <?php } ?>
        </tbody>
    </table>
  </div>
</body>
</html>