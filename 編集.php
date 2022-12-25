<!-- 誤入力があった時に対処するプログラム　-->
<!-- GitHub notice-board -->
<!DOCTYPE html>
<html lang='ja'>
    <head>
        <meta charset="utf-8">
        <title>mission2_01</title>
        mission5-1
        <br>
    </head>
    
        <?php
        $counter = 0;
        
        $dsn = 'データベース名';
        $user = 'ユーザー名';
        $password = 'パスワード';
        $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
        
        //テーブルが存在しない時は作る
        $sql = "CREATE TABLE IF NOT EXISTS tbtest5_1"
        ." ("
        . "id INT AUTO_INCREMENT PRIMARY KEY,"
        . "name char(32),"
        . "comment TEXT,"
        . "date TEXT,"
        ."pass TEXT"
        .");";
        $stmt = $pdo->query($sql);
        
        
             //　編集作業
        if(!empty($_POST["edit_num"]) && !empty($_POST["pass_edit"])){
            $edit_num=$_POST["edit_num"];
            $pass_edit=$_POST["pass_edit"];
            $id = $edit_num;
            //$number =$_POST["num2"];
            
            $sql = 'SELECT * FROM tbtest5_1 WHERE id=:id ';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
            $stmt->execute();                                  // ←SQLを実行する。
            $results = $stmt->fetchAll(); 
            foreach ($results as $row){
            $new_name    = $row['name'];
            $new_comment = $row['comment'];
            $pass_edit_data = $row['pass'];
            }
            if($pass_edit_data==$pass_edit){
                $counter = 1;
                echo"編集する内容を入力してください。";
            }
            else{
                echo"パスワードが正しくありません。";
            }
        }
        if(!empty($_POST["edit_num"]) && empty($_POST["pass_edit"])){
            echo "編集パスワードを入力してください";
        }
       
        
        
        if(!empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["hidden"]) ){
            $name=$_POST["name"];
            $comment=$_POST["comment"];
            $date = date("Y/m/d H:i:s");
            $edit_n=$_POST["hidden"];
            $pass_edit = $_POST["pass_edit"];
            
            $id = $edit_n; //変更する投稿番号
            //$name = $new_name;
            //$comment = $new_comment;
            $sql = 'UPDATE tbtest5_1 SET name=:name,comment=:comment,date=:date, pass=:pass  WHERE id=:id'; //UPDATEは更新するとき
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
            $stmt->bindParam(':date', $date, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':pass', $pass_edit, PDO::PARAM_STR);
            $stmt->execute();
        }
        
        //新規投稿
        if(!empty($_POST["name"]) && !empty($_POST["comment"]) && empty($_POST["hidden"])){
            $sql = $pdo -> prepare("INSERT INTO tbtest5_1 (name, comment, date, pass) VALUES (:name, :comment, :date, :pass)");
            $sql -> bindParam(':name', $name, PDO::PARAM_STR);
            $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
            $sql -> bindParam(':date', $date, PDO::PARAM_STR);
            $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
            $name = $_POST["name"];
            $comment = $_POST["comment"];
            $date = date("Y/m/d H:i:s");
            $pass = $_POST["pass_new"];
            $sql -> execute();
            echo"入力を新規投稿として受け付けました。";
            echo"<br>";
                }
        //------------
        
        
        // 削除---------------
        if(!empty($_POST["del_num"]) && !empty($_POST["pass_del"])){
            $del_num=$_POST["del_num"];
            $id = $del_num;
            $pass_del = $_POST["pass_del"];
            
            $sql = 'SELECT * FROM tbtest5_1 WHERE id=:id ';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT); 
            $stmt->execute();                             
            $results = $stmt->fetchAll(); 
            foreach ($results as $row){
            $pass_del_data = $row['pass'];
            }
            if($pass_del == $pass_del_data){
            $sql = 'delete from tbtest5_1 where id=:id';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            }
            else{
                echo"パスワードが正しくありません。";
            }
            }
            if(!empty($_POST["del_num"]) && empty($_POST["pass_del"])){
                echo"パスワードを入力してください";
            }
        //-------------------
        
        
?>

    <body>
        <form action=""method="post">
            <!--新規、削除-->
            <input type="text" name="name"placeholder ="名前" 
                value="<?php if($counter == 1){ echo $new_name;}?>">
            <input type="text" name="comment"placeholder ="コメント" 
                value="<?php if($counter == 1){ echo $new_comment;}?>">
            <input type="<?php if($counter==1){echo"hidden";} else{echo"text";} ?>" name="pass_new"placeholder="パスワード設定">
            <br>
            <input type="submit" name="submit">
            <br>
            <input type="<?php if($counter==1){echo"hidden";} else{echo"number";} ?>" name="del_num"placeholder ="削除番号">
            <input type="<?php if($counter==1){echo"hidden";} else{echo"text";} ?>" name="pass_del"placeholder="パスワード">
            <br>
            <input type="<?php if($counter==1){echo"hidden";} else{echo"submit";} ?>"value="削除">
            <br>
            <br>
            <!--編集-->
            <input type="<?php if($counter==1){echo"hidden";} else{echo"number";} ?>" name="edit_num"placeholder="編集対象番号">
            <input type="<?php if($counter==1){echo"hidden";} else{echo"text";} ?>" name="pass_edit"placeholder="パスワード"
                value="<?php if($counter ==1){ echo $pass_edit;}else{echo"";}?>">
            <br>
            <input type="<?php if($counter==1){echo"hidden";} else{echo"submit";} ?>" name="edit" value="編集">
            <input type ="hidden" name="hidden" value="<?php if(!empty($_POST["edit_num"])){ echo$_POST["edit_num"];}?>">
            <br>
            <br>
            
            <!--?php echo$_POST["edit_n"] ?> -->
            <!-- issetは変数が存在するかどうかを確かめる変数。　emptyはその中身があるかどうかを確かめる変数。-->
            <!-- 二段階で入力してしまっていた。　empty($_POST["num2"])がみそ -->
            
            
        </form>
        
        <?php
        
        $sql = 'SELECT * FROM tbtest5_1';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
            //$rowの中にはテーブルのカラム名が入る
            echo $row['id'].',';
            echo $row['name'].',';
            echo $row['comment'].',';
            echo $row['date'].'<br>';
            //echo $row['pass'].'<br>';
        echo "<hr>"; //水平線を引くためのタグ ------------------的なやつ
        }
        ?>
        
    </body>
</html>