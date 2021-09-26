<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body bgcolor="#FAF0E6">
      <form method="post">
        
        <input type="text" name="name" placeholder="名前">
        <input type="color" name="color" ><br>
        <textarea placeholder="コメント" name="comment" rows="5" cols="40"></textarea>
        <input type="submit" name="submit"><br>
        <input type="number" name="e_number" placeholder="削除対象番号">
        <input type="submit" name="e_submit">
    </form>
    
    <?php
    $filename="m3-3.txt";
    
    
    
    if (isset($_POST ["submit"])) {
       //エラーメッセージを入れる配列を用意 
       $error_message =  array ();  
       
        //名前が入力された時
        if ($_POST ["name"]!=="") {
        //データがセットされていたら各変数にPOSTのデータを格納
            $name = htmlspecialchars($_POST["name"],ENT_QUOTES);
        //各データがなかったらエラーメッセージを配列に格納
        }else{
          $error_message[]= '<font color="Crimson">名前が入力されていません。<br></font>';
        }
        //コメントが入力された時
        if ($_POST["comment"]!=="") {
            $comment_s = htmlspecialchars($_POST["comment"],ENT_QUOTES);
            //$searchの[]内の文字を$replaceの[]内の文字に変換
            //この場合は$comment_s内の改行を¥br¥に変換
            $search = ["\r\n", "\r", "\n",ENT_QUOTES];
            $replace = ["¥br¥", "¥br¥", "¥br¥","¥br¥"];
            $comment = str_replace($search,$replace, $comment_s);
        }else{
          $error_message[]='<font color="Crimson">コメントが入力されていません。<br></font>';
        } 
        //入力された色をPOSTで取得
        $color = htmlspecialchars($_POST["color"],ENT_QUOTES);
        
        
        //エラーがないとき
        if (!count($error_message)){
            echo "<hr size='1'>";
            $date=date("Y/m/d H:i:s");
                //ファイルが存在するとき
                //(ファイルに入力されたデータを書き込むため)
                    if(file_exists($filename)){
                    //$filename内の行数を数える+1
                    $num= count(file($filename));
                    $num++;
                    
                    //ファイルがないときは$num=1                 
                    }else{
                    $num=1;
                    }
                
            $fp = fopen($filename,"a");
                  fwrite($fp, $num."<>".$name."<>".$comment."<>".$date."<>".$color.PHP_EOL);
                  fclose($fp);
                  
                    //ファイルが存在する時
                    //(ファイルに入力されたデータをブラウザに表示)
                    
                    //$linesをファイルと定義する
                    $lines = file($filename,FILE_IGNORE_NEW_LINES);
                    //順序を逆にする
                    $lines= array_reverse($lines);
                        //配列を反復処理
                        foreach($lines as $line){
                            
                        //<>を区切りとし$lineを分解する
                        $return = explode("<>",$line);
                        //return[2]内の¥br¥を改行に変換
                        $return2 = str_replace("¥br¥","<br>", $return[2]);
                        //出力
                        echo"<FONT COLOR=\"$return[4] \">$return[0]:$return[1]    $return[3] <br>$return2 <br><hr size='1'></FONT>";
                        //<hr size=''>で区切り線を入れる 
                        
                        }
                    
        }elseif(count($error_message)){
            
            //配列を反復処理 
            foreach ($error_message as $message){
            print ($message);
            }
            echo "<hr size='1'>";
            
                //書き出し送信押す前の処理と全く同じ
                if(file_exists($filename)){
                 $lines = file($filename,FILE_IGNORE_NEW_LINES);
                $lines= array_reverse($lines);
                    foreach($lines as $line){
                    $return = explode("<>",$line);
                    $return2 = str_replace("¥br¥","<br>", $return[2]); 
                    echo"<FONT COLOR=\"$return[4] \">$return[0]:$return[1]    $return[3] <br>$return2 <br><hr size='1'></FONT>";
                    }
                }  
                
        }  
        
        //削除用の送信ボタンが押された時
    }elseif(isset($_POST ["e_submit"])){
        if(file_exists($filename)){
           $error_message2 =  array ();
            //削除対象番号が入力されて送信された時
            if ($_POST ["e_number"]!=="") {
            //データがセットされていたら各変数にPOSTのデータを格納
                $e_num = htmlspecialchars($_POST["e_number"],ENT_QUOTES);
                //変数にファイルの中身を代入
                //ファイルを開く前に代入しないと中身が消える
                $lines = file($filename,FILE_IGNORE_NEW_LINES);
                
                $numl= count($lines);
                       
                //削除対象番号が投稿番号より大きい時
                //||を使ってORを条件に組み込む
                if($e_num> $numl || $e_num <1){
                        
                   $error_message2[]= '<font color="Crimson">削除対象番号が存在しません。<br></font>';
                    
                }
                //新規書き込みで開く
                $fp_e= fopen( $filename,"w");
                
                
                //ファイルの数だけ繰り返し
                foreach($lines as $line){
                    
                    //<>を区切りとし$lineを分解する
                    $element = explode("<>",$line);
                    $postnum=$element[0];
                    //投稿番号と削除対象番号が一緒じゃない時ファイルに書き込む
                    if($e_num!=$postnum){
                        fwrite($fp_e, $element[0]."<>".$element[1]."<>".$element[2]."<>".$element[3]."<>".$element[4].PHP_EOL);
                    }
                    if($e_num==$postnum){
                        $element[4]="#999999";
                        fwrite($fp_e, $element[0]."<> <>----削除されたメッセージです----<>　<>".$element[4].PHP_EOL);
                    }
                    
                }
                fclose($fp_e);
                
                
               
                
                
            //各データがなかったらエラーメッセージを配列に格納
            }else{
                
              $error_message2[]= '<font color="Crimson">削除対象番号が入力されていません。<br></font>';
               
                 
                
            }
            if(!count($error_message2)){
            echo '<font color="teal">削除しました。<br></font>';
            }elseif(count($error_message2)){
            foreach ($error_message2 as $message2){
                print ($message2);
            }    
            }        //書き出し送信押す前の処理と全く同じ
                    if(file_exists($filename)){
                    echo "<hr size='1'>";
                     $lines = file($filename,FILE_IGNORE_NEW_LINES);
                    $lines= array_reverse($lines);
                        foreach($lines as $line){
                        $return = explode("<>",$line);
                        $return2 = str_replace("¥br¥","<br>", $return[2]); 
                        echo"<FONT COLOR=\"$return[4] \">$return[0]:$return[1]    $return[3] <br>$return2 <br><hr size='1'></FONT>";
                        }
                    }       
        
        }else{
            echo '<font color="Crimson">投稿がありません。<br></font>';
        }
    }else{
        //送信ボタンを押してない時に限定することで送信ボタンを押した後は表示しない(消える)
        if(file_exists($filename)){
        echo "<hr size='1'>";
         $lines = file($filename,FILE_IGNORE_NEW_LINES);
            //配列を反復処理
            //順序を逆にする
        $lines= array_reverse($lines);
            foreach($lines as $line){
            //<>を区切りとし$lineを分解する
            $return = explode("<>",$line);
            //return[2]内の¥br¥を改行に変換
            $return2 = str_replace("¥br¥","<br>", $return[2]);
            //出力
            //<hr size=''>で区切り線を入れる 
            echo"<FONT COLOR=\"$return[4] \">$return[0]:$return[1]    $return[3] <br>$return2 <br><hr size='1'></FONT>";
            }
        }
    }
    ?>
    
    
    
        
</body>
</html>
</html>