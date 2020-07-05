<?php
session_start();
require_once("./lib/util.php");
require_once("./database.php");
require_once("./account.php");
//エラーメッセージ破棄
$errormsg = "";
//アカウント情報破棄、会員ページで保存するかどうかの選択入れる？
//$_SESSION['account']= "";
if(isset($_SESSION['account'])){
$_SESSION['account']['email'] = es($_SESSION['account']['email']);
$_SESSION['account']['password'] = es($_SESSION['account']['password']);
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>酒場のすみっコぐらし</title>
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
            <h1><a href="frontPage.php">3channel</a></h1>
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
                <b>L<span>og</span>I<span>n</span></b>
            </h2>
            <?php if($login) : 
                header("Location: ./myPage.php"); ?>
            <?php else : ?>
            <?php if(!empty($_POST['flag']) && !empty($error)) :?>   
                <?php foreach( $error as $value ): ?>
            <li class="error"><?php echo $value; ?></li>
                <?php endforeach; ?>
            <?php endif; ?>
            <div>
            <!--   登録者確認-->
            <form method="post">
            <ul>
                <li class="login"><label>e-mail : <input type="email" name="email" value="" placeholder="<?php if(isset($_SESSION['account'])){echo $_SESSION['account']['email'];}?>"></label></li>
                <li class="login"><label>password : 
                <input type="text" name="password" value="" placeholder="<?php if(isset($_SESSION['account'])){echo $_SESSION['account']['password'];}?>"></label></li>
                <!--            hiddenでログインフラグをポスト   -->
                <label><input type="hidden" name="name" value="login"></label>
                <input type="hidden" name="flag" value="login">
                <li><input type="submit" class="button" value="ログイン"></li>
            </ul>
            </form>
            </div>
            <?php endif; ?>
            <div>
                <a class="logo-b" href = "newAccount.php"><b><span>新規登録</span></b></a>
                <!--    新規登録用
                <a href="newAccount.php">新規登録</a>
                -->
            </div>
            <div>
                <!-- 管理ページ  -->
                <a class="logo-b" href="admin.php"><b>管理者</b></a>
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
