<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="ja">
        <title>M5-1</title>
    </head>
    <body>
        <?php
        //まずはデータベースへの接続
       
        $dsn="DBNAME";
        $user="USERNAME";
        $password="PASSWORD";
        $pdo=new PDO($dsn,$user,$password);
        //ここまで　ここは動かさない
        
        $sql="CREATE TABLE IF NOT EXISTS tbm5"
        ."("
        ."id INT AUTO_INCREMENT PRIMARY KEY,"
        ."date DATE,"
        ."name char(32),"
        ."comment TEXT,"
        ."password char(4)"
        .");"; 
        $stmt = $pdo->query($sql);
        //これでデータベースが出来た。
        //編集用分岐、一個目。valueをフォームに送る
        if(!empty($_POST["numEdit"])&&!empty($_POST["passEdit"])){
            $numEdit=$_POST["numEdit"];
            $passEdit=$_POST["passEdit"];
            //ここからid行の番号を取得する。そうすると、パスワードと名前投稿内容が取り出せる。
             //パスワードが入力されたものと等しかったら、名前と投稿内容をvalueに入れる。
            $id=$numEdit;
            $sql = "SELECT* FROM tbm5 WHERE id=:id";
            $stmt = $pdo->prepare($sql);                  
            $stmt->bindParam(':id', $id, PDO::PARAM_INT); 
            $stmt->execute();                            
            $results = $stmt->fetchAll(); 
            foreach ($results as $row){
            if($row["password"]==$passEdit){
            $value1=$row["name"];
            $value2=$row["comment"];
            $value3=$row["id"];
            $value4=$row["password"];
        }   
        }}
        
        ?>
        投稿
        <br>
        お名前
        <form action="" method="post">
        <input type="text" name="name" value=<?php if(!empty($value1)){echo $value1;}?>> <br>
         コメント
        <input type="text" name="str"  value=<?php if(!empty($value2)){echo $value2;}?>> <br>
        <input type="hidden" name="hidden" value=<?php if(!empty($value3)){echo $value3;}?>>
        パスワード
        <input type="password" name="pass" size="4" value=<?php if(!empty($value4)){echo $value4;}?>>
        <input type="submit" name="submit">
        </form>
        <br>
        削除
        <form action="" method="post">
        <input type="number" name="num">
        <br>    
        パスワード
        <input type="password" name="passDelete" size="4">
         <input type="submit" name="delete">
        </form>
        <br>
        編集
        <form action="" method="post">
        <input type="number" name="numEdit">
        <br>
        パスワード
        <input type="password" name="passEdit" size="4">
        <input type="submit" name="submit">
        </form>
        <br>
        <?php
        //普通に投稿する用の部分を作る。名前、投稿番号が空じゃなくて、隠し番号が空の場合の処理。
        if(!empty($_POST["name"])&&!empty($_POST["str"])&&!empty($_POST["pass"])&&empty($_POST["hidden"])){
        $sql=$pdo->prepare("INSERT INTO tbm5(name,comment,password,date) VALUES(:name,:comment,:password,:date)");
        /*$sql=$pdo->prepare("INSERT INTO tbtest (name,comment) VALUES (:name, :comment)");*/
        $sql->bindParam(":name",$name,PDO::PARAM_STR);
        $sql->bindParam(":comment",$str,PDO::PARAM_STR);
        $sql->bindParam(":password",$password,PDO::PARAM_STR);
        $sql->bindParam(":date",$date,PDO::PARAM_STR);
        $password=$_POST["pass"];
        $str=$_POST["str"];
        $name=$_POST["name"];
        $date=date("Y/m/d");
        $sql->execute();
        //ここは問題なく動いた！！
        }
        //編集したい部分用　名前、パスワード,隠し番号が空でない時の処理
        if(!empty($_POST["name"])&&!empty($_POST["str"])&&!empty($_POST["hidden"])&&!empty($_POST["pass"])){
            $str=$_POST["str"];
            $hidden=$_POST["hidden"];
            //hiddenとidが一致するものを取り出して,書き換える。
            $name=$_POST["name"];
            $comment=$_POST["str"];
            $date=date("Y/m/d");
            $password=$_POST["pass"];
            $id =$hidden;  //変更する投稿番号
            $sql = 'UPDATE tbm5 SET name=:name,comment=:comment, date=:date,password=:password WHERE id=:id';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
            $stmt->bindParam(":date",$date,PDO::PARAM_STR);
            $stmt->bindParam(":password",$password,PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
   


        }

        //削除したい部分用を作る。削除番号とパスワードが空でない場合の処理。
        if(!empty($_POST["num"])&&!empty($_POST["passDelete"])){
            $numDel=$_POST["num"];
            $passDel=$_POST["passDelete"];
            //まずは削除番号とidを比較して所得。パスワードを取り出して、比較。
            $id=$numDel;
            $sql = 'SELECT * FROM tbm5 WHERE id=:id ';
            $stmt = $pdo->prepare($sql);                  
            $stmt->bindParam(':id', $id, PDO::PARAM_INT); 
            $stmt->execute();                            
            $results = $stmt->fetchAll(); 
            foreach ($results as $row){
        if($row['password']==$passDel){
        $id = $numDel;
        $sql = 'delete from tbm5 where id=:id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        //よしここも問題なく動いた。
         }}}

        $sql="SELECT *FROM tbm5";
        $stmt=$pdo->query($sql);
        $results=$stmt->fetchAll();
        foreach($results as $row){
        echo $row["id"].",";
        echo $row["name"].",";
        echo $row["comment"].",";
        echo $row["date"]."<br>";
        echo "<hr>";
        }
        ?>
    </body>
</html>
