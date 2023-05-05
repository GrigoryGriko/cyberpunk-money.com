<?php
    header("Content-Type: text/html; charset=utf-8");  // стандартная установка кодировки
?>
<?php
top_auth('Ежедневный бонус', 'auth/style/dailybonusstyle');
?>

<script type='text/javascript'>
	function ajax_dailybonus() {
		$.get("ajax/ajax_dailybonus", function(data) {	//функция получает данные data с файла по директории /ajax_my_field
			data = $(data);
			$("#container_nav_stroke_3_e1s3b1cp").html( $(".container_days_streak", data).html() );	//извлечение конкретных балансов из одного файла
			$(".div_table_e1s4b1c").html( $(".container_history_bonus", data).html() );

		});
	};
</script>

<?php
function day ($date) {
	$value = substr($date, 8, 2);
	return $value;
}
function mounth ($date) {
	$value = substr($date, 5, 2);
	return $value;
}
function year ($date) {
	$value = substr($date, 0, 4);
	return $value;
}


function hours_minutes ($date) {
	$value = substr($date, 11, 5);
	return $value;
}


$db->Query("SELECT * FROM `data_daily_bonus`");		//таблица бонусов
$row_data_daily_bonus = $db->NumRows();
if ( !empty($row_data_daily_bonus) ) {		
	while ( $row = $db->FetchAssoc() ) {
		$num = $row['id'];
		foreach ($row as $key => $value) {
			$assoc_data_daily_bonus[$key][$num] = $value; // $value = $row[$key]
		}
	}

	$db->Query("SELECT * FROM `users_stats` WHERE `uid` = '$_SESSION[id]'");		// сумма пополнения и вывода пользователя
	$row_users_stats = $db->NumRows();
	if ( !empty($row_users_stats) ) {
		$assoc_users_stats = $db->FetchAssoc();

		for ($i = 1; $i <= 3; $i++) {
			$assoc_d_d_b['sum_payin'][$i][1] = floatval( stristr($assoc_data_daily_bonus['sum_payin'][$i], '-', true) );	//floatval() - преобразует строку в десятичное число
			$assoc_d_d_b['sum_payin'][$i][2] = floatval( substr( stristr($assoc_data_daily_bonus['sum_payin'][$i], '-', false), 1 ) );
		}
		$assoc_d_d_b['sum_payin'][4] = floatval( substr( stristr($assoc_data_daily_bonus['sum_payin'][4], '>', false), 1 ) );

		if ($assoc_users_stats['money_payin'] >= $assoc_d_d_b['sum_payin'][1][1] and $assoc_users_stats['money_payin'] <= $assoc_d_d_b['sum_payin'][1][2]) {		//взять значения сумм пополнений из бд
			$sum_payin_categoty = 1;		//stristr() - возвращает вхождения до/после символа, substr() - обрезает 1-й символ "-"
		}
		else if ($assoc_users_stats['money_payin'] >= $assoc_d_d_b['sum_payin'][2][1] and $assoc_users_stats['money_payin'] <= $assoc_d_d_b['sum_payin'][2][2]) {
			$sum_payin_categoty = 2;	
		}
		else if ($assoc_users_stats['money_payin'] >= $assoc_d_d_b['sum_payin'][3][1] and $assoc_users_stats['money_payin'] <= $assoc_d_d_b['sum_payin'][3][2]) {
			$sum_payin_categoty = 3;
		}
		else if ($assoc_users_stats['money_payin'] >= $assoc_d_d_b['sum_payin'][4]) {
			$sum_payin_categoty = 4;
		}

		$db->Query("SELECT `day1`, `day2`, `day3`, `day4`, `day5` FROM `users_daily_bonus` WHERE `uid` = '$_SESSION[id]'");
		$row_user_daily_bonus = $db->NumRows(); //дни получения бонуса
		if ( !empty($row_user_daily_bonus) ) {		//если ряд в бд существует, то..

			$today_year = intval( year( date("Y-m-d") ) );	//сегодняшний день/год/месяц
			$today_mounth = intval( mounth( date("Y-m-d") ) );
			$today_day = intval( day( date("Y-m-d") ) );		//intval() - преобразует строку в целове число
			while ( $row = $db->FetchAssoc() ) {
				$num = 0;
				foreach ($row as $key => $value) {
					$num ++;	//нумерация дня

					$ty_y[$num] = $today_year - year($value);	//разница сегодняшнего дня/месяца/года и дня/месяца/года сбора бонуса
					$tm_m[$num] = intval( $today_mounth - mounth($value) );
					$td_d[$num] = $today_day - day($value);	
					// $value = $row[$key]
				}
			}

			if ( ($ty_y[1] == 0) and ($tm_m[1] == 0) and ($td_d[1] == 0 or $td_d[1] == 1) ) {	//наращивание дней при сборе (серия дней получения бонуса, максимум 5)
					$step = 1;
				
				if ( ($ty_y[2] == 0) and ($tm_m[2] == 0) and ( $td_d[2] == ($td_d[1] + 1) ) ) {
					$step ++;
					if ( ($ty_y[3] == 0) and ($tm_m[3] == 0) and ( $td_d[3] == ($td_d[1] + 2) ) ) {
						$step ++;
						if ( ($ty_y[4] == 0) and ($tm_m[4] == 0) and ( $td_d[4] == ($td_d[1] + 3) ) ) {
							$step ++;
							if ( ($ty_y[5] == 0) and ($tm_m[5] == 0) and ( $td_d[5] == ($td_d[1] + 4) ) ) {
								$step = 1;
							}
						}
					}
				}
			}
			else {
				$step = 0;
			}
		}
		else {/**/
		}
	}
	else {/**/
	}
}
else {/**/
}

