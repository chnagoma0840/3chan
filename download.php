<?php
session_start();
require_once("./lib/util.php");
//投稿データの取得
//// 変数の初期化
$csv_data = null;
$sql = null;
$row = null;
$message_array = array();
$limit = null;

if( !empty($_GET['limit']) ) {

    if( $_GET['limit'] === "10" ) {
        $limit = 10;
    } elseif( $_GET['limit'] === "30" ) {
        $limit = 30;
    }
}

if( !empty($_SESSION['admin_login']) && !empty($_GET['btn_download'])) {

    header("Content-Type: application/octet-stream");
    header("Content-Disposition: attachment; filename=メッセージデータ.csv");
    header("Content-Transfer-Encoding: binary");

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
        if( !empty($limit) ) {
            $sql = "SELECT * FROM user_msg ORDER BY date_time ASC LIMIT $limit";
            $stm = $pdo->prepare($sql);
            
        } else {
            $sql = "SELECT * FROM user_msg ORDER BY date_time ASC";
            $stm = $pdo->prepare($sql);
        }
        
        //カラム毎に配列へ格納
        $stm->execute();
        $row = $stm->fetchAll(PDO::FETCH_ASSOC);
        if(!empty($row)) {
            // 1行目のラベル作成
            $csv_data .= '"ID","投稿者ID","メッセージ","投稿日時"'."\n";
            foreach( $row as $value ) {
                // データを1行ずつCSVファイルに書き込む
                $csv_data .= '"' . $value['id'] . '","' . $value['view_id'] . '","' . $value['view_message'] . '","' . $value['date_time'] . "\"\n";
            }
        }
        // ファイルを出力	
        echo $csv_data;

        
    } catch(Exception $e){
        echo '<span class="error">エラーがありました</span><br>';
        echo '<span class="error">登録情報を取得できませんでした</span><br>';
        // echo $e->getMessage();
        exit;
    }
       
} else {
    
    // ログインページへリダイレクト
  header("Location: ./admin.php");
   
}


return;


?>