<?php
session_start();
require_once("./lib/util.php");
require_once("./database.php");
require_once("./account.php");
define('DISP_MAX',  15); 
// 1ページの最大表示数
if(!isset($_GET['page_id'])){ // $_GET['page_id'] はURLに渡された現在のページ数
    $page = 1; // 設定されてない場合は1ページ目にする
}else{
    $page = $_GET['page_id'];
}
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
if(isset($_SESSION['account'])){
    $errormsg = "";
}
if(isset($_SESSION['id']) && empty($_SESSION['account'])){
    if($account=idCheck($_SESSION['id'])){
        $_SESSION['account'] = $account;
    }
}

//メッセージ投稿時のエラーチェック
if( !empty($_POST['btn']) ) {
    // 表示名の入力チェック
    if( empty($_POST['u_name']) ) {
        $error_message[] = '表示名を入力してください。';
    }
    // メッセージの入力チェック
    if( empty($_POST['view_message']) ) {
        $error_message[] = 'ひと言メッセージを入力してください。';
    }
}

//メッセージデータベースから取得

// 掲示板情報表示

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
    $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM user_msg INNER JOIN user ON user_msg.view_id = user.u_id ORDER BY date_time DESC LIMIT ?, ?";
    $stm = $pdo->prepare($sql);
    $stm->bindValue(1, ($page - 1) * DISP_MAX, PDO::PARAM_INT);
    $stm->bindValue(2, DISP_MAX, PDO::PARAM_INT);
    $stm->execute();
    $row = $stm->fetchAll(PDO::FETCH_ASSOC);

    // 現在のページの件数をセット
    $current_count = count($row);
    // 総件数をセット
    $whole_count = (int)$pdo->query('SELECT FOUND_ROWS()')->fetchColumn();
    // 総ページ数をセット
    $page_count = ceil($whole_count / DISP_MAX);
} catch(Exception $e){
    echo '<span class="error">エラーがありました</span><br>';
    echo '<span class="error">登録情報を取得できませんでした</span><br>';
   // echo $e->getMessage();
    exit;
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>酒場の<?php echo $_SESSION['account']['name']; ?></title>
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
                    <li><a href="updAccount.php">Edit My Account</a></li>
                    <li><a href="logout.php">Log Out</a></li>
                </ul>
            </nav>
        </header>
        <div class="wrapper">
            <h2 class="page-title logo">
               <!--  ログインした人の名前 -->
                ようこそ、<b><?php echo $_SESSION['account']['name'] ;?></b>
            </h2>
        </div>
           <div class="container">
            <section class="mymsg">
               <p>メッセージはこちらから</p>
                <!--   投稿の可否表示-->
                <?php if( !empty($_SESSION['success_message']) ): ?>
                <p class="success_msg"><?php echo $_SESSION['success_message']; ?></p>
                <?php unset($_SESSION['success_message']); ?>
                <?php endif; ?>
                <!--    投稿時のエラーメッセージ-->
                <?php if( !empty($error_message) ): ?>
                <ul class="error_message">
                    <?php foreach( $error_message as $value ): ?>
                    <li><?php echo $value; ?></li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>
                <!--投稿フォーム-->
                <form method="post">
                    <label>name : <br>
                    <?php echo $_SESSION['account']['name']; ?></label>
                    <label>message :<br>
                    <textarea id="view_message" type="text" name="view_message"></textarea></label>
                    <input type="hidden" name="id" value="<?php echo $_SESSION['id']; ?>">
                    <input type="hidden" name="msg" value="msg">
                    <input type="submit" class="button" name="btn" value="送信">
                </form>
            </section>

            <!--messageDBの読み込み.
            表示は最新から
            個々の修正は一番つらい
            -->
            <section class=thread>
                <p>最新の投稿一覧</p>
                <p class="page">
                    <a href="?">最新</a>
                <?php if ($page > 1): ?>
                <a href="myPage.php?page_id=<?php echo $page-1 ;?>">前</a> | 
                <?php endif; ?>
                    <?php
                    //ページ番号
                    for($i = 0;$i < $page_count; $i++){
                        echo '<a href="myPage.php?page_id=',$i+1,'">',$i+1,'</a>';
                        echo "|";
                    }
                    ?>
                <?php if (!empty($page_count) and $page < $page_count): ?>
                 <a href="myPage.php?page_id=<?php echo $page+1 ;?>">次</a>
                <?php endif; ?>
                <br>
                <?php
                    if (empty($current_count)) {
                        echo 'まだ書き込みはありません';
                    } else {
                        echo $whole_count,"件中",($tmp = ($page - 1) * DISP_MAX )+ 1,"件目～",$tmp + $current_count,"件目(",$page_count,"ページ中",$page,"ページ目)を表示中";
                    }
                    ?></p>
               
                <?php if( !empty($row) ): ?>
                    <?php foreach( $row as $value ): ?>
                    <article>
                        <div class="info">
                          
                            <p class="name"><?php echo $value['u_name']; ?></p>
                            <time><?php echo date('Y年m月d日 H:i', strtotime($value['date_time'])); ?></time>
                        </div>
                        <hr>
                        <div class=msgfram>
                        <p class=message><?php echo $value['view_message']; ?></p>
                        </div>
                    </article>
                    <?php endforeach; ?>
                <?php endif; ?>
                <p class="page">
                    <a href="?">最新</a>
                    <?php if ($page > 1): ?>
                    <a href="myPage.php?page_id=<?php echo $page-1 ;?>">前</a> | 
                    <?php endif; ?>
                    <?php
                    //ページ番号
                    for($i = 0;$i < $page_count; $i++){
                        echo '<a href="myPage.php?page_id=',$i+1,'">',$i+1,'</a>';
                        echo "|";
                    }
                    ?>
                    <?php if (!empty($page_count) and $page < $page_count): ?>
                    <a href="myPage.php?page_id=<?php echo $page+1 ;?>">次</a>
                    <?php endif; ?>
                    <br>
                    <?php
                    if (empty($current_count)) {
                        echo 'まだ書き込みはありません';
                    } else {
                        echo $whole_count,"件中",($tmp = ($page - 1) * DISP_MAX )+ 1,"件目～",$tmp + $current_count,"件目(",$page_count,"ページ中",$page,"ページ目)を表示中";
                    }
                    ?></p>
               </section>
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