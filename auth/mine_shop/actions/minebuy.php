<?php
usleep(50000);
    header("Content-Type: text/html; charset=utf-8");  // стандартная установка кодировки
?>
<?php

function minebuy_category ($id, $category) {
	
	global $db; // надо объявлять в функциях глобальные переменные, массивы записываются без скобок

	$db->Query("SELECT * FROM `mine_in_shop` WHERE `id` = $_POST[$id] AND `category` = $_POST[$category] AND `level` = 1");
	$row_mine_in_shop = $db->NumRows(); //извлекаем данные покупаемого товара
	if ( !empty($row_mine_in_shop) ) {		//если ряд в бд существует, то.. 
		$assoc_mine_in_shop = $db->FetchAssoc();	//..записываем данные предыдущего запроса в массив
	}

	$db->Query("SELECT `balance_buy` FROM `users_data` WHERE `uid` = $_SESSION[id]");
	$row_users_data = $db->NumRows(); //извлекаем баланс пользователя

	if ( !empty($assoc_mine_in_shop) and !empty($row_users_data) ) {	//если товар имется в бд и баланс пользователя существует, то..
	 	$row_users_data = $db->FetchAssoc();	//сохраняем в массив баланс пользователя
	 	if ( floatval($row_users_data['balance_buy']) >= floatval($assoc_mine_in_shop['price']) ) {		//если баланс больше либо равен цене товара, то..
	 		$db->Query("UPDATE `users_data` SET `balance_buy` = (`balance_buy` - $assoc_mine_in_shop[price]) WHERE `uid` = $_SESSION[id]"); //списывание денег с баланса
	 		
	 		$db->Query( 'INSERT INTO `users_amount_mine` VALUES( NULL, "'.$_SESSION['id'].'", "'.$assoc_mine_in_shop[category].'", "'.$assoc_mine_in_shop[first_name].'", "'.$assoc_mine_in_shop[second_name].'", "'.$assoc_mine_in_shop[image_name].'", "'.$assoc_mine_in_shop[price].'", "'.$assoc_mine_in_shop[income].'", "'.$assoc_mine_in_shop[rate_mining].'", "'.$assoc_mine_in_shop[bonus].'", "'.$assoc_mine_in_shop[rate_seconds].'", "'.$assoc_mine_in_shop[level].'", 0, 0, NOW(), NOW(), NOW(), 0)' ); //добавление товара пользователю в бд
	 		message('Вы приобрели '.$assoc_mine_in_shop['first_name'].' '.$assoc_mine_in_shop['second_name'].' '.$assoc_mine_in_shop['level'].'-го уровня');

	 	}
	 	else if ( floatval($row_users_data['balance_buy']) < floatval($assoc_mine_in_shop['price'])) {		//если баланс меньше цены товара, то..
	 		message('Недостаточно средств для покупки', false, false, 'warning');	
	 	}
	 	else {
	 		message('GREROR', false, false, 'error');
	 	}
	}
	else {
		message('Данный товар не существует', false, false, 'error');
	}
}
if ($_POST['minebuy1_f']) {		//если куплен товар по отправленным данным категории (в данном случае 1-й), то..
	minebuy_category ('id1', 'category1');	//выполняем функцию, извлекая данные товара 1-й категории
} 
else if ($_POST['minebuy2_f']) {
	minebuy_category ('id2', 'category2');
}
else if ($_POST['minebuy3_f']) {
	minebuy_category ('id3', 'category3');
} 
else if ($_POST['minebuy4_f']) {
	minebuy_category ('id4', 'category4');
} 
else {
	message('Fatal error', false, false, 'error');
}

?>