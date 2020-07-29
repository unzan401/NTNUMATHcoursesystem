<?php 
	global $term;
	if ($_GET['term']){
		$term= $_GET['term'];
	}else{
		if (date("m")<7){
			$term= intval((string)(date("Y")-1912)."2");
		}else{
			$term= intval((string)(date("Y")-1911)."1");
		}
	};
?>
<div class="oceanwp-custom-menu clr ocean_custom_menu-REPLACE_TO_ID left dropdown-hover" style="border-style:solid;border-color:#94142d; border-bottom-width:3px">
	<ul id="menu-in-timetable" class="dropdown-menu sf-menu sf-js-enabled" style="touch-action: pan-y;">
		<li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children dropdown">
			<a href="/math/index.php/courses/timetable/?id=4" style="font-size:16px" class="menu-link sf-with-ul">學年度及學期<span class="nav-arrow fa fa-angle-down"></span></a>
			<ul class="sub-menu" style="display: none;">
			<?php 
				global $wpdb;
				$result = $wpdb->get_results ( "SELECT DISTINCT term FROM timetable" );
				foreach ($result as $termo){
					$termlist=$termo->term;
					if ($termlist<=$term or is_user_logged_in()){ //檢查是否已經登入
						echo '<li class="menu-item menu-item-type-custom menu-item-object-custom"><a href="?term='.$termlist.'&id=4" class="menu-link">'.intval($termlist/10).'學年度第'.($termlist%10).'學期</a>';
					}
				}
				
			?>
			</ul>
		</li>
		<li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children dropdown">
			<a href="/math/index.php/courses/timetable/?id=4" style="font-size:16px" class="menu-link sf-with-ul">班級課表<span class="nav-arrow fa fa-angle-down"></span></a>
			<ul class="sub-menu" style="display: none;">
			<?php 
				$web=Array("calculus","1","2","3","4","M","D");
				$letter=Array("A","B","C");
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

				for ($i=0;$i<7;$i++){
					echo '<li class="menu-item menu-item-type-custom menu-item-object-custom"><a href="?term='.$term.'&id='.$web[$i].'" class="menu-link">'.$class[$web[$i]].'</a>';
					if ($i===1 or $i===2 or $i===3){
						echo '<ul class="sub-menu" style="display: none;">';
						for ($j=0;$j<3;$j++){
							echo '<li class="menu-item menu-item-type-custom menu-item-object-custom"><a href="?term='.$term.'&id='.$web[$i].$letter[$j].'" class="menu-link">'.$class[$web[$i].$letter[$j]].'</a></li>';
						};
						echo '</li></ul>';
					}
				}
			?>
			</ul>
		</li>
		<li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children dropdown">
			<a href="/math/index.php/courses/timetable/?term=<?php global $term; echo $term; ?>&type=r&id=M104" style="font-size:16px" class="menu-link sf-with-ul">教室課表 <span class="nav-arrow fa fa-angle-down"></span></a>
			<ul class="sub-menu" style="display: none;">
			<?php 
				$classroomList=Array("M104","M106","M210","M211","M212","M310","M311","M417");
				for ($i=0;$i<8;$i++){
					echo '<li class="menu-item menu-item-type-custom menu-item-object-custom"><a href="?term='.$term.'&type=r&id='.$classroomList[$i].'" class="menu-link">'.$classroomList[$i].'</a>';
				}
			?>
			</ul>
		</li>
		<li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children dropdown">
			<a href="/math/index.php/courses/timetable/?term=<?php global $term; echo $term; ?>&type=t&id=林俊吉" style="font-size:16px" class="menu-link sf-with-ul">教師課表 <span class="nav-arrow fa fa-angle-down"></span></a>
			<ul class="sub-menu" style="display: none;">
				<li class="menu-item menu-item-type-custom menu-item-object-custom">
					<a class="menu-link">專任教師</a>
					<ul class="sub-menu" style="display: none;">
					<?php 
						$result = $wpdb->get_results ( "SELECT name_tw FROM teacher WHERE time_tw='專任師資' " );  //抓專任師資的資料庫
						foreach ($result as $teachero){
							$teacherlist=$teachero->name_tw;
							if ($teacherlist){
								echo '<li class="menu-item menu-item-type-custom menu-item-object-custom"><a href="?term='.$term.'&type=t&id='.$teacherlist.'" class="menu-link">'.$teacherlist.'</a>';
							}
						};
					?>
					</ul>
				</li>	
				<li class="menu-item menu-item-type-custom menu-item-object-custom">
					<a class="menu-link">兼任教師</a>
					<ul class="sub-menu" style="display: none;">
					<?php 
						$result = $wpdb->get_results ( "SELECT name_tw FROM teacher WHERE time_tw like '%兼任%' " );  //抓兼任師資的資料庫
						foreach ($result as $teachero){
							$teacherlist=$teachero->name_tw;
							if ($teacherlist){
								echo '<li class="menu-item menu-item-type-custom menu-item-object-custom"><a href="?term='.$term.'&type=t&id='.$teacherlist.'" class="menu-link">'.$teacherlist.'</a>';
							}
						};
					?>
					</ul>
				</li>	
			</ul>
		</li>
	</ul>
</div>