echo '
	<div class="box_strokeafter_2_center_panel">
		<div class="box_1_center_panel">
			<div class="element_1_stroke_3_box_1_center_panel">
				<div class="text_e1s3b1cp">
					Каждый пользователь проекта имеет возможность получить ежедневный бонус, сумма этого
					бонуса напрямую зависит о суммы Ваших пополнений. Не забывайте получать бонус каждый
					день, ведь его сумма увеличивается. Точная сумма получаемого бонуса определяется
					случайным образом, бонус зачисляется на Ваш баланс для покупок.
				</div>

				<div class="title_s">Спонсоры бонуса:</div>

				<div id="linkslot_321369"><script src="https://linkslot.ru/bancode.php?id=321369" async></script></div>

				<center><a class="link_ad" href="https://linkslot.ru/link.php?id=321371" target="_blank" rel="noopener">Купить ссылку здесь за <span id="linprice_321371"></span> руб.</a><div id="linkslot_321371" style="margin: 10px 0;"><script src="https://linkslot.ru/lincode.php?id=321371" async></script></div></center>

				<div class="line_bord"></div>';
				
if ( isset($_SESSION['id']) ) {
	echo '
				

				<input type="hidden" id="confirm" value="1">
				<button class="button_getbonus_e1s3b1cp" onclick="post_query(\'mine_shop/actions/getdailybonus\', \'getdailybonus\', \'confirm\'); ajax_index_top_auth(); ajax_dailybonus();">
					<img src="../img/auth/dailybonus/icon_bonus.png" width="13" height="13">
					получить бонус
				</button>';
}
else {
	echo '
				<a href="/register"><button class="button_getbonus_e1s3b1cp">
					<img src="../img/auth/dailybonus/icon_bonus.png" width="13" height="13">
					получить бонус
				</button></a>';	
}
echo '
				<div id="container_nav_stroke_3_e1s3b1cp">

				<!--
			
			/*контейнер ajax-----VVVVVVVVVVVVV-----шкала сбора серий боунсов*/
		-->

				<table class="table_stroke_3_e1s3b1cp">
					<tr>';

//-------Счетчик серии дней сбора------VVVVVVVVVVVV

		if ($step == 0) {
		for ($num_2 = 1; $num_2 <= 5; $num_2 ++) {
				echo '
						
						<th>День '.$num_2.'</th>';
			} 
		}
		else {			
			for ($num_2 = 1; $num_2 <= $step; $num_2 ++) {
				echo '
						<th style="background: #81C784;">День '.$num_2.' <img src="../img/auth/dailybonus/ok.png" width="12" height="9"> </th>';
			}
			if ($step < 5) {
				for ( $num_3 = ($step + 1); $num_3 <= 5; $num_3 ++ ) {
					echo '
						<th>День '.$num_3.'</th>';
				}
			}
		}

//-------Счетчик серии дней сбора------AAAAAAAAAAAAA

echo '		
					</tr>
					<tr>';

				for ($num_4 = 1; $num_4 <= 5; $num_4 ++) {
					$day = 'day'.$num_4.'';
					echo '	
						<td>'.$assoc_data_daily_bonus[$day][$sum_payin_categoty].' руб.</td>';
				}

