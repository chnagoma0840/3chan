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
    if($account=idCheck($_SESSION['id'])){
        $_SESSION['account'] = $account;
    }
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
                <b><?php echo $_SESSION['account']['name'] ;?>の登録変更</b>
            </h2>
            <div class="Confirmation">
                <?php if(empty($error) && isset($_POST)) :?>
                <ul>
                    <li>name : <?php echo $_POST['name'] ; ?></li>
                    <li>email : <?php echo $_POST['email'] ; ?></li>
                    <li>password : <?php echo $_POST['password'] ; ?></li>
                    <form method="post" action="updPage.php">
                        <input type="hidden" name="name" value="<?php echo $_POST['name']; ?>">
                        <input type="hidden" name="email" value="<?php echo $_POST['email']; ?>">
                        <input type="hidden" name="password" value="<?php echo $_POST['password']; ?>">
                        <input type="hidden" name="id" value="<?php echo $_POST['id']; ?>">
                        <input type="hidden" name="upd" value="upd">
                        <input type="submit" class="button" value="変更">
                        <input type="button" class="button" value="戻る" onclick="location.href='updAccount.php'">
                    </form>
                </ul>
            </div>
                <?php else :?>
                <!-- 入力時のエラーメッセージ設定  -->
            <div>
                <ul>
                    <?php if(!empty($_POST['error']) && !empty($error)) :?>
                    <?php foreach( $error as $value ): ?>
                    <li class="error"><?php echo $value; ?></li>
                    <?php endforeach; ?>
                    <?php endif; ?>
                    <!--        不具合修正0619            -->
                    <?php if(!empty( $_SESSION['error_message'])) :?>
                    <li class="error"><?php echo $_SESSION['error_message']; ?></li>
                       <?php unset( $_SESSION['error_message']);?>
                        <?php endif; ?>
                </ul>
            </div>
               <div>
               <p>変更内容を入力してください</p>
                <form method="post">
                <ul>
                    <li><label>name:<input type ="text" name="name" value="<?php echo $_SESSION['account']['name'];?>"placeholder="<?php if(isset($_SESSION['account'])){echo $_SESSION['account']['name'];}?>"></label></li>
                    <li><label>e-mail:<input type ="email" name="email"value="<?php echo $_SESSION['account']['email'];?>"placeholder="<?php if(isset($_SESSION['account'])){echo $_SESSION['account']['email'];}?>"></label></li>
                    <li><label>password:<input type ="text" name="password" value="<?php echo $_SESSION['account']['password'];?>"placeholder="<?php if(isset($_SESSION['account'])){echo $_SESSION['account']['password'];}?>">
                        <label><input type="hidden" name="id" value="<?php echo $_SESSION['account']['id']; ?>"></label>
                        <label><input type="hidden" name="error" value="error"></label>
                        <li><input type ="submit" class="button" value="変更"></li>
                        
                 </ul>
                </form>
                </div>
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