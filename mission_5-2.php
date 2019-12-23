<?php
//データベースへの接続開始
	$dsn = 'データベース名';
	$user = 'ユーザー名';
	$password = 'パスワード';
	$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
//データベース接続完了
//テーブル作成開始
/*	$sql = "CREATE TABLE IF NOT EXISTS tbtest5"
	." ("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"
	. "name char(32),"
	. "comment TEXT,"
	. "date TEXT,"
	. "password TEXT"
	.");";
	$stmt = $pdo -> query($sql);*/
//テーブル作成完了
//変数の指定
$name_input = null;//最初の名前欄
$comment_input = null;//最初のコメント欄
$edit_input = null;//最初の編集番号指定
if(isset($_POST['button1'])){
//ボタン1を押したとき
	if(!empty($_POST['name'])&&!empty($_POST['comment'])){
	//name,commentデータがある時
		if(empty($_POST['edit'])){
		//editが何もない時そのままデータを入れる
		//insertを使ってデータ入力
			$sql = $pdo -> prepare("INSERT INTO tbtest5 (name, comment, pass, date) VALUES (:name, :comment, :pass, :date)");
			$sql -> bindParam(':name', $name, PDO::PARAM_STR);
			$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
			$sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
			$sql -> bindParam(':date', $date, PDO::PARAM_STR);
			$name = $_POST['name'];
			$comment = $_POST['comment'];
			$pass = $_POST['password1'];
			$date = date("Y/m/d H:i:s");
			$sql -> execute();
		}else{
		//editに番号がある時そのデータを編集する
			$name1 = $_POST['name'];
			$comment1 = $_POST['comment'];
			$pass1 = $_POST['password1'];
			$edit = $_POST['edit'];
			$sql = 'SELECT * FROM tbtest5';
			$stmt = $pdo->query($sql);
			$results = $stmt->fetchAll();
			foreach($results as $value){
				if($edit == $value['id']){
				//編集番号とデータ番号が同じ時編集
					$id = $edit;
					$name = $name1;
					$comment = $comment1;
					$pass = $pass1;
					$date = date("Y/m/d H:i:s");
					//書き込む
					$sql = 'update tbtest5 set name=:name,comment=:comment,pass=:pass,date=:date where id=:id';
					$stmt = $pdo->prepare($sql);
					$stmt->bindParam(':name', $name, PDO::PARAM_STR);
					$stmt->bindParam(':comment', $comment, PDO::PARAM_STR);	
					$stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
					$stmt->bindParam(':date', $date, PDO::PARAM_STR);
					$stmt->bindParam(':id', $id, PDO::PARAM_INT);
					$stmt->execute();
				}else{
				//それ以外はそのまま
				}
			}
		}
	}else{
	//name,commentデータがない時データ表示
	}
//ボタン1を押したとき終了
}elseif(isset($_POST['button2'])){
//ボタン2を押したとき
	if(!empty($_POST['delete'])){
	//deleteデータがある時
		$pass=$_POST['password2'];
		$delete = $_POST['delete'];
		//データ読み込み
		$sql = 'SELECT * FROM tbtest5';
		$stmt = $pdo->query($sql);
		$results = $stmt->fetchAll();
		foreach($results as $value){
			if($delete == $value['id']){ 
			//削除番号とidが等しいとき
			$get_pass = $value['pass'];
			//パスワードを取得
				if($get_pass == $pass){
				//削除パスがパスワードと等しいとき削除
					$id = $value['id'];
					$sql = 'delete from tbtest5 where id=:id';
					$stmt = $pdo->prepare($sql);
					$stmt->bindParam(':id', $id, PDO::PARAM_INT);
					$stmt->execute();
					$get_pass=null;
				}else{
				//パスワードが違うとき
					echo "パスワードが違います".'<br/>';
					$get_pass=null;
				}
			}else{
			}
		}
	}
//ボタン2を押したとき終了
}elseif(isset($_POST['button3'])){
//ボタン3を押したとき
	if(!empty($_POST['custom'])){
	//customデータがある時
		$pass=$_POST['password3'];
		$custom=$_POST['custom'];
		//データ読み込み
		$sql = 'SELECT * FROM tbtest5';
		$stmt = $pdo->query($sql);
		$results = $stmt->fetchAll();
		foreach($results as $value){
			if($custom == $value['id']){
			//編集番号とidが等しいとき
			$get_pass = $value['pass'];
			//パスワードを取得
				if($get_pass == $pass){
				//削除パスがパスワードと等しいときデータをフォームに入れる
					$name_input = $value['name'];
					$comment_input = $value['comment'];
					$edit_input = $value['id'];
					$get_pass=null;
				}else{
				//パスワードが違うとき
					echo "パスワードが違います".'<br/>';
					$get_pass=null;
				}
			}
		}
	}else{
	//customデータがない時
	}
//ボタン3を押したとき終了
}else{
//その他
}
//selectによって表示
$sql = 'SELECT * FROM tbtest5';
$stmt = $pdo->query($sql);
$results = $stmt->fetchAll();
foreach($results as $row){
	echo $row['id'].',';
	echo $row['name'].',';
	echo $row['comment'].',';
	echo $row['date'].'<br>';
	echo "<hr>";
}
?>
<html>
 <head>
  <meta charset="utf-8">
 </head>
<body>
<form action="https://tb-210695.tech-base.net/mission_5-1final.php" method="post">
	<input type="text" name="name" placeholder="名前" value="<?php echo $name_input ?>">
	<br>
	<input type="text" name="comment" placeholder="コメント" value="<?php echo $comment_input ?>">
	<br>
	<input type="text" name="password1" placeholder="パスワード" value="">
	<input type="hidden" name="edit" value="<?php echo $edit_input ?>">
	<input type="submit" value="送信" name="button1">
	<br>
	<input type="text" name="delete" placeholder="削除対象番号">
	<br>
	<input type="text" name="password2" placeholder="パスワード" value="">
	<input type="submit" value="削除" name="button2">
	<br>
	<input type="text" name="custom" placeholder="編集対象番号">
	<br>
	<input type="text" name="password3" placeholder="パスワード" value="">
	<input type="submit" value="編集" name="button3">
</form>
</body>
</html>