<?php
usleep(50000);
    header("Content-Type: text/html; charset=utf-8");  // стандартная установка кодировки
?>
<?php
if ($_POST['mineralscollect_f'] and $_POST['confirm'] == 1) {
	$indicator = 0;
	$db->Query("SELECT * FROM `users_amount_mine` WHERE `uid` = $_SESSION[id]");		//запись массива из конкретного запроса
		/*$assoc_u_a_m = $assoc_users_amount_mine--------обозначения*/
		$row_users_amount_mine = $db->NumRows();
	if ( !empty($row_users_amount_mine) ) {
		while ( $assoc_u_a_m = $db->FetchAssoc() ) {	//запись массива из конкретного запроса
			if ( (time() - strtotime($assoc_u_a_m['date_collection']) ) >= 600) {		//3600 временно из-за непонятной проблемы со временем
				switch($assoc_u_a_m['category']) {
					case 1:
						$ident_name_mineral = 'tourmaline';
						break;
					case 2:
						$ident_name_mineral = 'topaz';
						break;
					case 3:
						$ident_name_mineral = 'emerald';
						break;
					case 4:
						$ident_name_mineral = 'diamond';
						break;
					default:
						message('fatal error', false, false, 'error');
				}
				if ( isset($ident_name_mineral) ) {
					//PAGE_leadrace_payin_refresh();	//обновлять артефакты за лидерство

					$UPDATE_QUERY = $db->Query_recordless("UPDATE `users_data` SET `".$ident_name_mineral."` = (`".$ident_name_mineral."` + $assoc_u_a_m[keep_minerals] + $assoc_u_a_m[archive_keep_minerals]) WHERE `uid` = $_SESSION[id]");
					@mysqli_free_result($UPDATE_QUERY); //очистка пямяти от запроса
					
					$UPDATE_QUERY = $db->Query_recordless( "UPDATE `users_amount_mine` SET `keep_minerals` = 0, `archive_keep_minerals` = 0, `date_collection` = NOW() WHERE `id` = $assoc_u_a_m[id] AND `uid` = $_SESSION[id]" );
					@mysqli_free_result($UPDATE_QUERY); //очистка пямяти от запроса
					$indicator = 1;
				}
			}
		}
		switch($indicator) {
			case 1:
				message('Вы успешно собрали артефакты');
				break;
			case 0:
				message('Собирать артефакты можно 1 раз в 10 минут', false, false, 'info');
				break;
			default:
				message('Fatal error#FFx2', false, false, 'error');
		}
	}
	else {
		message('Fatal error#OUxSeeJey', false, false, 'error');
	}
}
else if ($_POST['collect_minerals_f'] and $_POST['id'] and $_POST['uid']) {
	$db->Query("SELECT * FROM `users_amount_mine` WHERE `id` = $_POST[id] AND `uid` = $_SESSION[id]");		//запись массива из конкретного запроса
		/*$assoc_u_a_m = $assoc_users_amount_mine--------обозначения*/
		$row_users_amount_mine = $db->NumRows();
	if ( !empty($row_users_amount_mine) ) {
		$assoc_u_a_m = $db->FetchAssoc();	//запись массива из конкретного запроса
		if ( (time() - strtotime($assoc_u_a_m['date_collection']) ) >= 600+3644 ) {		//3644 временно из-за непонятной проблемы со временем
			switch($assoc_u_a_m['category']) {
				case 1:
					$ident_name_mineral = 'tourmaline';
					break;
				case 2:
					$ident_name_mineral = 'topaz';
					break;
				case 3:
					$ident_name_mineral = 'emerald';
					break;
				case 4:
					$ident_name_mineral = 'diamond';
					break;
				default:
					message('fatal error', false, false, 'error');
			}
			if ( isset($ident_name_mineral) ) {
				//PAGE_leadrace_payin_refresh();	//обновлять артефакты за лидерство

				$db->Query("UPDATE `users_data` SET `".$ident_name_mineral."` = (`".$ident_name_mineral."` + $assoc_u_a_m[keep_minerals] + $assoc_u_a_m[archive_keep_minerals]) WHERE `uid` = $_SESSION[id]");
				
				$db->Query( "UPDATE `users_amount_mine` SET `keep_minerals` = 0, `archive_keep_minerals` = 0, `date_collection` = NOW() WHERE `id` = $assoc_u_a_m[id] AND `uid` = $_SESSION[id]" );

				message('Вы успешно собрали артефакты');
			}
		}
		else {
			message('Собирать артефакты можно 1 раз в 10 минут', false, false, 'info');
		}
	}
	else {
		message('Fatal error#OUxSeeJey', false, false, 'error');
	}
}
else if ($_POST['make_lvl_up_f'] and $_POST['id'] and $_POST['uid']) {
	$db->Query("SELECT * FROM `users_amount_mine` WHERE `id` = $_POST[id] AND `uid` = $_SESSION[id]");		//запись массива из конкретного запроса
	/*$assoc_u_a_m = $assoc_users_amount_mine--------обозначения*/
	$row_users_amount_mine = $db->NumRows();
	if ( !empty($row_users_amount_mine) ) {
		$assoc_u_a_m = $db->FetchAssoc();
		if ($assoc_u_a_m['level'] < 7) {
			$assoc_u_a_m_level_up = $assoc_u_a_m['level'] + 1;	//уровень персонажа на 1 выше от текущего

			$db->Query("SELECT * FROM `mine_in_shop` WHERE `category` = $assoc_u_a_m[category] AND `level` = ".$assoc_u_a_m_level_up."");		//данные персонажа следующего уровня
			$row_mine_in_shop = $db->NumRows();
			if ( !empty($row_mine_in_shop) ) {
				$assoc_mine_in_shop = $db->FetchAssoc();
				
				$db->Query("SELECT `balance_buy` FROM `users_data` WHERE `uid` = $_SESSION[id]");
				$row_users_data = $db->NumRows(); //извлекаем баланс пользователя
				if ( !empty($row_users_data) ) {
					$assoc_users_data = $db->FetchAssoc();

					if ($assoc_users_data['balance_buy'] >= $assoc_mine_in_shop['price']) {		//дополнить эту часть кода
						//PAGE_leadrace_payin_refresh();	//обновлять артефакты за лидерство
						
						$assoc_u_a_m['keep_minerals'] = ( (time() - strtotime($assoc_u_a_m['date_collection']) ) * ($assoc_u_a_m['rate_mining'] + $assoc_u_a_m['bonus']) ) / $assoc_u_a_m['rate_seconds']; 
						$db->Query("UPDATE `users_amount_mine` SET `keep_minerals` = $assoc_u_a_m[keep_minerals] WHERE `id` = $assoc_u_a_m[id] AND `uid` = $_SESSION[id]");

						$db->Query( "UPDATE `users_data` SET `balance_buy` = (`balance_buy` - $assoc_mine_in_shop[price]) WHERE `uid` = $_SESSION[id]" );			
						$db->Query( "UPDATE `users_amount_mine` SET `level` = ".$assoc_u_a_m_level_up.", `price` = $assoc_mine_in_shop[price], `bonus` = $assoc_mine_in_shop[bonus], `income` = $assoc_mine_in_shop[income], `rate_mining` = $assoc_mine_in_shop[rate_mining], `keep_minerals` = 0, `archive_keep_minerals` = $assoc_u_a_m[keep_minerals], `date_collection` = NOW(), `date_level_update` = NOW() WHERE `id` = $assoc_u_a_m[id] AND `uid` = $_SESSION[id]" );

						message('Вы успешно подняли уровень персонажа до '.$assoc_u_a_m_level_up.'-го');
					}
					else {
						message('Недостаточно средств для покупки', false, false, 'info');
					}
				}
				else {
					message('Fatal error', false, false, 'error');	
				}
			}
			else {
				message('Fatal error', false, false, 'error');
			}
		}
		else {
			message('Уровень вашего персонажа максимальный', false, false, 'info');	
		}
	}
	else {
		message('Fatal error', false, false, 'error');
	}
}
else {
	message('Fatal error#SZx1', false, false, 'error');
}
?>