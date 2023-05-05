<?php
    header("Content-Type: text/html; charset=utf-8");  // стандартная установка кодировки
?>
<?php

function go_auth($data) { //для бывалых
    global $db;
    global $login_vs_email;
    foreach ($data as $key => $value) {
        $_SESSION[$key] = $value;
    }

    /*$db->Query('INSERT INTO `logs_authorization` VALUES (NULL, "'.$_SESSION['id'].'", "'.$_POST['Login_or_Email'].'", "succes", NOW(), 1, "'.define_IP().'")'); лог при авторизации*/ 

	
	if ($login_vs_email == 'login') {
		setcookie('userl', $_POST['Login_or_Email'], strtotime('+30 days'), '/');
	    setcookie('user', $_POST['password'], strtotime('+30 days'), '/');
   	}

   	else if ($login_vs_email == 'email') {
		setcookie('userm', $_POST['Login_or_Email'], strtotime('+30 days'), '/');
	    setcookie('user', $_POST['password'], strtotime('+30 days'), '/');
	}
    //переменная для показа текста (вы успешно зарегистрировалиь, воспользуйтесь формой для входа)
    unset($_SESSION['reg_now']);

    go('my_cabinet');
} //связанная функция(1)
/*---------------------------------Авторизация---------------------------------------------------*/

if ($_POST['login_f']) { //система авторизаици/логинизация
    /*if ($_POST['captcha_on']) {
    	captcha_valid_login();
    }*/

    Login_or_Email_valid();
    password_valid('login');

    $_SESSION['fail_login'];

    if ($login_vs_email == 'login') {
        $db->Query("SELECT `login` FROM `users` WHERE `login` = '$_POST[Login_or_Email]'");
        $NumRows_login = $db->NumRows();    
        if ( empty($NumRows_login) ) {
            $_SESSION['fail_login'] += 1;
            //$db->Query('INSERT INTO `logs_authorization` VALUES (NULL, 0, "'.$_POST['Login_or_Email'].'", "Логин не существует", NOW(), 0, "'.define_IP().'")');
            message('Данный Логин не существует либо указан неверно', false, false, 'info');
        }
        else {
            $db->Query("SELECT `password` FROM `users` WHERE `login` = '$_POST[Login_or_Email]' AND `password` = '$_POST[password]'");
            $NumRows_password = $db->NumRows();

            if ( empty($NumRows_password) ) {
                $_SESSION['fail_login'] += 1;
                //$db->Query('INSERT INTO `logs_authorization` VALUES (NULL, 0, "'.$_POST['Login_or_Email'].'", "Неправильный пароль", NOW(), 0, "'.define_IP().'")');
                message('Неправильный пароль', false, false, 'info');
            }
            else {
                $_SESSION['USER_LOGIN_IN'] = 1;

                $db->Query("SELECT * FROM `users` WHERE `login` = '$_POST[Login_or_Email]'");
                $FetchAssoc = $db->FetchAssoc();    
                   	
               	go_auth($FetchAssoc); //связанная функция(1)            
            }               
        }
    }
    
    else if ($login_vs_email == 'email') {
        $db->Query("SELECT `email` FROM `users` WHERE `email` = '$_POST[Login_or_Email]'");
        $NumRows_email = $db->NumRows();    
        if ( empty($NumRows_email) ) {
            $_SESSION['fail_login'] += 1;
            //$db->Query('INSERT INTO `logs_authorization` VALUES (NULL, 0, "'.$_POST['Login_or_Email'].'", "Email не существует", NOW(), 0, "'.define_IP().'")');
            message('Данный E-mail не существует либо указан неверно', false, false, 'info');
        }
        else {
            $db->Query("SELECT `password` FROM `users` WHERE `email` = '$_POST[Login_or_Email]' AND `password` = '$_POST[password]'");
            $NumRows_password = $db->NumRows();

            if ( empty($NumRows_password) ) {
                $_SESSION['fail_login'] += 1;
                //$db->Query('INSERT INTO `logs_authorization` VALUES (NULL, 0, "'.$_POST['Login_or_Email'].'", "Неправильный пароль", NOW(), 0, "'.define_IP().'")');
                message('Неправильный пароль', false, false, 'info');
            }
            else {
               // $_SESSION['USER_LOGIN_IN'] = 1;

                $db->Query("SELECT * FROM `users` WHERE `email` = '$_POST[Login_or_Email]'");
            	$FetchAssoc = $db->FetchAssoc();    
                                                  
            	go_auth($FetchAssoc); //связанная функция(1)            
            }               
        }
    } 
    else {
        message('ErRoR_empty_lvse', false, false, 'error');
    }  
}
/*
else if ($_POST['buy_f']) {
	message('успешно');
}*/

