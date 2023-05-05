<?php
	usleep(50000);
    header("Content-Type: text/html; charset=utf-8");  // стандартная установка кодировки
?>
<?php
if ($_POST['moneyexchange_buy_f'] and $_POST['from_moneypayout_to_moneybuy']) {		//обмен баланса для вывода на баланс для покупок

	if ( !is_numeric($_POST['from_moneypayout_to_moneybuy']) ) {
		message('Укажите число', false, false, 'warning');
	}
	$_POST['from_moneypayout_to_moneybuy'] +=0;
	
	if ( strlen($_POST['from_moneypayout_to_moneybuy']) > 18 ) {
		message('Вы указали слишком большое число', false, false, 'warning');
	}

	else if ( $_POST['from_moneypayout_to_moneybuy'] <= 0 ) {
		message('Введите количество', false, false, 'warning');
	}

	$db->Query("SELECT `balance_withdrawal` FROM `users_data` WHERE `uid` = $_SESSION[id]");
	$row_users_data = $db->NumRows();
	if ( !empty($row_users_data) ) {
		$assoc_users_data = $db->FetchAssoc();
		if ($_POST['from_moneypayout_to_moneybuy'] < 1) {
			message('Минимальная сумма для обмена 1 руб.', false, false, 'info');
		}
		else {
			if ($_POST['from_moneypayout_to_moneybuy'] <= $assoc_users_data['balance_withdrawal']) {
				$db->Query("UPDATE `users_data` SET `balance_withdrawal` = (`balance_withdrawal` - $_POST[from_moneypayout_to_moneybuy]) WHERE `uid` = $_SESSION[id]");
				$db->Query("UPDATE `users_data` SET `balance_buy` = (`balance_buy` + $_POST[from_moneypayout_to_moneybuy]) WHERE `uid` = $_SESSION[id]");

				message('Вы успешно произвели обмен');
			}
			else if ($_POST['from_moneypayout_to_moneybuy'] > $assoc_users_data['balance_withdrawal']) {
				message('У вас недостаточно средств', false, false, 'info');
			}
		}
	}
	else {
		message('Ошибка', false, false, 'error');
	}
}
else if ($_POST['moneyexchange_ad_f'] and $_POST['from_moneypayout_to_moneyad']) {		//обмен баланса для вывода на баланс для покупок

	if ( !is_numeric($_POST['from_moneypayout_to_moneyad']) ) {
		message('Укажите число', false, false, 'warning');
	}
	$_POST['from_moneypayout_to_moneyad'] +=0;
	
	if ( strlen($_POST['from_moneypayout_to_moneyad']) > 18 ) {
		message('Вы указали слишком большое число', false, false, 'warning');
	}

	else if ( $_POST['from_moneypayout_to_moneyad'] <= 0 ) {
		message('Введите количество', false, false, 'warning');
	}

	$db->Query("SELECT `balance_withdrawal` FROM `users_data` WHERE `uid` = $_SESSION[id]");
	$row_users_data = $db->NumRows();
	if ( !empty($row_users_data) ) {
		$assoc_users_data = $db->FetchAssoc();
		if ($_POST['from_moneypayout_to_moneyad'] < 1) {
			message('Минимальная сумма для обмена 1 руб.', false, false, 'info');
		}
		else {
			if ($_POST['from_moneypayout_to_moneyad'] <= $assoc_users_data['balance_withdrawal']) {
				$db->Query("UPDATE `users_data` SET `balance_withdrawal` = (`balance_withdrawal` - $_POST[from_moneypayout_to_moneyad]) WHERE `uid` = $_SESSION[id]");
				$db->Query("UPDATE `users_data` SET `balance_advertising` = (`balance_advertising` + $_POST[from_moneypayout_to_moneyad]) WHERE `uid` = $_SESSION[id]");

				message('Вы успешно произвели обмен');
			}
			else if ($_POST['from_moneypayout_to_moneyad'] > $assoc_users_data['balance_withdrawal']) {
				message('У вас недостаточно средств', false, false, 'info');
			}
		}
	}
	else {
		message('Ошибка', false, false, 'error');
	}
}
else {
	message('Fatal error', false, false, 'error');
}
?>