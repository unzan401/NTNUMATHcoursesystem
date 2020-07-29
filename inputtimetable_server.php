
<?php
if (is_user_logged_in()){
    if($_POST['term']) {
        $term=$_POST['term'];
    }elseif($_GET['term']){
        $term= $_GET['term'];
    }else{
        if (date("m")<7){
            $term= intval((string)(date("Y")-1912)."2");
        }else{
            $term= intval((string)(date("Y")-1911)."1");
        }
    };
    echo '<div id="coursesystem" style="display: flex;flex-wrap: wrap;flex-direction: row;"><div style="width:50%"><h2 style="margin:0">上傳開課資料</h2><form action="'.$_SERVER['PHP_SELF'].'?menu=import#coursesystem" method="post" enctype="multipart/form-data">檔案名稱:<input type="file" name="file" id="file" /><br />檔案編碼：<select name="code" style="width:100px"><option value="utf-8">UTF8</option></select><input type="submit" name="submit" value="上傳檔案" /></form></div><div style="width:50%"><h2 style="margin:20px 0 0 0">匯出開課資料</h2><form action="'.$_SERVER['PHP_SELF'].'#coursesystem" method="get"><input type="hidden" name="menu" value="export">學期：<input type="number" name="term" value="'.$term.'" style="max-width:80px"><input type="submit" name="submit" value="預覽" /><p><a href="https://github.com/unzan401/NTNUcoursesystem" target="_blank">開課暨課表系統使用說明</a></p></form></div></div>';
    
    if($_GET["menu"]=="import"){
        global $wpdb;
        $handle=fopen($_FILES["file"]["tmp_name"],"r");
        $i=0;
        $datalength=10;
        // Use fgetcsv function along with while loop to get all of the rows in the file
        // 使用fgetcsv功能，配合while迴圈，可以拿到檔案內的每一行資料
        while (($data = fgetcsv($handle, 1000, ',')))
        {

        //  Since the first line in the file is column name, so we are going to skip the first line.
            // When $i = 0, it should be in the first line, it gets into the if function, and the continue skip all the codes afterwards and get back to the top of the loop, and in this round the $i = 1. So it will not get into if function, only skip the first line that we don't want.
            //如圖片所示，第一行是行的名稱，我們不想要將這行導入資料庫，所以我們設定條件句，當變數i爲0正是跑到第一行，進入條件句內，變數i變爲1，並且continue使迴圈將之後的code都跳掉，直接回到迴圈的最上面在開始跑，此時變數i已經是1，所以將不會在進到條件句中。如此一來我們就完成我們的目標，只跳掉第一行。


            if($i == 0)
            {
                for ($j=0;$j<$datalength;$j++){
                    $title[$j]=mb_convert_encoding($data[$j],"utf-8",$_POST["code"]);
                }
                $i++;
                continue;
            }else{
                for ($j=0;$j<$datalength;$j++){
                    $value[$j]=mb_convert_encoding($data[$j],"utf-8",$_POST["code"]);
                }
            }

            if ($value[0]!=""){
                if ($value[1]==""){
                    $wpdb->query($wpdb->prepare("delete from timetable where timetable.No = ".$value[0]));
                    echo "No=".$value[0]."刪除成功<br>";
                }else{
                    $insertSql="insert into timetable (No,term,classname,credit,required,teacher,ps,time,classroom,class) values (";
                    for ($j=0;$j<$datalength;$j++){
                        $insertSql.="'".$value[$j]."'";
                        if($j!=$datalength-1){
                            $insertSql.=", ";
                        };
                    };
                    $insertSql.=")";
                    $insertSql =$wpdb->prepare($insertSql);
                    $status= $wpdb->query($insertSql);
                    if ($status) {
                        echo "No=".$value[0].'新增成功<br>';
                    } else {
                        // echo "錯誤: " . $insertSql . "<br>" . $wpdb->error;
                        $update="update timetable set ";
                        for ($j=1;$j<$datalength;$j++){
                            $update.=$title[$j]."='".$value[$j]."'";
                            if($j!=$datalength-1){
                                $update.=", ";
                            };
                        };
                        $update.=" where No =".$value[0];
                        $update=$wpdb->prepare($update);
                        $status =$wpdb->query($update);
                        if ($status){
                            echo "No=".$value[0]."更新成功<br>";
                        }else{
                            echo "No=".$value[0]."錯誤 資料未修改或檔案格式不正確"."<br>";
                        }
                    }
                }
            }
        }
        fclose($handle);
    };
    if($_GET["menu"]=="export"){
        echo "<input type='button' id='downloadcsvtext' value='下載資料' style='margin-left:50%'>";
        echo "<div class='exportcsvtext'>";
        global $wpdb;
        if ($_GET['term']){
            $term= $_GET['term'];
        }else{
            if (date("m")<7){
                $term= intval((string)(date("Y")-1912)."2");
            }else{
                $term= intval((string)(date("Y")-1911)."1");
            }
        };
        $filename=$term."學期".date('Ymd').".csv";
        
        ob_start();
        $output=fopen("php://output","w");
        fwrite($output, chr(0xEF).chr(0xBB).chr(0xBF));

        $result = $wpdb->get_results ( "SELECT * FROM timetable WHERE term =  $term" );
        fputcsv($output,array("No","term","classname","credit","required","teacher","ps","time","classroom","class"));
        foreach ($result as $class) {
            fputcsv($output,array($class->No,$class->term,$class->classname,$class->credit,$class->required,$class->teacher,$class->ps,$class->time,$class->classroom,$class->class));
        }

        ob_end_flush();
        echo "</div><script>var exportcsvtext= document.getElementsByClassName('exportcsvtext')[0].textContent;csvContent=URL.createObjectURL(new Blob([exportcsvtext],{type:'text/csv;charset=utf-8;'}));document.getElementById ('downloadcsvtext').onclick=function(){var a = document.createElement('a');a.download = '".$term."學期".date('md').".csv';a.href = csvContent;a.click();};</script>";

    };
};
?>