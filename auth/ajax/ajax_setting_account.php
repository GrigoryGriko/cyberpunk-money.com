<?php
	usleep(50000);
echo '
	<div>
		<div id="container_current_avatar">';


$db->Query("SELECT `upload_avatar`, `payment_password` FROM `users` WHERE `id` = '$_SESSION[id]'");					
$NumRows_users = $db->NumRows();
if ( !empty($NumRows_users) ) {
	$assoc_users = $db->FetchAssoc();

	if ($assoc_users['upload_avatar'] == 1) {
		$db->Query("SELECT * FROM `users_data` WHERE `uid` = '$_SESSION[id]'");					
		$NumRows_users_data = $db->NumRows();
		if ( !empty($NumRows_users_data) ) {
			$assoc_users_data = $db->FetchAssoc();

			if ( file_exists($assoc_users_data['name_image_avatar']) ) {
				echo '
							<img src="../'.$assoc_users_data['name_image_avatar'].'">';
			}
			else {
				echo '	
							<img src="../img/auth/home/avatar.png">';
			}
		}
		else {
			echo '	
							<img src="../img/auth/home/avatar.png">';
		}
	}
	else {
		echo '	
							<img src="../img/auth/home/avatar.png">';
	}
}
else {
	echo '	
							<img src="../img/auth/home/avatar.png">';
}

echo '
		</div>
		<div id="container_form_change_payment_password">';

if ( !$assoc_users['payment_password']) {
		echo '
			<div id="set_paypass" class="box_expansion">
				<h1>Платёжный пароль:</h1>
				<div class="box_input">
					<input class="input_write input_button_style" id="account_password" type="password" placeholder="Введите ваш пароль от аккаунта">
					<input class="input_write input_button_style" id="payment_password" type="password" placeholder="Введите платежный пароль">
					<input class="input_write input_button_style" id="confirm_payment_password" type="password" placeholder="Подтвердите платежный пароль">

					<div id="button_change_password_ajax">
						<button id="set_parametr_1" class="set_button_blue input_button_style" type="submit" onclick="ajax_button_change_password_failsuccess(); post_query(\'mine_shop/actions/request_setting_account\', \'set_payment_password\', \'account_password*+*payment_password*+*confirm_payment_password\');">
							<img id="setting_icon_button_2" class="icon_button" src="../img/auth/setting_account/setting_icon_button.png" style="display: block" width="15px" height="15px">
							Установить платежный пароль
						</button>
					</div>	
				</div>
			</div>';				
	}
	else {
		echo '
			<div id="reset_paypass" class="box_expansion">
				<h1>Платёжный пароль</h1>
				<p class="p_reset_paypass">
					<b>Платежный пароль уже установлен!</b> Если вы забыли пароль, то мы можем Сбросить
					его для Вас. Для этого нажмите на кнопку ниже, после чего на Ваш почтовый
					ящик поступит письмо с инструкцией по сбросу платёжного пароля.
				</p>
				<div class="box_input">
					<div id="button_change_password_ajax">
						<input id="confirm_request" type="hidden" value="1">

						<button id="set_parametr_1" class="set_button_blue input_button_style set_paypass" type="submit" onclick="post_query(\'mine_shop/actions/request_setting_account\', \'reset_payment_password\', \'confirm_request\');">
							<img id="setting_icon_button_2" class="icon_button" src="../img/auth/setting_account/set_paypass_icon_button.png" style="display: block" width="18px" height="13px">
							Сбросить платежный пароль
						</button>
					</div>	
				</div>
			</div>';
	}


echo '
		</div>
	</div>';
?>