else if ($_POST['rec_f']) { /*на recovery вообще ужас не работает почему-то, а с rec хоть немного. recovery_password*/ 

    Login_or_Email_valid();

    function post_mail_recovery () {
        global $db;
        global $_SERVER;

        $db->Query("SELECT * FROM `users` WHERE `login` = '$_POST[Login_or_Email]'");
        $NumRows_login = $db->NumRows();
        if ( !empty($NumRows_login) ) {
            $FetchAssoc = $db->FetchAssoc();

            $link_http_host = stristr($_SERVER['HTTP_REFERER'], '/', true ).'//'.$_SERVER['HTTP_HOST'];

            $pre_code = hash(sha256, time()).'_g'.$FetchAssoc['id'];
            $code_reset = hash(sha256, $pre_code);

            $db->Query('INSERT INTO `confirm_rest_password_users` VALUES (NULL, "'.$FetchAssoc['id'].'", "'.$FetchAssoc['email'].'", "'.$code_reset.'", NOW())');

            //добавлять в базу email, code и в будущем использовать эти данные для сброса

            //$FetchAssoc['email'] пока отправлять для теста на один адрес

            /*----------------------------VVVVVVVV---скрытие email звездочками---------VVVVVVVVVV-------------------------*/
            $string = $FetchAssoc['email'];  
            preg_match('/^.\K[a-zA-Z\.0-9]+(?=.@)/',$string,$matches);//here we are gathering this part bced

            $replacement= implode("",array_fill(0,strlen($matches[0]),"*"));//creating no. of *'s
            $hide_email = preg_replace('/^(.)'.preg_quote($matches[0])."/", '$1'.$replacement, $string);
            /*----------------------------AAAAAAAAAA ---скрытие email звездочками---------AAAAAAAAAAA-------------------------*/

            mail("".$FetchAssoc['email']."", 'Восстановление пароля на '.$_SERVER['HTTP_HOST'].'', "Для сброса пароля перейдите по ссылке: ".$link_http_host."/confirm?code=".$code_reset."");
            message('Ссылка для сброса пароля отправлена на '.$hide_email.', если письма нет, проверьте папку спам', false, false, 'succes');
            //проблема здесь
        }
        else if ( empty($NumRows_login) ) {
            $db->Query("SELECT * FROM `users` WHERE `email` = '$_POST[Login_or_Email]'");
            $NumRows_email = $db->NumRows();

            $FetchAssoc = $db->FetchAssoc();

            $link_http_host = stristr($_SERVER['HTTP_REFERER'], '/', true ).'//'.$_SERVER['HTTP_HOST'];

            $pre_code = hash(sha256, time()).'_g'.$FetchAssoc['id'];
            $code_reset = hash(sha256, $pre_code);

            $db->Query('INSERT INTO `confirm_rest_password_users` VALUES (NULL, "'.$FetchAssoc['id'].'", "'.$FetchAssoc['email'].'", "'.$code_reset.'", NOW())');

            //добавлять в базу email, code и в будущем использовать эти данные для сброса

            //$FetchAssoc['email'] пока отправлять для теста на один адрес

            /*----------------------------VVVVVVVV---скрытие email звездочками---------VVVVVVVVVV-------------------------*/
            $string = $FetchAssoc['email'];  
            preg_match('/^.\K[a-zA-Z\.0-9]+(?=.@)/',$string,$matches);//here we are gathering this part bced

            $replacement= implode("",array_fill(0,strlen($matches[0]),"*"));//creating no. of *'s
            $hide_email = preg_replace('/^(.)'.preg_quote($matches[0])."/", '$1'.$replacement, $string);
            /*----------------------------AAAAAAAAAA ---скрытие email звездочками---------AAAAAAAAAAA-------------------------*/

            mail("".$FetchAssoc['email']."", 'Восстановление пароля на '.$_SERVER['HTTP_HOST'].'', "Для сброса пароля перейдите по ссылке: ".$link_http_host."/confirm?code=".$code_reset."");
            message('Ссылка для сброса пароля отправлена на '.$hide_email.', если письма нет, проверьте папку спам', false, false, 'succes');
            //проблема здесь
        }
        else {
            message('Непредвиденная ошибка', false, false, 'error');
        }
    }

    if ($login_vs_email == 'login') {
        $db->Query("SELECT `login` FROM `users` WHERE `login` = '$_POST[Login_or_Email]'");
        $NumRows_login = $db->NumRows();    
        if ( empty($NumRows_login) ) {
            //$db->Query('INSERT INTO `logs_authorization` VALUES (NULL, 0, "'.$_POST['Login_or_Email'].'", "Логин не существует", NOW(), 0, "'.define_IP().'")');
            message('Данный Логин не существует либо указан неверно', false, false, 'info');
        }
        else {
            post_mail_recovery();                     
        }
    }
   
    else if ($login_vs_email == 'email') {
        $db->Query("SELECT `email` FROM `users` WHERE `email` = '$_POST[Login_or_Email]'");
        $NumRows_email = $db->NumRows();    
        if ( empty($NumRows_email) ) {
            $_SESSION['fail_login'] += 1;
            //$db->Query('INSERT INTO `logs_authorization` VALUES (NULL, 0, "'.$_POST['Login_or_Email'].'", "Email не существует", NOW(), 0, "'.define_IP().'")');
            message('Данный E-mail не существует либо указан неверно', false, false, 'info');
        }
        else {
            
            post_mail_recovery();             
        }
    } 
    else {
        message('ErRoR_empty_lvse', false, false, 'error');
    }
}