echo '
					</tr>
				</table>
					
			<!--
			
			/*контейнер ajax-----AAAAAAAAAAAA-----шкала сбора серий боунсов*/
			-->

				</div>

			</div>
			<div class="element_1_stroke_4_box_1_center_panel">
				<div class="title_e1s4b1c">
					<img src="../img/auth/dailybonus/icon_list.png" width="13" height="10">
					Последние 20 полученных бонусов
				</div>
				<div class ="div_table_e1s4b1c">
					<table class="table_e1s4b1c">
						<tr>
							<th>Логин Пользователя</th>
							<th>Сумма бонуса</th>
							<th>Время Получения</th>
						</tr>';

/*-------история получения бонусов----VVVVVVVVVVVVV-----*/

$db->Query("SELECT * FROM `history_daily_bonus` ORDER BY `date_get` DESC LIMIT 20");		
$row_history_daily_bonus = $db->NumRows();
if ( !empty($row_history_daily_bonus) ) {		
	while ( $row = $db->FetchAssoc() ) {

		$date_get = day($row['date_get']).'/'.mounth($row['date_get']).'/'.year($row['date_get']).' в '.hours_minutes($row['date_get']).'';
		

		echo
			'<tr>
				<td>
					<a href="/user_wall?id='.$row['uid'].'">'.$row['login'].'</a>
				</td>
				<td>
					'.$row['sum_dailybonus'].' руб.
				</td> 
				<td>
					'.$date_get.'	
				</td>
			</tr>';
	}
}
else {
	echo 'ни один бонус ещё не был получен';
}

/*-------история получения бонусов----AAAAAAAAAAAAAA-----*/

echo
					'</table>
				</div>
			</div>

		</div>
		<div class="box_2_center_panel">
			<div class="element_2_stroke_3_box_2_center_panel">
				<div class="title_e2s3b2c">
					<img src="../img/auth/dailybonus/icon_gift.png" width="13" height="18">
					Легенда бонуса
				</div>
				<div class="textabove_e2s3b2c">
					Сумма пополнений: '.$assoc_data_daily_bonus['sum_payin'][1].' руб.
				</div>
				<table class="tabletext_2-e2s3b2cp">';


				for ($i2 = 1; $i2 <= 5; $i2++) {
					$day_2 = 'day'.$i2.'';
					echo '
					<tr>
						<td>
							<div class="text1_table_element_1_stroke_4_box_1_center_panel">
								День '.$i2.': </div>
							<div class="text2_table_element_1_stroke_4_box_1_center_panel">
								'.$assoc_data_daily_bonus[$day_2][1].' руб.
							</div>
						</td>
					</tr>';	
				}
					

echo '
				</table>
				<div class="textabove_e2s3b2c">
					Сумма пополнений: '.$assoc_data_daily_bonus['sum_payin'][2].' руб.
				</div>
				<table class="tabletext_2-e2s3b2cp">';


				for ($i2 = 1; $i2 <= 5; $i2++) {
					$day_2 = 'day'.$i2.'';
					echo '
					<tr>
						<td>
							<div class="text1_table_element_1_stroke_4_box_1_center_panel">
								День '.$i2.': </div>
							<div class="text2_table_element_1_stroke_4_box_1_center_panel">
								'.$assoc_data_daily_bonus[$day_2][2].' руб.
							</div>
						</td>
					</tr>';	
				}
echo '
				</table>
				<div class="textabove_e2s3b2c">
					Сумма пополнений: '.$assoc_data_daily_bonus['sum_payin'][3].' руб.
				</div>
				<table class="tabletext_2-e2s3b2cp">';


				for ($i2 = 1; $i2 <= 5; $i2++) {
					$day_2 = 'day'.$i2.'';
					echo '
					<tr>
						<td>
							<div class="text1_table_element_1_stroke_4_box_1_center_panel">
								День '.$i2.': </div>
							<div class="text2_table_element_1_stroke_4_box_1_center_panel">
								'.$assoc_data_daily_bonus[$day_2][3].' руб.
							</div>
						</td>
					</tr>';	
				}
echo '
				</table>
				<div class="textabove_e2s3b2c">
					Сумма пополнений: '.$assoc_data_daily_bonus['sum_payin'][4].' руб.
				</div>
				<table class="tabletext_2-e2s3b2cp">';


				for ($i2 = 1; $i2 <= 5; $i2++) {
					$day_2 = 'day'.$i2.'';
					echo '
					<tr>
						<td>
							<div class="text1_table_element_1_stroke_4_box_1_center_panel">
								День '.$i2.': </div>
							<div class="text2_table_element_1_stroke_4_box_1_center_panel">
								'.$assoc_data_daily_bonus[$day_2][4].' руб.
							</div>
						</td>
					</tr>';	
				}
echo '
				</table>
			</div>
		</div>
	</div>
		';

bottom_auth('exist_footer', false);
?>