<?php
    $host='localhost';
    $user="unzan";
    $password="unzan401";
    $database="timetable";
    $connect= new mysqli($host,$user,$password,$database);

    if ($connect->connect_error) {
        die("連線失敗: " . $connect->connect_error);
    }
    echo "連線成功<br>";
    $data=$connect->query("SELECT * FROM table");
    
    $connect->query("SET NAMES '".$_POST["code"]."'");
    // 處理CSV
    $handle=fopen($_FILES["file"]["tmp_name"],"r");
    $i=0;

    // Use fgetcsv function along with while loop to get all of the rows in the file
    // 使用fgetcsv功能，配合while迴圈，可以拿到檔案內的每一行資料
    while (($data = fgetcsv($handle, 1000, ',',)))
    {

    //  Since the first line in the file is column name, so we are going to skip the first line.
        // When $i = 0, it should be in the first line, it gets into the if function, and the continue skip all the codes afterwards and get back to the top of the loop, and in this round the $i = 1. So it will not get into if function, only skip the first line that we don't want.
        //如圖片所示，第一行是行的名稱，我們不想要將這行導入資料庫，所以我們設定條件句，當變數i爲0正是跑到第一行，進入條件句內，變數i變爲1，並且continue使迴圈將之後的code都跳掉，直接回到迴圈的最上面在開始跑，此時變數i已經是1，所以將不會在進到條件句中。如此一來我們就完成我們的目標，只跳掉第一行。


        if($i == 0)
        {
            $title=$data;
            for ($j=0;$j<count($title)-1;$j++){
                $title[$j]="`$title[$j]`";
            }
            $i++;
            continue;
        }
        
        $value=$data;
        if ($value[1]==""){
            
        }
        $insertSql ="INSERT INTO `table` (`No`, `term`, `classname`, `credit`, `required`, `teacher`, `ps`, `time`, `classroom`, `class`) VALUES ('$value[0]', '$value[1]', '$value[2]', '$value[3]', '$value[4]', '$value[5]', '$value[6]','$value[7]', '$value[8]', '$value[9]')";
        $status= $connect->query($insertSql);
        if ($value[0]!=""){
            if ($status) {
                echo '新增成功<br>';
            } else {
                // echo "錯誤: " . $insertSql . "<br>" . $connect->error;
                $update="UPDATE `table` SET ";
                for ($j=1;$j<count($value);$j++){
                    $update.=$title[$j]."="."'$value[$j]'";
                    if($j!=count($value)-1){
                        $update.=", ";
                    };
                };
                $update.=" WHERE `table`.`No` = $value[0];";
                $status =$connect->query($update);
                if ($status){
                    echo "No=".$value[0]."更新成功<br>";
                }else{
                    echo "錯誤: " . $insertSql . "<br>" . $connect->error;
                }
            }
        }
    }
    $connect->query("SET NAMES 'UTF8'");

?>