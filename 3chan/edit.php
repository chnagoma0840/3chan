<?php
session_start();
require_once("./lib/util.php");
require_once("./database.php");
require_once("./account.php");
if(isset($_POST)){
    $_POST = es($_POST);
}

// 管理者としてログインしているか確認
if( empty($_SESSION['admin_login'])) {
    // ログインページへリダイレクト
    header("Location: ./admin.php");
}
//選択メッセージの表示　or 修正
if( !empty($_GET['message_id']) && empty($_POST['message_id']) ) {
    //メッセージの表示
    $message_id = (int)htmlspecialchars($_GET['message_id'], ENT_QUOTES);
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
        $sql = "SELECT * FROM user_msg INNER JOIN user ON user_msg.view_id = user.u_id WHERE id = (:id)";
        $stm = $pdo->prepare($sql);
        $stm->bindValue(':id',$message_id, PDO::PARAM_INT);
        $stm->execute();
        //取得は１件のみ
        $row = $stm->fetch(PDO::FETCH_ASSOC);
        $message_data = $row;
    } catch(Exception $e){
        echo '<span class="error">エラーがありました</span><br>';
        echo '<span class="error">登録情報を取得できませんでした</span><br>';
        // echo $e->getMessage();
        exit;
    } 
} elseif ( !empty($_GET['message_id']) ) {
    //メッセージの修正
    $message_id = (int)htmlspecialchars( $_POST['message_id'], ENT_QUOTES);
        if( empty($_POST['u_name']) ) {
            $error_message[] = '表示名を入力してください。';
        } else {
            $message_data['u_name'] = htmlspecialchars($_POST['u_name'], ENT_QUOTES);
        }
        if( empty($_POST['view_message']) ) {
            $error_message[] = 'メッセージを入力してください。';
        } else {
            $message_data['view_message'] = htmlspecialchars($_POST['view_message'], ENT_QUOTES);
        }
        if( empty($_POST['view_id']) ) {
            $error_message[] = 'メッセージを入力してください。';
        } else {
            $message_data['view_id'] = htmlspecialchars($_POST['view_id'], ENT_QUOTES);
        }
        if( empty($error_message) ) {
            $user = 'ashibe';
            $password = '007731584';
            $dbname = '3chan';
            $host = 'localhost';
            $dsn = "mysql:host={$host};dbname={$dbname};charset=utf8";
            try{
                $pdo =new PDO($dsn, $user, $password);
                $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "UPDATE user_msg SET view_message = (:message) WHERE id = (:id)";
                $stm = $pdo->prepare($sql);
                $stm->bindValue(':message',$message_data['view_message'], PDO::PARAM_STR);
                $stm->bindValue(':id',$message_id, PDO::PARAM_INT);
                $stm->execute();
                
                $sql = "UPDATE user SET u_name = (:name) WHERE u_id =(:id)";
                $stm = $pdo->prepare($sql);
                $stm->bindValue(':name',$message_data['u_name'], PDO::PARAM_STR);
                $stm->bindValue(':id',$message_data['view_id'], PDO::PARAM_INT);
                $stm->execute();
                if( $stm ) {
                    header("Location: ./admin.php");
                }
            } catch(Exception $e){
                echo '<span class="error">エラーがありました</span><br>';
                echo '<span class="error">更新ができませんでした </span><br>';
                // echo $e->getMessage();
                exit;
        }
    }
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>酒場の管理者(編集)</title>
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
                <h1>編集ページ</h1>
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
                <h2 class="page-title logo">
                    <b>「S」akaba no okuba</b>
                </h2>
                <div>
                    <?php if( !empty($error_message) ): ?>
                    <ul class="error_message">
                        <?php foreach( $error_message as $value ): ?>
                        <li>・<?php echo $value; ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <?php endif; ?>
                    <!--投稿フォーム-->
                    <form method="post">
                    <div>
                        <label>name:<input type ="text" name="u_name" value="<?php if(!empty($message_data['u_name']) ){ echo $message_data['u_name']; } ?>" ></label>
                        <label>message:<textarea id="view_message" name="view_message"><?php if( isset($message_data['view_message']) ){ echo $message_data['view_message']; } ?></textarea></label>
                    </div>
                        <a class="button" href="admin.php">キャンセル</a>
                        <input type="submit" class="button" name="btn_submit" value="更新">
                        <input type="hidden" name="message_id" value="<?php echo $message_data['id']; ?>">
                        <input type="hidden" name="view_id" value="<?php echo $message_data['view_id']; ?>">
                        
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>
</html>