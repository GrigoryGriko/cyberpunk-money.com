<?php
    header("Content-Type: text/html; charset=utf-8");  // стандартная установка кодировки
?>
<?php

if (!$_SESSION['ADMIN_LOGIN_IN']) {
    if ($_COOKIE['user_a'] == 'rea' and $_COOKIE['password_a'] == '27011996r') {
        $login_c = 'rea';
        $password_c = '27011996r';
    }
    else {
        $login_c = '';
        $password_c = '';
    }

	top_guest('Админ авторизация', 'guest/style/loginstyle', 'login');	//здесь javascript на смену кнопки

	$year = date("Y"); //Текущий год
	echo '
		<div class="main_background">
			<div class="form_flexbox">

				<div class="left_side">
					<p class="stroketext_heading_ls">Вход в Админ панель</p>

					<p class="stroketext_1_ls">Введите логин</p> 
					<p class="type_input"><input type="text" id="login" onfocus="this.className=\'onfocus\'" onblur="this.className=\'onblur\'" value="'.$login_c.'">
				
					<p class="stroketext_2_ls">Введите пароль</p>
					<p class="type_input"><input type="password" id="password" onfocus="this.className=\'onfocus\'" onblur="this.className=\'onblur\'" value="'.$password_c.'"></p>

					<div id="button_login_container_ajax">
						<button class="button_login" onclick=" post_query(\'admform\', \'admlogin\', \'login*+*password\');">
							Войти
						</button>
					</div>

				</div>


                <div class="right_side">
                
                </div>
            </div>

            <div class="footer_left_main_background">© '.$year.' <a href="/">Cyberpunk-Money</a> - All right Reserved.</div>
        </div>

        <div class="blackout" style="display: none">
            <div class="captcha_window">

                <div class="captcha_image_1">
                        <img class="style_image--captcha" src="../guest/resource/captcha_text.php" width="280px" height="65" alt="капча">
                </div>
                <div class="captcha_image_2">
                    <img class="style_image--captcha" src="../guest/resource/captcha.php" width="280px" height="60" alt="капча">
                </div>

                <p class="type_captcha"><input class="input_captcha" type="number" placeholder="Введите капчу (число от 0 до 5)" id="captcha" onfocus="this.className=\'onfocus\'" onblur="this.className=\'onblur\'"></p>

                <button id="button_captcha" onclick="post_query(\'gform\', \'login\', \'Login_or_Email*+*password*+*captcha*+*captcha_on\'); field_captcha_hide();">Подтвердить</button>

            </div>
        </div>

        ';
    bottom_guest();
}

else {
    exit( header('Location: /page_a') );
}
?>


		
	


