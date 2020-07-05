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
if(isset($_SESSION['id']) && empty($_SESSION['account'])){
    $account=idCheck($_SESSION['id']);
    $_SESSION['account'] = $account;
    
}


?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>酒場の登録変更</title>
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
                    <li><a href="myPage.php">My page</a></li>
                    <li><a href="logout.php">Log Out</a></li>
                </ul>
            </nav>
        </header>
        <div class="wrapper">
            <h2 class="page-title logo">
                <b><?php echo $_SESSION['account']['name'] ;?>の変更確認</b>
            </h2>
            <div>
                <?php if(count($error_message)<=0 ): ?>
                <p class="success_message"><?php echo $success_message; ?></p>
                <!--    投稿時のエラーメッセージ-->
                <?php else: ?>
                <ul class="error_message">
                    <?php foreach( $error_message as $value ): ?>
                    <li class="error"><?php echo $value; ?></li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>
                <input type="button" class="button" value="戻る" onclick="location.href='myPage.php'">
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