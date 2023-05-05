<?php
	usleep(50000);
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

	$db->Query("SELECT * FROM `users_stats` WHERE `uid` = $_SESSION[id]");		// сумма пополнения и вывода пользователя
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

		$db->Query("SELECT `day1`, `day2`, `day3`, `day4`, `day5` FROM `users_daily_bonus` WHERE `uid` = $_SESSION[id]");
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
								$step ++;
							}
						}
					}
				}
			}
			else {
				$step = 0;
			}
		}
		else {
			exit(header('Location: /my_cabinet'));
		}
	}
	else {
		exit(header('Location: /my_cabinet'));
	}
}
else {
	exit(header('Location: /my_cabinet'));
}

echo '
<div>
	<div class="container_days_streak">
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
	</div>
	<div class="container_history_bonus">
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
								'.$row['login'].'
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
echo '		
		</table>
	</div>
</div>';
?>