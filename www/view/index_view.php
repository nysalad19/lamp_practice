<!DOCTYPE html>
<html lang="ja">
<head>
  <!--文字コード指定や、フレームワークの読み込み-->
  <?php include VIEW_PATH . 'templates/head.php'; ?>
  
  <title>商品一覧</title>
  <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'index.css'); ?>">
</head>
<body>
  <!--ログイン後用ヘッダーを表示-->
  <?php include VIEW_PATH . 'templates/header_logined.php'; ?>
  
  <div class="text-right">
    <form action="index.php" method="get" id="order_form">
      <select name="order" id="order">
          <option value="new">新着順</option>
          <option value="low">価格の安い順</option>
          <option value="high">価格の高い順</option>
      </select>
      <input type="submit" value="並び替え" class="btn btn-primary">
    </form>
  </div>
  
  <div class="container">
    <h1>商品一覧</h1>
    <!--メッセージ表示（エラー文含む）-->
    <?php include VIEW_PATH . 'templates/messages.php'; ?>

    <div class="card-deck">
      <div class="row">
      <?php foreach($items as $item){ ?>
        <div class="col-6 item">
          <div class="card h-100 text-center">
            <div class="card-header">
              <!--商品名のタグインジェクションを防ぐためのhtmlエスケープ処理-->
              <?php print h($item['name']); ?>
            </div>
            <figure class="card-body">
              <!--商品画像-->
              <img class="card-img" src="<?php print(IMAGE_PATH . $item['image']); ?>">
              <figcaption>
                <?php print(number_format($item['price'])); ?>円
                <?php if($item['stock'] > 0){ ?>
                  <!--カート追加処理ページに送信-->
                  <form action="index_add_cart.php" method="post">
                    <input type="submit" value="カートに追加" class="btn btn-primary btn-block">
                    <input type="hidden" name="item_id" value="<?php print($item['item_id']); ?>">
                    <!--トークンの埋め込み-->
                    <input type="hidden" name="token" value="<?php print $token; ?>">
                  </form>
                <?php } else { ?>
                  <p class="text-danger">現在売り切れです。</p>
                <?php } ?>
              </figcaption>
            </figure>
          </div>
        </div>
      <?php } ?>
      </div>
    </div>
  </div>
  <script>
    const order = document.getElementById('order');
    const order_form = document.getElementById('order_form');
    
    // valueの変化があった時に
    order.addEventListener('change', (event) => {
      order_form.submit();
    });
  </script>
</body>
</html>