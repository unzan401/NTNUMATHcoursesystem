    <?php
        echo '<style>.table td,.table th {
                    vertical-align: middle;
                    border:0.5px #aaaaaa solid!important;
                    font-size:1.2rem;
                    line-height:2.2rem;
                }
                .table, .table th, .table td {text-align:center};

            </style>';
        global $wpdb;
        $time=Array("07:10 - 08:00","08:10 - 09:00","09:10 - 10:00","10:20 - 11:10","11:20 - 12:10","12:20 - 13:10","13:20 - 14:10","14:20 - 15:10","15:30 - 16:20","16:30 - 17:20","17:30 - 18:20","18:40 - 19:30","19:35 - 20:25","20:30 - 21:20");
        $class=Array(
            "calculus"=>"校基礎微積分",
            "1"=>"一年級",
            "2"=>"二年級",
            "3"=>"三年級",
            "4"=>"四年級",
            "1A"=>"一年甲班",
            "1B"=>"一年乙班",
            "1C"=>"一年丙班",
            "2A"=>"二年甲班",
            "2B"=>"二年乙班",
            "2C"=>"二年丙班",
            "3A"=>"三年甲班",
            "3B"=>"三年乙班",
            "3C"=>"三年丙班",
            "4A"=>"四年甲班",
            "4B"=>"四年乙班",
            "4C"=>"四年丙班",
            "M"=>"碩士班",
            "D"=>"博士班"
        );
        $type=$_GET['type']; //false=class t=teacher r=classroom
        if ($_GET['id']){
            $id=$_GET['id'];
        }else{
            $id=4;
        }
        if ($_GET['term']){
            $term= $_GET['term'];
        }else{
            if (date("m")<7){
                $term= intval((string)(date("Y")-1912)."2");
            }else{
                $term= intval((string)(date("Y")-1911)."1");
            }
        };

        
        
        function recallname($type, $id, $class){
            switch ($type){
                case "t":
                    echo $id."老師課表";
                break;
                case "r":
                    echo $id."課表";
                break;
                default:
                    echo $class[$id]."課表";}
        };

		function recallClass($db,$term,$week, $num, $type, $id){
            $result = $db->get_results ( "SELECT * FROM timetable WHERE term =  $term" );
            switch ($week){
                case 1:
                    $week="/一";
                    break;
                case 2:
                    $week="/二";
                    break;	
                case 3:
                    $week="/三";
                    break;
                case 4:
                    $week="/四";
                    break;
                case 5:
                    $week="/五";
                    break;
            };
            switch ($type){
                case "t":
                    foreach ($result as $class) {
                        if (preg_match($week.$num."(?!0)/",$class->time)){
                            if (preg_match("/".$id."/",$class->teacher)){ 
                                echo "<b>".$class->classname."</b><br>".$class->teacher." ".$class->classroom."<br>";
                            }
                        }
                    };
                break;
                case "r":
                    foreach ($result as $class) {
                        if (preg_match($week.$num."(?!0)/",$class->time)){
                            if (preg_match("/".$id."/",$class->classroom)){ 
                                echo "<b>".$class->classname."</b><br>".$class->teacher." ".$class->classroom."<br>";
                            }
                        }
                    }
                break;
                default:
                    foreach ($result as $class) {
                        if (preg_match($week.$num."(?!0)/",$class->time)){
                            if (preg_match("/".$id."/",$class->class) or (substr($id,0,1)===$class->class)){ 
                                echo "<b>".$class->classname."</b><br>".$class->teacher." ".$class->classroom."<br>";
                            }
                        }
                    };
 
            }
        };

        echo '<h3 style="text-align:center">';
        echo intval($term/10);
        echo "學年度第";
        echo $term%10;
        echo "學期";
        echo recallname($type,$id,$class);
        echo '</h3>
        <table  class="table table-striped table-bordered" style="text-align:center;page-break-after:always">
		<thead class="thead-dark">
			<tr>
			<th scope="col">#</th>
			<th scope="col">星期一 MON</th>
			<th scope="col">星期二 TUE</th>
			<th scope="col">星期三 WED</th>
			<th scope="col">星期四 THU</th>
			<th scope="col">星期五 FRI</th>
			</tr>
		</thead>
		<tbody>';
			for ($j=1;$j<14;$j++){
				$jn=$j;
				if ($jn==11){
					$jn="A";
				}elseif ($jn==12){
					$jn="B";
				}elseif ($jn==13){
					$jn="C";
				};
				echo "<tr><th scope='row' style='line-height:1.5rem'>".$jn."<br><br>".$time[$j]."</th>";
				for ($i=1;$i<6;$i++){
                    echo "<td>";
                    recallClass( $wpdb, $term, $i, $jn, $type, $id);
					echo "</td>";
				}
				echo "</tr>";

			}
			echo "</tbody>
			</table>";
	?>
