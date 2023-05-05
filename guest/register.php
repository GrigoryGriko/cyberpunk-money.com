<?php

if (!$_SESSION['USER_LOGIN_IN']) {
	top_guest('Регистрация', 'guest/style/registerstyle');

	$year = date("Y"); //Текущий год
	echo '
		<div class="general_box">
			<div class="left_side">
				<div class="imgtext_stroke_1_left_side">
					<img src="../img/guest/register/image_background_left_side.png" width="403">
				
					<p class="text_stroke_2_left_side">Уже есть аккаунт? Авторизируйтесь</p>
					<a href="/login"><button class="button_login">Вход в аккаунт</button></a>
					<div class="footer_left_side">© '.$year.' <a href="/">Cyberpunk-Money</a> - All right reserved.</div>
				</div>
			</div>
			<div class="right_side">';
                
	echo '
                <div class="header_half"></div>
                <p class="text_stroke_1_right_side">Создание аккаунта</p>
                <div class="line"></div>
				<div class="form_input">
					<p class="type_input"><input type="text" placeholder="Введите желаемый Логин" id="login" onfocus="this.className=\'onfocus\'" onblur="this.className=\'onblur\'"></p>
					<p class="sub_type_input">Логин может состоять из латинских букв и цифр, тире</p>

					<p class="type_input"><input type="text" placeholder="Введите E-mail адрес" id="email" onfocus="this.className=\'onfocus\'" onblur="this.className=\'onblur\'"></p>
					<p class="sub_type_input">Во избежание неприятностей вводите существующий E-mail адрес</p>

					<div class="flexbox_type_password">	
						<p class="type_password"><input type="password" placeholder="Введите пароль" id="password" onfocus="this.className=\'onfocus\'" onblur="this.className=\'onblur\'">
							<span>От 6 до 30 символов</span>
						</p>

						<div class="block_dynamyc_distance"></div>

						<p class="type_password"><input type="password" placeholder="Повторите пароль" id="retype_password" onfocus="this.className=\'onfocus\'" onblur="this.className=\'onblur\'">
							<span>Повторите введенный пароль<span>
						</p>
					</div>

					<!-- <div class="captcha_image_1">
	               		<img class="style_image--captcha" src="../guest/resource/captcha_text.php" width="280px" height="65" alt="капча">
	               	</div>
	                <div class="captcha_image_2">
	                	<img class="style_image--captcha" src="../guest/resource/captcha.php" width="280px" height="60" alt="капча">
	                </div>

					<p class="type_captcha"><input class="input_captcha" type="number" placeholder="Введите капчу (число от 0 до 5)" id="captcha" onfocus="this.className=\'onfocus\'" onblur="this.className=\'onblur\'"></p> -->
					

					<p class="checkbox_text"><input type="checkbox" id="checkbox_1" value="0">Я согласен с<a href="/rules">&nbsp;правилами проекта&nbsp;</a>и не имею других аккаунтов в проекте</p>

					<button class="button_register" onclick="post_query(\'gform\', \'register\', \'login*+*email*+*password*+*retype_password*+*checkbox_1*+*captcha\')">Создать аккаунт</button>

				</div>';
				
	/*echo ' НА САЙТЕ ИДУТ ТЕХНИЧЕСКИЕ РАБОТЫ, РЕГИСТРАЦИЯ ВРЕМЕННО ПРИОСТАНОВЛЕНА*/

echo '
			</div>
		</div>
	';
	bottom_guest();

}

else {
	exit('Непредвиденная ошибка');
}
?>


		
	


