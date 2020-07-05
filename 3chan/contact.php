<?php
    //セッションを開始
    session_start();
    
    //セッションIDを更新して変更（セッションハイジャック対策）
    session_regenerate_id( TRUE );
    
    //エスケープ処理やデータチェックを行う関数のファイルの読み込み
    require_once("functions.php");
    
    //初回以外ですでにセッション変数に値が代入されていれば、その値をそうでなければNULLで初期化
    $name = isset( $_SESSION[ 'name' ] ) ? $_SESSION[ 'name' ] : NULL;
    $email = isset( $_SESSION[ 'email' ] ) ? $_SESSION[ 'email' ] : NULL;
    $email_check = isset( $_SESSION[ 'email_check' ] ) ? $_SESSION[ 'email_check' ] : NULL;
    $tel = isset( $_SESSION[ 'tel' ] ) ? $_SESSION[ 'tel' ] : NULL;
    $subject = isset( $_SESSION[ 'subject' ] ) ? $_SESSION[ 'subject' ] : NULL;
    $body = isset( $_SESSION[ 'body' ] ) ? $_SESSION[ 'body' ] : NULL;
    $error = isset( $_SESSION[ 'error' ] ) ? $_SESSION[ 'error' ] : NULL;
    
    //個々のエラーを初期化（$error は定義されていれば配列）
    $error_name = isset( $error['name'] ) ? $error['name'] : NULL;
    $error_email = isset( $error['email'] ) ? $error['email'] : NULL;
    $error_email_check = isset( $error['email_check'] ) ? $error['email_check'] : NULL;
    $error_tel = isset( $error['tel'] ) ? $error['tel'] : NULL;
    $error_tel_format = isset( $error['tel_format'] ) ? $error['tel_format'] : NULL;
    $error_subject = isset( $error['subject'] ) ? $error['subject'] : NULL;
    $error_body = isset( $error['body'] ) ? $error['body'] : NULL;
    
    //CSRF対策の固定トークンを生成
    if ( !isset( $_SESSION[ 'ticket' ] ) ) {
        //セッション変数にトークンを代入
        $_SESSION[ 'ticket' ] = sha1( uniqid( mt_rand(), TRUE ) );
    }
    
    //トークンを変数に代入
    $ticket = $_SESSION[ 'ticket' ];
?>



<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link href="./jquery/js/vegas/vegas.css" rel="stylesheet"/>
    <link href="./css/style.css" rel="stylesheet">
    <script src="./jquery/js/jquery-3.4.1.min.js"></script> <!--jQueryファイル-->
    <script src="./jquery/js/vegas/vegas.min.js"></script>
    <script src="./jquery/js/script.js"></script><!--Vegas2の設定用のjsファイル -->
    <script src="./jquery/js/script2.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>お問い合わせはこちら</title>
</head>
<body>
    <div id="home">
        <div id="contact">
            <header class="page-header wrapper">
                <h1 class=""></h1>
                <nav>
                    <ul class="main-nav">
                        <li><a href="index.html">Home</a></li>
                        <li><a href="admin.php">admin</a></li>
                        <li><a href="contact.php">Contact</a></li>
                        <li><a href="newAccount.php">sing up</a></li>
                        <li><a href="frontPage.php">Log In</a></li>
                    </ul>
                </nav>
            </header>

            <div class="wrapper">
                <h2 class="page-title logo"><b>C<span>on</span>t<span>a</span>ct</b></h2>
                <form method="post" action="./comfirm.php">
                    <div>
                        <label for="name">お名前
                        <span class="error"><?php echo h( $error_name ); ?></span>
                        </label>
                        <input type="text" class="form-control validate max50 required" id="name" name="name" placeholder="氏名" value="<?php echo h($name); ?>">
                    </div>

                    <div>
                        <label for="email">メールアドレス
                        <span class="error"><?php echo h( $error_email ); ?></span>
                        </label>
                        <input type="email" id="email" name="email" placeholder= "メールアドレス" value="<?php echo h($email); ?>">
                    </div>
                    <div>
                        <label for="email_check">メールアドレス(確認用) (必須)
                        <span class="error"><?php echo h( $error_email_check ); ?></span>
                        </label>
                        <input type="text" class="form-control validate email_check required" id="email_check" name="email_check" placeholder="Email アドレス（確認のためもう一度ご入力ください。）" value="<?php echo h($email_check); ?>">
                    </div>

                    <div>
                        <label for="tel">電話番号（半角英数字） 
                            <span class="error"><?php echo h( $error_tel ); ?></span>
                            <span class="error"><?php echo h( $error_tel_format ); ?></span>
                        </label>
                        <input type="text" class="validate max30 tel form-control" id="tel" name="tel" value="<?php echo h($tel); ?>" placeholder="お電話番号（半角英数字でご入力ください）">
                    </div>

                    <div>
                        <label for="subject">件名（必須） 
                            <span class="error"><?php echo h( $error_subject ); ?></span> 
                        </label>
                        <input type="text" class="form-control validate max100 required" id="subject" name="subject" placeholder="件名" value="<?php echo h($subject); ?>">
                    </div>
                    <div>
                        <label for="body">メッセージ
                        <span class="error"><?php echo h( $error_body ); ?></span>
                        </label>
                        <textarea class="form-control validate max1000 required" id="body" name="body" placeholder="お問い合わせ内容（1000文字まで）をお書きください" rows="3"><?php echo h($body); ?></textarea>
                    </div>

                    <input type="submit" class="button" value="送信">
                      <!--確認ページへトークンをPOSTする、隠しフィールド「ticket」-->
                    <input type="hidden" name="ticket" value="<?php echo h($ticket); ?>">
                </form>
            </div><!-- /.wrapper -->
        </div><!-- /#contact -->

        <section id="location">
            <div class="wrapper">
                <div class="location-info">
                    <h3 class="sub-title">実際に送られるかもしれません。完成度次第です。</h3>
                    <p>
                        住所： 大阪府〇〇市<br>
                        〇〇〇〇〇〇〇 000-22-1<br>
                        〇〇〇〇<br>
                        電話： 06-1111-1111<br>
                        営業時間： 10:00〜20:00<br>
                        休日：土曜日、日曜日
                    </p>
                </div><!-- /.location-info -->
            </div><!-- /.wrapper -->
        </section><!-- /#location -->
    </div>
</body>
</html>