<?php
	usleep(50000);
    header("Content-Type: text/html; charset=utf-8");  // стандартная установка кодировки
?>
<?php

if ($_GET['code']) {
	$db->Query("SELECT `id`, `uid` FROM `confirm_rest_password_users` WHERE `code` = '$_GET[code]'");
	$numrows = $db->NumRows();
	if ( !empty($numrows) ) {
		$fetch_assoc = $db->FetchAssoc();

		$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
		$rand_gen_password = substr(str_shuffle($permitted_chars), 0, 10);
		$crypto_password = hash(sha256, $rand_gen_password);

		$db->Query("UPDATE `users` SET `password` = '$crypto_password' WHERE `id` = '$fetch_assoc[uid]'");

		$db->Query("DELETE FROM `confirm_rest_password_users` WHERE `code` = '$_GET[code]'");

		echo '<p>Ваш новый пароль: '.$rand_gen_password.'</p> <br>Пароль продублирован на почту, при необходимости вы можете сменить его в настройках аккаунта. <a href="/login">Войти в аккаунт</a>';

		mail("".$fetch_assoc['email']."", 'Новый пароль для '.$_SERVER['HTTP_HOST'].'', "Ваш новый пароль: ".$rand_gen_password." (Вы можете изменить его в настройках аккаунта)");
		//меняем пароль, отправляем его на почту, удаляем запись с кодом подтверждения
	}          
	else {
		echo 'Ссылка недействительна. <a href="/login">Странциа авторизации</a>';
	} 
}
else {
    header('location: /recovery_password');	
}

?>