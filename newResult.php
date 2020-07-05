<?php
session_start();
require_once("./lib/util.php");
require_once("./database.php");
require_once("./account.php");
if(!cken($_POST)){
    $encoding = mb_internal_encoding();
    $err = "Encoding Error! The expected encoding is" . $encoding;
    exit($err);
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>酒場の新規登録</title>
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
                <h1>3channel</h1>
                <nav>
                    <ul class="main-nav">
                        <li><a href="index.html">TOP</a></li>
                        <li><a href="admin.php">admin</a></li>
                        <li><a href="contact.php">Contact</a></li>
                        <li><a href="newAccount.php">sing up</a></li>
                        <li><a href="frontPage.php">Log In</a></li>
                    </ul>
                </nav>
            </header>
            <div class="wrapper">
                <h2 class="page-title logo">
                    <b>新規登録</b>
                </h2>
                <div>
                    <?php if(empty($_SESSION['error_message'])):?>
                    <p class="success_message">登録が完了しました！</p>
                    <input type="button" class="button" value="ログインページ" onclick="location.href='frontPage.php'">
                    <?php else : ?>
                    <!-- 入力時のエラーメッセージ設定  -->
                    <?php foreach( $_SESSION['error_message'] as $value ): ?>
                    <li class="error"><?php echo $value; ?></li>
                    <?php endforeach; ?>
                    <input type="button" class="button" value="戻る" onclick="location.href='newAccount.php'">
                    <?php  endif; ?>
                </div>
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