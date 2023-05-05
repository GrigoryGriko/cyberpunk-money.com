<?php
	usleep(50000);
echo '
	<div>
		<div id="button_login_change">';
		if ($_SESSION['fail_login'] >= 25) {	//если пользователь неудачно авторизовался 10 раз и более, то...
			echo '
			<button class="button_login" onclick="ajax_button_login_change_failsuccess(); field_captcha_show();">Войти в аккаунт</button>';
		}
		else {
			echo '
			<button class="button_login" onclick="ajax_button_login_change_failsuccess(); post_query(\'gform\', \'login\', \'Login_or_Email*+*password\');">
				Войти в аккаунт
			</button>';
		}
	echo '
	</div>
	';
?>