/*---------------------------------Авторизация---------------------------------------------------*/
/*---------------------------------Регистрация---------------------------------------------------*/
else if ($_POST['register_f']) { //система регистрации   
    //captcha_valid_logless();
	email_valid();
	password_valid('register');
	login_valid();

	$db->Query("SELECT `id` FROM `users` WHERE `email` = '$_POST[email]'");
	$NumRows_id_email = $db->NumRows();

	$db->Query("SELECT `id` FROM `users` WHERE `login` = '$_POST[login]'");
	$NumRows_id_login = $db->NumRows();

	
	if ( !empty($NumRows_id_login) ) {
		//$db->Query('INSERT INTO `logs_registration` VALUES (NULL, 0, "'.$_POST['email'].'", "Этот Логин занят", NOW(), 0, "'.define_IP().'")');
		message('Этот логин занят', false, false, 'warning');
	}
	else if ( !empty($NumRows_id_email) ) {
		//$db->Query('INSERT INTO `logs_registration` VALUES (NULL, 0, "'.$_POST['email'].'", "Этот E-mail занят", NOW(), 0, "'.define_IP().'")');
		message('Этот E-mail занят', false, false, 'warning');
	}
	if ($_POST['checkbox_1'] != 1 ) {
        //$db->Query('INSERT INTO `logs_registration` VALUES (NULL, 0, "'.$_POST['email'].'", "Не приняты правила проекта", NOW(), 0, "'.define_IP().'")');
		message('Примите правила проекта', false, false, 'warnnig');
	}

	else {

		if (is_numeric($_COOKIE['g'])) {
			$ref = $_COOKIE['g'];					/*Реферальная система*/
			
			$db->Query("SELECT `ref` FROM `users` WHERE `id` = '$_COOKIE[g]'");	//извлекаем id пользователя, под которым стоит пригласивший рерфер
        	$NumRows_ref_lvl_2 = $db->NumRows();    
        	if ( !empty($NumRows_ref_lvl_2) ) {
        		$assoc_ref_lvl_2 = $db->FetchAssoc();
        		$ref_lvl_2 = $assoc_ref_lvl_2['ref'];
        	}
		}
		else if (is_string($_COOKIE['g'])) {
			$db->Query("SELECT `id` FROM `users` WHERE `login` = '$_COOKIE[g]'");
			$NumRows_ref = $db->NumRows();    
        	if ( !empty($NumRows_ref) ) {
        		$assoc_ref = $db->FetchAssoc();
        		$ref = $assoc_ref['id'];
        	}

			$db->Query("SELECT `ref` FROM `users` WHERE `login` = '$_COOKIE[g]'");	//извлекаем по логину id пользователя, под которым стоит пригласивший рерфер
			$NumRows_ref_lvl_2 = $db->NumRows();    
        	if ( !empty($NumRows_ref_lvl_2) ) {
        		$assoc_ref_lvl_2 = $db->FetchAssoc();
        		$ref_lvl_2 = $assoc_ref['ref'];
        	}			
		}
		else {
			$ref = 1;
			$ref_lvl_2 = 0;
		}
        if ( !isset($_SESSION['origURL']) ) {
            $_SESSION['origURL']= 'нет данных';
        }
		$db->Query('INSERT INTO `users` VALUES (NULL, "'.$_POST['email'].'", "'.$_POST['password'].'", "", "'.$_POST['login'].'", "Без имени", "'.$ref.'", "'.$ref_lvl_2.'", NOW(), 0, 0, 0, 0, 0, 7, 3, 0.005, 5, 0, 0, 0, 0, "", "'.$_SESSION['origURL'].'")');

		$db->Query("SELECT `id` FROM `users` WHERE `email` = '$_POST[email]'");		//извлечение id для добавления новых таблиц пользователю
		$NumRows_users = $db->NumRows();
		if ( !empty($NumRows_users) ) {
			$assoc_users = $db->FetchAssoc();
			$db->Query('INSERT INTO `users_daily_bonus` VALUES (NULL, "'.$assoc_users['id'].'", 0, 0, 0, 0, 0, 0 )');
			$db->Query('INSERT INTO `users_data` VALUES (NULL, "'.$assoc_users['id'].'", 0, 0, 0, 0.1, 0, 0, 0, 0, "", 0, 0, 0)');
			$db->Query('INSERT INTO `users_stats` VALUES (NULL, "'.$assoc_users['id'].'", 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0)');
            $db->Query('INSERT INTO `users_amount_mine` VALUES (NULL, "'.$assoc_users['id'].'", 1, "Магматический", "рудник", "image_category1_element_2_stroke_3_box_2_center_panel.png", 10.00, 20.00, 2.00, 0.00, 60, 1, 0, 0, NOW(), NOW(), NOW(), 0)'); //бонус - магматический рудник

            $db->Query('INSERT INTO `parametres_free_chance` VALUES (NULL, "'.$assoc_users['id'].'", 0, 0, 0, 0, 0)');



/*---------------VVVVVVVVVVVVVVVV-----------Лог регистрации---------------------------*/

			//$db->Query('INSERT INTO `logs_registration` VALUES (NULL, "'.$assoc_users['id'].'", "'.$_POST['email'].'", "succes", NOW(), 1, "'.define_IP().'")');

/*-------------AAAAAAAAAAAAA-----------Лог регистрации--------------------------*/

			$_SESSION['reg_now'] = 1;	//переменная для показа текста (вы успешно зарегистрировалиь, воспользуйтесь формой для входа)
            setcookie('g', '', strtotime('-30 days'), '/');
            unset($_COOKIE['g']);
			go('login');
		}
		else {
			message('fatal_REQUESTreg001', false, false, 'error');
		}
	}


/*
	mail($_POST['email'], 'Регистрация', "Код подтверждения регистрации: $code", "GeologyMoney");


	go('confirm');
*/
}

