<?php
	usleep(50000);
    header("Content-Type: text/html; charset=utf-8");  // стандартная установка кодировки
?>
<?php

function mineralssell_category ($amount_minerals, $category, $name_mineral) {

	$_POST[$category] += 0;
	$_POST[$amount_minerals] += 0;  //преобразование типа переменной из строковой в числовую

	if ( is_numeric($_POST[$amount_minerals]) and strlen($_POST[$amount_minerals]) <= 18 and $_POST[$amount_minerals] > 0 ) {

		global $db;
		global $users_data;

		$db->Query("SELECT `tourmaline`, `topaz`, `emerald`, `diamond` FROM `users_data` WHERE `uid` = $_SESSION[id]");
		$users_data = $db->NumRows();
		if ( !empty($users_data) ) { 
			$users_data = $db->FetchAssoc();
		}

		$db->Query("SELECT `category`, `price_mineral`, `for_count` FROM `data_mineral_to_sell` WHERE `category` = '$_POST[$category]'");
		$data_mineral_to_sell = $db->NumRows();
		if ( !empty($data_mineral_to_sell) ) {
			$data_mineral_to_sell = $db->FetchAssoc();
		}
	
		if ($_POST[$amount_minerals] <= $users_data[$name_mineral]) {
			$TOTAL_GET_MONEY = $_POST[$amount_minerals] * $data_mineral_to_sell['price_mineral'] / $data_mineral_to_sell['for_count'];

			$db->Query("UPDATE `users_data` SET `balance_withdrawal` = (`balance_withdrawal` + '".$TOTAL_GET_MONEY."'), `".$name_mineral."` = (`".$name_mineral."` - $_POST[$amount_minerals]) WHERE `uid` = $_SESSION[id]");
			$db->Query("UPDATE `users_stats` SET `money_earn_mine` = (`money_earn_mine` + '".$TOTAL_GET_MONEY."') WHERE `uid` = $_SESSION[id]");
			message('Вы успешно продали образцы');
		}
		else if ($_POST[$amount_minerals] > $users_data[$name_mineral]) {
			message('У вас недостаточно артефактов', false, false, 'info');
		}
	}
	else if ( strlen($_POST[$amount_minerals]) > 18 ) {
		message('Вы указали слишком большое число', false, false, 'warning');
	}
	else if ( !is_numeric($_POST[$amount_minerals]) ) {
		message('Укажите число', false, false, 'warning');
	}
	else if ( $_POST[$amount_minerals] <= 0 ) {
		message('Введите количество', false, false, 'warning');
	}
	else {
		message('Ошибка', false, false, 'error');
	}
}


if ($_POST['mineralssell_f']) {
	if ( isset($_POST['amount_minerals_1']) and $_POST['category_1']) {
		mineralssell_category ('amount_minerals_1', 'category_1', 'tourmaline');
	}
	else if ( isset($_POST['amount_minerals_2']) and $_POST['category_2']) {
		mineralssell_category ('amount_minerals_2', 'category_2', 'topaz');
	}
	else if ( isset($_POST['amount_minerals_3']) and $_POST['category_3']) {
		mineralssell_category ('amount_minerals_3', 'category_3', 'emerald');
	}
	else if ( isset($_POST['amount_minerals_4']) and $_POST['category_4']) {
		mineralssell_category ('amount_minerals_4', 'category_4', 'diamond');
	}
	else {
		message('fatal_error#13zss', false, false, 'error');
	}
}
else {
	message('fatal error#00hbo', false, false, 'error');
}
?>