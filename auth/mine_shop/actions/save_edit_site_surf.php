<?php
usleep(50000);
    header("Content-Type: text/html; charset=utf-8");  // стандартная установка кодировки
?>
<?php
if ($_POST['save_edit_site_surf_f']) {
	if (!$_POST['name_link']) {
		message('Напишите заголовок к ссылке', false, false, 'info');
	}
	if (!$_POST['url_site']) {
		message('Введите ссылку', false, false, 'info');
	}
	if ($_POST['max_count_views']) {
		if ( is_numeric($_POST['max_count_views']) ) {
			$_POST['max_count_views'] += 0; //превращаем переменную в число
			if ( $_POST['max_count_views'] <= 0 or $_POST['max_count_views'] > 9999999999999 ) {
				message('Введите допустимое число', false, false, 'warning');
			}
		}
		else if ( is_string($_POST['max_count_views']) ) {
			if (strlen($_POST['max_count_views'] > 200) ) {
				message('Некорректное значение макс. числа просмотров (не больше 100млн.)'.$_POST['max_count_views'].'', false, false, 'warning');
			}
		}
	}
	if (!$_POST['max_count_views']) {
		message('Поле макс. числа просмотров пустое', false, false, 'warning');
	}

	if ($_POST['checkbox_ad'] != 1) {
		message('Примите правила размещения рекламы', false, false, 'warning');
	}
	if ( substr($_POST['url_site'], 0, 7 ) != 'http://' and substr($_POST['url_site'], 0, 8 ) != 'https://' ) {
		message('Сайт должен начинаться с http:// или с https:// ', false, false, 'info');
	}

	if ( strlen($_POST['name_link']) > 80) {
		message('В описании должно быть не больше 80 символов', false, false, 'warning');
	}
	if ( strlen($_POST['url_site']) > 170) {
		message('количество символов в ссылке должно быть не больше 170', false, false, 'warning');
	}
	if ( !is_numeric($_POST['time_watch']) or $_POST['value_cost_watch'] > 200 ) {
		message('Ошибка времени просмотра', false, false, 'error');
	}
	if ( !is_numeric($_POST['value_cost_watch']) or $_POST['value_cost_watch'] > 200 ) {
		message('Ошибка стоимости просмотра', false, false, 'error');
	}
	else {
		$db->Query("SELECT `url_site` FROM `user_addsurf_sites` WHERE `id` != '$_POST[id_task]'");
		$NumRows_url_sites = $db->NumRows();
		if ( !empty($NumRows_url_sites) ) {
			while ( $_SESSION['assoc_count'] = $db->FetchAssoc() ) {
				if ($_POST['url_site'] == $_SESSION['assoc_count']['url_site']) {
					message('Данная ссылка уже была добавлена в сёрфинг', false, false, 'warning');
				}
			}
			unset($_SESSION['assoc_count']);
		}
		
    	$db->Query("SELECT `balance_advertising` FROM `users_data` WHERE `uid` = '$_SESSION[id]'");	
    	$NumRows_bal_adv = $db->NumRows();
		if ( !empty($NumRows_bal_adv) ) {
		    $assoc_bal_adv = $db->FetchAssoc();	

    		if ( ($assoc_bal_adv['balance_advertising'] < $_POST['value_cost_watch']) or $_POST['checkbox_enable'] == 0 ) {
    			$_SESSION['enable'] = 0;	
	    	}
		    else if ($_POST['checkbox_enable'] == 1) {
		    	$_SESSION['enable'] = 1;
			}
			if ($_POST['max_count_views'] == 'Неограничено просмотров') {
				$_POST['max_count_views'] = 9999999999999;
			}
			$db->Query("UPDATE `user_addsurf_sites` SET `name_link` = '$_POST[name_link]', `url_site` = '$_POST[url_site]', `time_watch` = '$_POST[time_watch]', `cost_watch` = '$_POST[value_cost_watch]', `max_count_views` = '$_POST[max_count_views]', `enable` = '$_SESSION[enable]' WHERE `id` = '$_POST[id_task]'");

			message('Изменения задания успешно сохранены');
			unset($_SESSION['enable']);
		}
	}
}
else {
	message('Ошибка 0x000adsr1', false, false, 'error');
}
?>