/*---------------------------------Регистрация---------------------------------------------------*/

/*---------------------------------Восстановление---------------------------------------------------*/

/*else if ($_POST['recovery_f']) {
    /*if ($_POST['captcha_on']) {
        captcha_valid_login();
    }*/

    //Login_or_Email_valid();

 /*   function post_mail_recovery () {
		global $db;

        $db->Query("SELECT * FROM `users` WHERE `login` = '$_POST[Login_or_Email]'");
        $NumRows_login = $db->NumRows();
        if ( !empty($NumRows_login) ) {
		    $FetchAssoc = $db->FetchAssoc();

		    $link_http_host = stristr($_SERVER['HTTP_REFERER'], '/', true ).'//'.$_SERVER['HTTP_HOST'];

		    $code_reset = hash(sha256, time()).'_g'.$FetchAssoc['id'];

		    $db->Query('INSERT INTO `confirm_rest_password_users` VALUES (NULL, "'.$FetchAssoc['id'].'", "'.$code_reset.'", NOW())');

		    //добавлять в базу email, code и в будущем использовать эти данные для сброса

		    //$FetchAssoc['email'] пока отправлять для теста на один адрес
		    mail("griko1996@gmail.com", 'Восстановление пароля '.$_SERVER['HTTP_HOST'].'', "Для сброса пароля перейдите по ссылке: ".$link_http_host."/confirm?code=".$code_reset."");
		}
		else {
			message('Непредвиденная ошибка', false, false, 'error');
		}
    }*/

    /*if ($login_vs_email == 'login') {
        $db->Query("SELECT `login` FROM `users` WHERE `login` = '$_POST[Login_or_Email]'");
        $NumRows_login = $db->NumRows();    
        if ( empty($NumRows_login) ) {
            //$db->Query('INSERT INTO `logs_authorization` VALUES (NULL, 0, "'.$_POST['Login_or_Email'].'", "Логин не существует", NOW(), 0, "'.define_IP().'")');
            message('Данный Логин не существует либо указан неверно', false, false, 'info');
        }
        else {
            post_mail_recovery();                     
        }
    }
   
    else if ($login_vs_email == 'email') {
        $db->Query("SELECT `email` FROM `users` WHERE `email` = '$_POST[Login_or_Email]'");
        $NumRows_email = $db->NumRows();    
        if ( empty($NumRows_email) ) {
            $_SESSION['fail_login'] += 1;
            //$db->Query('INSERT INTO `logs_authorization` VALUES (NULL, 0, "'.$_POST['Login_or_Email'].'", "Email не существует", NOW(), 0, "'.define_IP().'")');
            message('Данный E-mail не существует либо указан неверно', false, false, 'info');
        }
        else {
            
            post_mail_recovery();             
        }
    } 
    else {
        message('ErRoR_empty_lvse', false, false, 'error');
    }
}*/


