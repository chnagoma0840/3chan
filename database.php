<?php
//セッションの開始が無ければスタート
if(!isset($_SESSION)){
    session_start();
}
require_once("./lib/util.php");


/***********************************************
DB情報
DBname : 3chan
user : ashibe
passeord : 007731584
会員情報
    テーブル名 : user
    フィールド : ①int(11)          u_id       (key)(unique)(AUTO_INCREMENT)
            : ② varchar(40)     u_name
            : ③ varchar(255)    u_password
            : ④ varchar(255)    u_mail      (unique)
掲示板
    テーブル名 : user_msg
            : ① int(11)         id          (key)(unique)(AUTO_INCREMENT)
            : ② varchar(40)     u_name
            : ③ text            view_message
            : ④ datetime        date_time
**************************************************/


//ログイン認証用
function login($email, $pass){
    $user = 'ashibe';
    $password = '007731584';
    $dbname = '3chan';
    $host = 'localhost';
    $dsn = "mysql:host={$host};dbname={$dbname};charset=utf8";
       try{ $pdo =new PDO($dsn, $user, $password);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = 'SELECT * FROM user WHERE u_mail = (:email) AND u_password = (:password)';
        $stm = $pdo->prepare($sql);
        $stm->bindValue(':email',$email, PDO::PARAM_STR);
        $stm->bindValue(':password',$pass, PDO::PARAM_STR);
        $stm->execute();
           $result = [];
        while($row=$stm->fetch()){
            $result['id'] = $row['u_id'];
            $result['name'] = $row['u_name'];
            $result['email'] = $row['u_mail'];
            $result['password'] = $row['u_password'];
        }
           
        if(isset($result)){
            return $result;
        }
           
          } catch(Exception $e){
           echo '<span class="error">エラーがありました</span><br>';
           exit;
           
       }
    
}
    //セッションアカウントへ保存用
    function accountCheck($email, $pass){
        $user = 'ashibe';
        $password = '007731584';
        $dbname = '3chan';
        $host = 'localhost';
        $dsn = "mysql:host={$host};dbname={$dbname};charset=utf8";
        try{
            $pdo =new PDO($dsn, $user, $password);
            $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = 'SELECT * FROM user WHERE u_mail = (:email) AND u_password = (:password)';
            $stm = $pdo->prepare($sql);
            $stm->bindValue(':email',$email, PDO::PARAM_STR);
            $stm->bindValue(':password',$pass, PDO::PARAM_STR);
            $stm->execute();
           
            while($row=$stm->fetch()){
                $result['id'] = $row['u_id'];
                $result['name'] = $row['u_name'];
                $result['email'] = $row['u_mail'];
                $result['password'] = $row['u_password'];
            }
        
            if(isset($result)){
                return $result;
            } 
        }catch(Exception $e){
            echo '<span class="error">エラーがありました</span><br>';
            
                exit;
        }

    }

//新規登録用
function insertCheck($name, $email, $pass){
    $gobackURL="newAccount.php";
    $user = 'ashibe';
    $password = '007731584';
    $dbname = '3chan';
    $host = 'localhost';
    $dsn = "mysql:host={$host};dbname={$dbname};charset=utf8";
    try{
        $pdo =new PDO($dsn, $user, $password);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stm = $pdo -> query("SELECT * FROM user");
        while($item = $stm->fetch()) {
            if($item['u_mail'] === $_POST['email']){
                throw new Exception('登録時のエラー');
            } else {
                $email = $email;
            }
        } 
        
        $sql = 'INSERT INTO user (u_name, u_password, u_mail) VALUES ((:name),(:password),(:email))';
        $stm = $pdo->prepare($sql);
        $stm->bindValue(':name',$_POST['name'], PDO::PARAM_STR);
        $stm->bindValue(':email',$_POST['email'], PDO::PARAM_STR);
        $stm->bindValue(':password',$_POST['password'], PDO::PARAM_STR);
        $stm->execute();

        while($row=$stm->fetch()){
            $result['id'] = $row['u_id'];
            $result['name'] = $row['u_name'];
            $result['email'] = $row['u_mail'];
            $result['password'] = $row['u_password'];
        }
       

        if(isset($result)){
            return $result;
        } 
    } catch(Exception $e){
        //不具合修正0619
        $_SESSION['error_message'] = "このメールアドレスは使用できません";
    header("Location: ./newAccount.php");
        
    }

}

