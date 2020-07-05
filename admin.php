<?php
session_start();
require_once("./lib/util.php");
require_once("./database.php");
require_once("./account.php");
//messageへのファイルパス情報

define( 'PASSWORD', '007731584');
if(isset($_POST)){
    $_POST = es($_POST);
}

// タイムゾーン設定
date_default_timezone_set('Asia/Tokyo');
if(!cken($_POST)){
    $encoding = mb_internal_encoding();
    $err = "Encoding Error! The expected encoding is" . $encoding;
    exit($err);
}


//メッセージ投稿時のエラーチェック
if( !empty($_POST['btn']) ) {
    // 表示名の入力チェック
    if( !empty($_POST['admin_password']) && $_POST['admin_password'] === PASSWORD ) {
        $_SESSION['admin_login'] = true;
    } else {
        $error_message[] = 'ログインに失敗しました。';
    }
}

//メッセージデータベースから取得
// データベースの接続情報

$user = 'ashibe';
$password = '007731584';
$dbname = '3chan';
$host = 'localhost';
$dsn = "mysql:host={$host};dbname={$dbname};charset=utf8";
try{
    $pdo =new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //登録されたメッセージをすべて取得
    $sql = "SELECT * FROM user_msg INNER JOIN user ON user_msg.view_id = user.u_id ORDER BY date_time DESC";
    //カラム毎に配列へ格納
    $row=$pdo->query($sql)->fetchAll();
} catch(Exception $e){
    echo '<span class="error">エラーがありました</span><br>';
    echo '<span class="error">登録情報を取得できませんでした</span><br>';
    //データ確認用
    echo $e->getMessage();
    exit;
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>酒場の管理者</title>
    <link href="./jquery/js/vegas/vegas.css" rel="stylesheet"/>
    <link href="./css/style.css" rel="stylesheet">
    <link href="./css/style4.css" rel="stylesheet">
    <script src="./jquery/js/jquery-3.4.1.min.js"></script> <!--jQueryファイル-->
    <script src="./jquery/js/vegas/vegas.min.js"></script>
    <script src="./jquery/js/script.js"></script><!--Vegas2の設定用のjsファイル -->
</head>
<body>
    <div id="vegas">
        <div id="contact">
            <header class="page-header wrapper">
                <h1>管理者</h1>
                <nav>
                    <ul class="main-nav">
                        <li><a href="index.html">TOP</a></li>
                        <li><a href="admin.php">admin</a></li>
                        <li><a href="frontPage.php">Log In</a></li>
                    </ul>
                </nav>
            </header>
            <div class="wrapper">
                <h2 class="page-title logo">
                    <b>「S」akaba no okuba</b>
                </h2>
            <?php if( !empty($_SESSION['admin_login']) && $_SESSION['admin_login'] == true ): ?>
            <div class="dl">
                <form method="get" action="./download.php">
                    <select name="limit" class="button">
                        <option value="">全て</option>
                        <option value="10">10件</option>
                        <option value="30">30件</option>
                    </select>
                    <input type="submit"  class="button" name="btn_download" value="ダウンロード">
                </form>
                
            </div>
            <section>
            <?php if( !empty($row) ): ?>
                <?php foreach( $row as $value ): ?>
                <article>
                    <div class="info">
                        <h2 class="name"><?php echo $value['u_name']; ?></h2>
                        <time><?php echo date('Y年m月d日 H:i', strtotime($value['date_time'])); ?></time>
                    
                    <p class="edit"><a href="edit.php?message_id=<?php echo $value['id']; ?>">編集</a>  <a href="delete.php?message_id=<?php echo $value['id']; ?>">削除</a></p>
                    </div>
                    <hr>
                    <div class=msgfram>
                    <p class=message><?php echo nl2br($value['view_message']); ?></p>
                    </div>
                </article>
                <?php endforeach; ?>
            <?php endif; ?>
            </section>
        <?php else: ?>
            <section>  
                <?php if( !empty($error_message) ): ?>
                    <ul class="error_message">
                        <?php foreach( $error_message as $value ): ?>
                        <li><?php echo $value; ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
                <form method="post">
                    <div>
                        <label for="admin_password">Enter Password</label>
                        <input id="admin_password" type="password" name="admin_password" value="">
                    </div>
                    <input type="submit"  class="button" name="btn" value="ログイン">
                    <input type="button"class="button"  value="戻る" onclick="location.href='frontPage.php'">
                </form>
            </section>
        <?php endif; ?>
        </div>
    </div>
</div>
    <footer>
        <small>
            <p class="footer-fm">2020&copy;Copyright chanGOMA Corporation.All Rights Reserved.</p>
        </small>
    </footer>

</body>
</html>