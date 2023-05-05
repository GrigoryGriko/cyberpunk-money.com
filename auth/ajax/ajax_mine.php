<?php
	usleep(50000);
global $db;
$db->Query("SELECT * FROM `users_amount_mine` WHERE `uid` = '$_SESSION[id]' AND `id` = '$_SESSION[GET_id]'");
$row_users_amount_mine = $db->NumRows();	
if ( !empty($row_users_amount_mine) ) {
	$assoc_u_a_m = $db->FetchAssoc();

	if ( ( time() - strtotime($assoc_u_a_m['date_collection']) ) >= 1) {	/*если текущее время минус время последнего сбора больше либо равно 1 секунде, то..*/
		$assoc_u_a_m['keep_minerals'] = ( (time() - strtotime($assoc_u_a_m['date_collection']) ) * ($assoc_u_a_m['rate_mining'] + $assoc_u_a_m['bonus']) ) / $assoc_u_a_m['rate_seconds']; 
		$db->Query("UPDATE `users_amount_mine` SET `keep_minerals` = $assoc_u_a_m[keep_minerals] WHERE `id` = $assoc_u_a_m[id]");
		/*..то текущее время минус время сбора умножаем на доходность в секунду (доходность в минуту делим на 60), получаем накопленное количество минералов*/
	}
	
	$db->Query("SELECT * FROM `mine_in_shop` WHERE `category` = '$assoc_u_a_m[category]' ");
	$row_mine_in_shop = $db->NumRows();	
	if ( !empty($row_mine_in_shop) ) {
		while ( $row = $db->FetchAssoc() ) {
			$num_level = $row['level'];
			foreach ($row as $key => $value) {
				$assoc_m_i_s[$key][$num_level] = $value; // $value = $row[$key]	//данные рудников всех уровней одной категории
			}
		}
		if ($assoc_u_a_m['level'] < 7) {
			$level_up = $assoc_u_a_m['level'] + 1;
		}
		else $level_up = 0;

		echo '
			<div>
				<div class="container_name_mine">	
					Персонаж "'.$assoc_u_a_m['first_name'].'", Уровень '.$assoc_u_a_m['level'].'
				</div>
				<div class="container_ready_collect_minerals">	
					'.round( ($assoc_u_a_m['keep_minerals'] + $assoc_u_a_m['archive_keep_minerals']) , 0 ).' шт.
				</div>
				<div class="container_level_mine">
					'.$assoc_u_a_m['level'].'
				</div>
				<div class="container_next_level_mine">
					'.($assoc_m_i_s['level'][$assoc_u_a_m['level']] + 1).'
				</div>
				<div class="container_rate_mining">
					'.round( ($assoc_m_i_s['rate_mining'][$assoc_u_a_m['level']] + $assoc_m_i_s['bonus'][$assoc_u_a_m['level']]), 0 ).' / мин
				</div>
				<div class="container_price">
					'.round($assoc_m_i_s['price'][$level_up], 0).'
				</div>

				<div class="container_info_level_up">';

					if ($assoc_u_a_m['level'] < 7) {
						echo '
							<div class="text1_element_1_stroke_4_box_2_center_panel">
								Стоимость перехода на '.$level_up.' уровень: </div>
							<div class="text2_element_1_stroke_4_box_2_center_panel">


								'.round($assoc_m_i_s['price'][$level_up], 0).' рублей

							</div>

							<div class="flexbox_stretch"></div>

							<button id="levelup_button" class="button_1_element_1_stroke_4_box_2_center_panel" onclick="post_query(\'mine_shop/actions/mineralscollect\', \'make_lvl_up\', \'id*+*uid\'); ajax_index_top_auth(); ajax_mine2();">
								<div class="square_left_img">
		                            <img id="levelup_button_01" class="img_button_1e1s4b2cp" src="../img/auth/mine/icon_button_lvl_up.png" width="16px" height="16px">
		                            <img id="levelup_button_02" style="display: none" class="img_button_1e1s4b2cp" src="../img/auth/mine/icon_button_lvl_up_02.png" width="16px" height="16px">
		                        </div>	
								<p>поднять уровень</p>
							</button>';
					}		
					else {
						echo '	
							<div class="text1_element_1_stroke_4_box_2_center_panel">
								Уровень этого персонажа максимальный
							</div>';
					}
			echo '	
				</div>
			</div>
			';
		}
}
else {
	exit(header('Location: /my_field'));	
}
?>