<?php
//セッションの開始が無ければスタート
if(!isset($_SESSION)){
    session_start();
}
require_once("./lib/util.php");
require_once("./database.php");

if(!cken($_POST)){
    $encoding = mb_internal_encoding();
    $err = "Encoding Error! The expected encoding is" . $encoding;
    exit($err);
}

if(!empty($_POST)){
    $_POST = es($_POST);
}
if(!empty($_SESSION)){
    $_SESSION = es($_SESSION);
}

$error = array();
$login = null;
$now_date = null;
$data = null;
$file_handle = null;
$split_data = null;
$message = array();
$message_array = array();
$success_message = null;
$error_message = array();


//postされた値をセッションに保存。無ければエラーメッセージ
if(empty($_POST['name'])){
    $error[] = "名前を入力してください";
} else{
    $_POST['name'] = es($_POST['name']);
}
if(empty($_POST['email'])){
    $error[] = "メールアドレスを入力してください";
}else{
    $_POST['email'] = es($_POST['email']);
}
if(empty($_POST['password'])){
    $error[] = "パスワードを入力してください";
}else{
    $_POST['password'] = es($_POST['password']);
}


if(!empty($_SESSION['account']['id'])){
    $_SESSION['id'] = $_SESSION['account']['id'];
}


//ポストデータ初回エラー回避用
if(!empty($_POST['flag'])){
    //ポストデータと一致した場合
    if($_POST['flag'] == "login") {
    //ポストデータに問題がない場合
        if(!empty($_POST['email']) && !empty($_POST['password'])){ 
            //ログイン(f)：データベースと照合。
            if ($account= login($_POST['email'], $_POST['password'])){
                //$_SESSION['acount']へreturnデータを連想配列保存
                $_SESSION['account'] = $account;
                //承認成功時には会員ページに移行
                header("Location: ./myPage.php");
                } else {
                //データベースミスマッチの場合ログインフラグ破綻
                $error[] = "アカウント情報が確認できません";
                $login = false;
            }
         } else {
            $login = false;
        }
    }
}

//セッションにあるアカウント情報からログイン
//ログインフラグの作成 
if(isset($_SESSION['account'])){
    //アカウントチェック(f):データベースと照合
    $account = accountCheck($_SESSION['account']['email'], $_SESSION['account']['password']);
    if(isset($account)){
        //ログインフラグ成立
        $login = true;
        $_SESSION['account'] = $account;
    } else {
        //フラグ粉砕
        $errror[] = "アカウント情報が確認できません";
        $login = false;
        //セッションデータ破棄
        unset($_SESSION['account']);
    }
    //セッションに保存されてなければエラー
} 

//新規登録用
//hiddenされたPOSTにsetがあれば
if(isset($_POST['set'])){
    if($_POST['set'] == "set") {
        if($_POST['password'] === $_POST['password2']){
        //入力内容確認
            if(!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['password'])){
                //DBに新規登録
                $account　= insertCheck($_POST['name'], $_POST['email'], $_POST['password']);
                        //ログインフラグ成立
             } else {
                $_SESSION['error_message'] = "1";
            }
        } else {
            $_SESSION['error_message'] = "2";
        }
    } else {
        $_SESSION['error_message'] = "3"; 
    }
}


//登録更新用
if(isset($_POST['upd'])){
    if($_POST['upd'] == "upd") {
        //入力内容確認   
        if(!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['password'])){
            //DBに新規登録
            updCheck($_POST['name'], $_POST['email'], $_POST['password']);
            $account=idCheck($_SESSION['id']);
            $_SESSION['account'] = $account;
            $login = true;
            $success_message = "登録内容が変更されました";
           
         } else {
            $error_message[] ="更新されませんでした";
        }
    }else {
        $error_message[] ="更新されませんでした";;
    }
}


//メッセージ投稿用
if(!empty($_POST['msg'])){
    //ポストデータと一致した場合
    //ポストデータに問題がない場合
    if(!empty($_POST['id']) && !empty($_POST['view_message'])){ 
        //ログイン(f)：データベースと照合。
       
        $_POST['view_message'] = htmlspecialchars($_POST['view_message'], ENT_QUOTES);
        msgCheck($_POST['id'], $_POST['view_message']);
        $_SESSION['success_message'] = "メッセージを書き込みました";
        } else {
            //データベースミスマッチの場合ログインフラグ破綻
        $error_message[] ="メッセージの書き込みが出来ませんでした";
    }
    header("Location: ./myPage.php");
}


    



//エラーメッセージ作成
$errormsg = implode('<br>', $error);


?>