/*---------------------------------Восстановление---------------------------------------------------*/
/*---------------------------------Подтверждение---------------------------------------------------*/
/*---------------------------------Подтверждение(Регистрации)---------------------------------------------------*/

else if ($_POST['confirm_f']) {
	/*if ( $_SESSION['confirm']['type'] == 'register') {
		if ( !$_POST['code'] ) {
			message('Не указан код подтверждения');
		}
		else if ( $_SESSION['confirm']['code'] != $_POST['code'] ) {
			message('Код подтверждения регистрации указан неверно');
		}
		if (is_numeric($_COOKIE['g'])) {
			$ref = $_COOKIE['g'];					
		}
		else {
			$ref = 1;
		}
		mysqli_query( $CONNECT, 'INSERT INTO `users` VALUES (NULL, "'.$_SESSION['confirm']['email'].'", "'.$_SESSION['confirm']['password'].'", "", 0, '.$ref.', 0, 100, 0, 0, 0, 0, 0, 0, 0)' );
		unset($_SESSION['confirm']);

		go('login');
	}*/

/*---------------------------------Подтверждение(Регистрации)---------------------------------------------------*/
/*---------------------------------Подтверждение(Восстановления)---------------------------------------------------*/

	if ( $_SESSION['confirm']['type'] == 'recovery') {

		if ( $_SESSION['confirm']['code'] != $_POST['code'] ) {
			message('Код подтверждения регистрации указан неверно', false, false, 'info');
		}

		$newpass = random_str(10);
		mysqli_query($CONNECT, 'UPDATE `users` SET `password` = "'.md5($newpass).'" WHERE `email` = "'.$_SESSION['confirm']['email'].'"');
		unset($_SESSION['confirm']);
		message("Ваш новый пароль: $newpass", false, false, 'info');

	}

	else {
		not_found();
	}
}

?>