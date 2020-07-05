<?php
//セッションを開始
session_start(); 
//エスケープ処理やデータをチェックする関数を記述したファイルの読み込み
require_once("./functions.php"); 
//メールアドレス等を記述したファイルの読み込み
require_once('./mailvars.php'); 
?>
<?php
    function killSession(){
        //セッションの変数の値をからにする
        $_SESSION = [];
        //セッションクッキーを破棄する
        if(isset($_COOKIE[session_name()])){
            $params = session_get_cookie_params();
            setcookie(session_name(), '' , time()-36000, $params['path']);
        }
    }
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link herf="<link rel="stylesheet" href="https://unpkg.com/ress/dist/ress.min.css">
    <link href="https://fonts.googleapis.com/css?family=Philosopher" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Wallpoet&display=swap" rel="stylesheet">
    <script src="./jquery/js/jquery-3.4.1.min.js"></script>
    <script src="./jquery/js/script.js"></script>
    <title>送信画面</title>
</head>

<body>
    <div id="home2" class="big-img">
        <header class ="page-header wrapper" >
            <h1></h1>
            <nav>
                <ul class="main-nav">
                    <li><a href="./html/index.html">Home</a></li>
                    <li><a href="./html/news.html">News</a></li>
                    <li><a href="./html/portfolio.html">Portfolio</a></li>
                    <li><a href="./contact.php">Contact</a></li>
                    <li><a href="./frontPage.php">Log In</a></li>
                </ul>
            </nav>
        </header>

        <div class="home-content wrapper">
            <h2 class="page-title logo"><b>C<span>yb</span>erP<span>un</span>k</b></h2>
            <?php

                    //お問い合わせ日時を日本時間に
                date_default_timezone_set('Asia/Tokyo'); 
                
                //POSTされたデータをチェック
                $_POST = checkInput( $_POST );
                
                //固定トークンを確認（CSRF対策）
                if ( isset( $_POST[ 'ticket' ], $_SESSION[ 'ticket' ] ) ) {
                $ticket = $_POST[ 'ticket' ];
                if ( $ticket !== $_SESSION[ 'ticket' ] ) {
                    //トークンが一致しない場合は処理を中止
                    die( 'Access denied' );
                }
                } else {
                //トークンが存在しない場合（入力ページにリダイレクト）
                //die( 'Access Denied（直接このページにはアクセスできません）' ); //処理を中止する場合
                $dirname = dirname( $_SERVER[ 'SCRIPT_NAME' ] );
                $dirname = $dirname == DIRECTORY_SEPARATOR ? '' : $dirname;
                $url = ( empty( $_SERVER[ 'HTTPS' ] ) ? 'http://' : 'https://' ) . $_SERVER[ 'SERVER_NAME' ] . $dirname . '/contact.php';
                header( 'HTTP/1.1 303 See Other' );
                header( 'location: ' . $url );
                exit; //忘れないように
                }
                
                //変数にエスケープ処理したセッション変数の値を代入
                $name = h( $_SESSION[ 'name' ] );
                $email = h( $_SESSION[ 'email' ] ) ;
                $tel =  h( $_SESSION[ 'tel' ] ) ;
                $subject = h( $_SESSION[ 'subject' ] );
                $body = h( $_SESSION[ 'body' ] );
                
                //メール本文の組み立て
                $mail_body = 'コンタクトページからのお問い合わせ' . "\n\n";
                $mail_body .=  date("Y年m月d日 H時i分") . "\n\n"; 
                $mail_body .=  "お名前： " .$name . "\n";
                $mail_body .=  "Email： " . $email . "\n"  ;
                $mail_body .=  "お電話番号： " . $tel . "\n\n" ;
                $mail_body .=  "＜お問い合わせ内容＞" . "\n" . $body;
                
                //-------- sendmail（mb_send_mail）を使ったメールの送信処理------------
                
                //メールの宛先（名前<メールアドレス> の形式）。値は mailvars.php に記載
                $mailTo = mb_encode_mimeheader(MAIL_TO_NAME) ."<" . MAIL_TO. ">";
                
                //Return-Pathに指定するメールアドレス
                $returnMail = MAIL_RETURN_PATH; //
                //mbstringの日本語設定
                mb_language( 'ja' );
                mb_internal_encoding( 'UTF-8' );
                
                // 送信者情報（From ヘッダー）の設定
                $header = "From: " . mb_encode_mimeheader($name) ."<" . $email. ">\n";
                $header .= "Cc: " . mb_encode_mimeheader(MAIL_CC_NAME) ."<" . MAIL_CC.">\n";
                $header .= "Bcc: <" . MAIL_BCC.">";
                
                //メールの送信（結果を変数 $result に代入）
                if ( ini_get( 'safe_mode' ) ) {
                //セーフモードがOnの場合は第5引数が使えない
                $result = mb_send_mail( $mailTo, $subject, $mail_body, $header );
                } else {
                $result = mb_send_mail( $mailTo, $subject, $mail_body, $header, '-f' . $returnMail );
                }
                
            ?>

                <?php if ( $result ): ?>
                <p>
                    お問い合わせありがとうございます。1営業日以内にご返信させていただきます。<br>
                    自動返信メールをお送りしておりますのでご確認ください。<br>
                    1時間たっても届かない場合はお手数ですがこちらからご連絡ください。
                </p>
            <?php else:?>
                <p>
                    送信失敗です。下記の電話番号にお問い合わせください。<br>
                    ちなみにiniをいじるのが怖くてここで止めてます。
                    <?php killSession();?>
                </p>
            <?php endif;?>

            <p> 直通電話番号 : 06-1111-1111</p>
            <!-- <a class="button" href="news.html">ニュースを見る</a> -->
        </div>
    </div>