//登録内容更新用

function updCheck($name, $email, $pass){
   
    $gobackURL="updAccount.php";
    $user = 'ashibe';
    $password = '007731584';
    $dbname = '3chan';
    $host = 'localhost';
    $dsn = "mysql:host={$host};dbname={$dbname};charset=utf8";
    try{
        $pdo =new PDO($dsn, $user, $password);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = 'UPDATE user SET u_name = (:name), u_mail = (:email), u_password = (:password) WHERE u_id = (:id)';
        $stm = $pdo->prepare($sql);
        $stm->bindValue(':name',$_POST['name'], PDO::PARAM_STR);
        $stm->bindValue(':email',$_POST['email'], PDO::PARAM_STR);
        $stm->bindValue(':password',$_POST['password'], PDO::PARAM_STR);
        $stm->bindValue(':id',$_POST['id'], PDO::PARAM_INT);
        $stm->execute();
        if( $stm ) {
            $success_message = "変更が完了しました！";
        } else {
            $error_message[] = "このメールアドレスは使用できません";
            exit;
        }
        while($row=$stm->fetch()){
            $result['id'] = $row['u_id'];
            $result['name'] = $row['u_name'];
            $result['email'] = $row['u_mail'];
            $result['password'] = $row['u_password'];
        }
        if(isset($result)){
            return $result;
        } 
    } catch(Exception $e){
        //不具合修正0619
        $_SESSION['error_message'] = "このメールアドレスは使用できません";
        header("Location: ./updAccount.php");
       // exit;
    }
}

//会員ページ内での保持用
function idCheck($id){

    $gobackURL="myPage.php";
    $user = 'ashibe';
    $password = '007731584';
    $dbname = '3chan';
    $host = 'localhost';
    $dsn = "mysql:host={$host};dbname={$dbname};charset=utf8";
    try{
        $pdo =new PDO($dsn, $user, $password);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = 'SELECT * FROM user WHERE u_id = (:id)';
        $stm = $pdo->prepare($sql);
        $stm->bindValue(':id',$_SESSION['id'], PDO::PARAM_INT);
        $stm->execute();
        while($row=$stm->fetch()){
            $result['id'] = $row['u_id'];
            $result['name'] = $row['u_name'];
            $result['email'] = $row['u_mail'];
            $result['password'] = $row['u_password'];
        }
        if(isset($result)){
            return $result;
        } 
    } catch(Exception $e){
        echo '<span class="error">エラーがありましたid</span><br>';
        echo '<span class="error">ログイン情報がありません。</span><br>';
        echo '<span><input type="button" value="戻る" onclick="location.href=\'frontPage.php\'"></span>';
        print_r($_SESSION);
        exit;
    }
}


function msgCheck($id,$message){

    $gobackURL="myPage.php";
    $user = 'ashibe';
    $password = '007731584';
    $dbname = '3chan';
    $host = 'localhost';
    $dsn = "mysql:host={$host};dbname={$dbname};charset=utf8";
    //投稿時間
    $now_date = date("Y-m-d H:i:s");
    try{
        $pdo =new PDO($dsn, $user, $password);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //メッセージをデータベースに登録
        $sql = 'INSERT INTO user_msg (view_id, view_message, date_time) VALUES ((:id),(:message),(:date))';
        $stm = $pdo->prepare($sql);
        $stm->bindValue(':id',$_POST['id'], PDO::PARAM_STR);
        $stm->bindValue(':message',$_POST['view_message'], PDO::PARAM_STR);
        $stm->bindValue(':date', $now_date, PDO::PARAM_STR);
        $stm->execute();
        if( $stm ) {
            $success_message = "メッセージを書き込みました。";
        } else {
            $error_message[] = "書き込みに失敗しました";
            exit;
        }
        while($row=$stm->fetch()){
            $result['u_name'] = $row['u_name'];
            $result['view_message'] = $row['view_message'];
            $result['date_time'] = $row['date_time'];
        } 
    } catch(Exception $e){
        echo '<span class="error">エラーがありました</span><br>';
        echo '<span class="error">登録できませんでした</span><br>';      
        echo $e->getMessage();

        exit;
    }
}





?>
