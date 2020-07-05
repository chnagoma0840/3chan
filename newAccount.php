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
if(isset($_POST['password'])){
    if($_POST['password'] != $_POST['password2']){
        $error[] = "パスワードが一致しません";
    }
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
                <div class="Confirmation">
                <!-- 新規登録フォームがtrueになった場合の確認画面  POST hidden set -->
                <?php if(empty($error) && isset($_POST)) :?>
                <ul>
                    <li>name : <?php echo $_POST['name'] ; ?></li>
                    <li>email : <?php echo $_POST['email'] ; ?></li>
                    <li>password : <?php echo $_POST['password'] ; ?></li>
                    <form method="post" action="newResult.php">
                        <input type="hidden" name="name" value="<?php echo $_POST['name']; ?>">
                        <input type="hidden" name="email" value="<?php echo $_POST['email']; ?>">
                        <input type="hidden" name="password" value="<?php echo $_POST['password']; ?>">
                        <input type="hidden" name="password2" value="<?php echo $_POST['password2']; ?>">
                        <input type="hidden" name="set" value="set">
                        <input type="submit" class="button" value="登録">
                        <input type="button" class="button" value="戻る" onclick="location.href='newAccount.php'">
                    </form>
                </ul>
                </div>
                <?php else :?>
                <div>
                <!-- 入力時のエラーメッセージ設定  -->
                <?php if(!empty($_POST['error']) && !empty($error)) :?>
                <?php foreach( $error as $value ): ?>
                    <li class="error"><?php echo $value; ?></li>
                <?php endforeach; ?>
                <?php endif; ?>
                <!--      不具合修正0619             -->
                    <?php if(!empty($_SESSION['error_message'])) :?>
                    <li class="error"><?php echo $_SESSION['error_message']; ?></li>
                        <?php unset( $_SESSION['error_message']); ?>
                        <?php endif; ?>
                    
                </div>
                <!-- 新規登録フォーム  POST hidden error -->
                <div>
                 <form method="post">
                     <ul>
                         <li><label>name : <input type ="text" name="name" placeholder="名前を入力してください"></label></li>
                         <li><label>e-mail : <input type ="email" name="email" placeholder="メールアドレスを入力してください"></label></li>
                         <li><label>password : <input type ="text" name="password" placeholder="パスワードを入力してください">
                        <li><label>password(確認用):<input type ="text" name="password2" placeholder="確認のためもう一度入力してください"></label></li>
                             <label><input type="hidden" name="error" value="error"></label>
                             <li><label><input type ="submit" class="button" value="登録"></label></li>
                             <li><input type="button"  class="button" value="戻る" onclick="location.href='frontPage.php'"></li>
                     </ul>
                 </form>
                </div>
                <?php endif ;?>
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