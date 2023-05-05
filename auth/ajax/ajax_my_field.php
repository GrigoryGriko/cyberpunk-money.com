<?php
	usleep(50000);
$db->Query("SELECT * FROM `users_amount_mine` WHERE `uid` = $_SESSION[id] ORDER BY `date_level_update` DESC, `date_buy` DESC");
$row_users_amount_mine = $db->NumRows();
if ( !empty($row_users_amount_mine) ) {
	/*$as_u_a_m = $assoc_users_amount_mine--------обозначения*/
	while ( $as_u_a_m = $db->FetchAssoc() ) {	/*Отображение рудников, купленных пользователем*/

//система накопления минералов---VVVV---
		if ( ( time() - strtotime($as_u_a_m['date_collection']) ) >= 1) {	/*если текущее время минус время последнего сбора больше либо равно 1 секунде, то..*/
			$as_u_a_m['keep_minerals'] = ( (time() - strtotime($as_u_a_m['date_collection']) ) * ($as_u_a_m['rate_mining'] + $as_u_a_m['bonus']) ) / $as_u_a_m['rate_seconds']; 
			$UPDATE_QUERY = $db->Query_recordless("UPDATE `users_amount_mine` SET `keep_minerals` = '$as_u_a_m[keep_minerals]' WHERE `id` = '$as_u_a_m[id]' AND `uid` = '$_SESSION[id]'");
			/*..то текущее время минус время сбора умножаем на доходность в секунду (доходность в минуту делим на 60), получаем накопленное количество минералов*/
			@mysqli_free_result($UPDATE_QUERY); //очистка пямяти от запроса
		}
//система накопления минералов---AAAA---
		switch ($as_u_a_m['category']) {
			case 1:
				$image_mineral = 'tourmaline.png';
				break;
			case 2:
				$image_mineral = 'topaz.png';
				break;
			case 3:
				$image_mineral = 'emerald.png';
				break;
			case 4:
				$image_mineral = 'diamond.png';
				break;
			default:
				$image_mineral = '';
				break;

		}

		echo '
					<li>
						<div class="fromsql_element-e1s6b1cp">

							<div class="fromsql_stroketext_1-e1s6b1cp">
								<p><span>'.$as_u_a_m['second_name'].'</span> "'.$as_u_a_m['first_name'].'"<br><span>уровень '.$as_u_a_m['level'].'</span></p>
							</div>
								<img class="fromsql_strokeimage-e1s6b1cp" src="../img/auth/my_field/'.$as_u_a_m['image_name'].'">
							<button class="fromsql_stroketext_2-e1s6b1cp">
								<img src="../img/auth/my_field/'.$image_mineral.'" width="20px" height="20px">
								'.round( ($as_u_a_m['keep_minerals'] + $as_u_a_m['archive_keep_minerals']) , 0 ).'
							</button>
							<a href="/mine?id='.$as_u_a_m['id'].'">
								<button class="fromsql_stroketext_3-e1s6b1cp">
									<img src="../img/auth/my_field/check.png" width="16px" height="16px">
									Проверить
								</button>
							</a>';
		if ($as_u_a_m['level'] < 7) {
			echo '
						<a href="/mine?id='.$as_u_a_m['id'].'">
							<button class="fromsql_stroketext_4-e1s6b1cp_1">
								<img src="../img/auth/my_field/lvl_up.png" width="16px" height="16px">
								LVL UP
							</button>
						</a>';
		}
		else {
			echo '
						<button class="fromsql_stroketext_4-e1s6b1cp_2">
							LVL MAX
						</button>';
		}

		echo '
						</div>						
					</li>';
	}
}
else {
	exit(header('Location: /my_field'));
}
?>