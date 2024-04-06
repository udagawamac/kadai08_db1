<?php
//1. POSTデータ取得
//[name,email,color,indate]
$name   = $_POST["name"];
$email  = $_POST["email"];
$color  = $_POST["color"];
// $indate = $_POST["indate"];

//2. DB接続
try {
  //Password:MAMP='root',XAMPP=''
  $pdo = new PDO('mysql:dbname=gs_db;charset=utf8;host=localhost','root','');
} catch (PDOException $e) {
  exit('DB_CONNECT:'.$e->getMessage());
}

//３．データ登録SQL作成
$sql = "INSERT INTO gs_bm_table(name,email,color,indate)VALUES(:name, :email, :color,sysdate())";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':name',   $name,   PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':email',  $email,  PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':color',  $color,  PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$status = $stmt->execute(); //true or false

//４．データ登録処理後
if($status==false){
  //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
  $error = $stmt->errorInfo();
  exit("SQL_ERROR:".$error[2]);
}else{
  //５．index.phpへリダイレクト
  // header("Location: index2.php");\
  //５．select4.phpへリダイレクト
  header("Location: select4.php");
  exit();
}
?>
