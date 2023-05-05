<?php
usleep(50000);
    header("Content-Type: text/html; charset=utf-8");  // стандартная установка кодировки
?>
<?php
if ($_POST['getdailybonus_f'] and $_POST['confirm'] == 1) {

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

				if ( ($ty_y[1] == 0) and ($tm_m[1] == 0) and ($td_d[1] <= 0) ) {	//ограничение получения бонуса сутками
					message('Вы уже получили бонус, следующий будет сегодня в 00:00 по МСК', false, false, 'info');
				}
				else {
					if ($step == 5) {
						$step = 0;
					}
					$day = 'day'.($step+1).'';

					$assoc_d_d_b[$day][$sum_payin_categoty][1] = floatval( stristr($assoc_data_daily_bonus[$day][$sum_payin_categoty], '-', true) );	//floatval() - преобразует строку в десятичное число
					$assoc_d_d_b[$day][$sum_payin_categoty][2] = floatval( substr( stristr($assoc_data_daily_bonus[$day][$sum_payin_categoty], '-', false), 1 ) );

					$sum_dailybonus = rand( ($assoc_d_d_b[$day][$sum_payin_categoty][1] * 100), ($assoc_d_d_b[$day][$sum_payin_categoty][2] * 100) ) / 100;	//random_int (php 7)

					$db->Query("UPDATE `users_daily_bonus` SET `day5` = `day4`, `day4` = `day3`, `day3` = `day2`, `day2` = `day1`, `day1` = NOW() WHERE `uid` = $_SESSION[id]");
					$db->Query("UPDATE `users_data` SET `balance_buy` = (`balance_buy` + $sum_dailybonus) WHERE `uid` = $_SESSION[id]");
					$db->Query("UPDATE `users_stats` SET `getmoney_dailybonus` = (`getmoney_dailybonus` + $sum_dailybonus) WHERE `uid` = $_SESSION[id]");

					$db->Query('INSERT INTO `history_daily_bonus` VALUES (NULL, "'.$_SESSION['id'].'", "'.$_SESSION['login'].'", "'.$sum_dailybonus.'", NOW())');

					message('Вы получили бонус в размере '.$sum_dailybonus.' руб.');
				}
			}
			else {
				message('Fatal error#$31', false, false, 'error');
			}
		}
		else {
			message('Fatal error@^52', false, false, 'error');
		}
	}
	else {
		message('Fatal error0x003', false, false, 'error');
	}
}
else {
	message('Fatal error', false, false, 'error');
}
?>