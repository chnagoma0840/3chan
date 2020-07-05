<?php
session_start();
$_SESSION = array();
if (isset($_COOKIE["PHPSESSID"])) {
    setcookie("PHPSESSID", '', time() - 1800, '/');
}
session_destroy();


require_once("./lib/util.php");
//エラーメッセージ破棄
$errormsg = "";
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>酒場のログアウト</title>
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
                        <li><a href="admin.php">admin</a></li>
                        <li><a href="contact.php">Contact</a></li>
                        <li><a href="newAccount.php">sing up</a></li>
                        <li><a href="frontPage.php">Log In</a></li>
                    </ul>
                </nav>
            </header>
            <div class="wrapper">
                <h2 class="page-title logo"><b>Log Out</b>
                </h2>
                <div>
                    <span><input type="button"class="button" value="TOPへ" onclick="location.href='index.html'"></span>
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

