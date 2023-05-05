<?php
    usleep(50000);
    header("Content-Type: text/html; charset=utf-8");  // стандартная установка кодировки
?>
<?php
if ($_POST['confirm_account_f']) {
    if ($_POST['request'] == 1) {
   
        $db->Query("SELECT * FROM `users` WHERE `id` = '$_SESSION[id]' AND `isCONFIRM_email` = 0");
        $NumRows = $db->NumRows();
        if ( !empty($NumRows) ) {
            $FetchAssoc = $db->FetchAssoc();

            $link_http_host = stristr($_SERVER['HTTP_REFERER'], '/', true ).'//'.$_SERVER['HTTP_HOST'];

            $pre_code = hash(sha256, time()).'_g'.$FetchAssoc['id'];
            $code_confirm = hash(sha256, $pre_code);

            $db->Query('INSERT INTO `confirm_email_account` VALUES (NULL, "'.$FetchAssoc['id'].'", "'.$FetchAssoc['email'].'", "'.$code_confirm.'", NOW())');

            mail("".$FetchAssoc['email']."", 'Подтверждение аккаунта на '.$_SERVER['HTTP_HOST'].'', "Для подтверждения аккаунта перейдите по ссылке: ".$link_http_host."/allconfirm?code=".$code_confirm."");
            message('Письмо с сылкой для подтверждения отправлено на ваш email');
        }
        else {
            message('Пользователя не существует.', false, false, 'warning');
        }
    }
    else {
        message('Пользователь уже подтвержден (если нет, обратитесь в техподдержку).', false, false, 'warning');
    }
}
if ($_POST['set_name_f']) {
    if ($_POST['user_name']) {
        if ( mb_strlen($_POST['user_name']) > 30) {
            message('Имя должно содержать не более 30 символов', false, false, 'warning');
        }
        else {
            $db->Query("UPDATE `users` SET `Name` = '$_POST[user_name]' WHERE `id` = '$_SESSION[id]'");
            message('Вы установили себе имя');
        }
    }
    else {
        $db->Query("UPDATE `users` SET `Name` = 'Без имени' WHERE `id` = '$_SESSION[id]'");
        message('Вы сбросили своё имя');
    }
}
else if ($_POST['change_password_f']) {
    if ($_POST['captcha_on']) {
        captcha_valid_login();
    }
    if ($_POST['current_password']) { 
        if ($_POST['new_password']) {
            if ($_POST['confirm_password']) {
                if (!preg_match('/^[A-z0-9]{6,30}$/', $_POST['new_password'])) {
                    message('Неправильно указан новый пароль, он должен содержать от 6 до 30 латинских символов и/или цифр', false, false, 'info');
                }
                else {
                    $_POST['current_password'] = hash(sha256, $_POST['current_password']);

                    $db->Query("SELECT `password` FROM `users` WHERE `id` = '$_SESSION[id]' AND `password` = '$_POST[current_password]'");
                    $NumRows_password = $db->NumRows();

                    if ( empty($NumRows_password) ) {
                        $_SESSION['fail_change_password'] += 1; //для вывода капчи
                        message('Текущий пароль неверный', false, false, 'info');
                    }

                    else if ($_POST['new_password'] != $_POST['confirm_password']) {
                        $_SESSION['fail_change_password'] += 1; //для вывода капчи
                        message('Пароли не совпадают', false, false, 'info');
                    }

                    $_POST['new_password'] = hash(sha256, $_POST['new_password']);
                    $db->Query("SELECT `password` FROM `users` WHERE `id` = '$_SESSION[id]' AND `password` = '$_POST[new_password]'");
                    $NumRows_password_2 = $db->NumRows();

                    if ( !empty($NumRows_password_2) ) {
                        $_SESSION['fail_change_password'] += 1; //для вывода капчи
                        message('Новый пароль совпадает с текущим', false, false, 'info');
                    }
                    else {
                        $db->Query("UPDATE `users` SET `password` = '$_POST[new_password]' WHERE `id` = '$_SESSION[id]'");
                        message('Вы успешно сменили пароль');
                    }
                }
            }            
            else {
                message('Подтвердите новый пароль', false, false, 'info');
            }
        }
        else {
            message('Введите Ваш новый пароль', false, false, 'info');    
        }
    }
    else {
        message('Введите Ваш актуальный пароль', false, false, 'info');    
    }
}
else if ($_POST['set_payment_password_f']) {
    if ($_POST['account_password']) {
        if ($_POST['payment_password']) {
            if ($_POST['confirm_payment_password']) {
                if (!preg_match('/^[A-z0-9]{6,30}$/', $_POST['payment_password'])) {
                    message('Неправильно указан платежный пароль, он должен содержать от 6 до 30 латинских символов и/или цифр', false, false, 'info');
                }
                else {
                    $_POST['account_password'] = hash(sha256, $_POST['account_password']);

                    $db->Query("SELECT `id` FROM `users` WHERE `id` = '$_SESSION[id]' AND `password` = '$_POST[account_password]' AND `payment_password` = ''");
                    $NumRows_chan_pas = $db->NumRows();

                    if ( !empty($NumRows_chan_pas) ) {

                        $db->Query("SELECT `password` FROM `users` WHERE `id` = '$_SESSION[id]' AND `password` = '$_POST[account_password]'");
                        $NumRows_password = $db->NumRows();

                        if ( empty($NumRows_password) ) {
                            message('Пароль от аккаунта неверный', false, false, 'info');
                        }

                        else if ($_POST['payment_password'] != $_POST['confirm_payment_password']) {
                            message('Платежные пароли не совпадают', false, false, 'info');
                        }

                        $_POST['payment_password'] = hash(sha256, $_POST['payment_password']);
                        $db->Query("SELECT `password` FROM `users` WHERE `id` = '$_SESSION[id]' AND `password` = '$_POST[payment_password]'");
                        $NumRows_password_3 = $db->NumRows();

                        if ( !empty($NumRows_password_3) ) {
                            message('Платежный пароль совпадает с паролем от аккаунта', false, false, 'info');
                        }

                        $db->Query("SELECT `password` FROM `users` WHERE `id` = '$_SESSION[id]' AND `payment_password` = '$_POST[payment_password]'");
                        $NumRows_password_4 = $db->NumRows();

                        if ( !empty($NumRows_password_4) ) {
                            message('Такой платежный пароль уже был установлен', false, false, 'info');
                        }

                        else {
                            $db->Query("UPDATE `users` SET `payment_password` = '$_POST[payment_password]' WHERE `id` = '$_SESSION[id]'");
                            message('Вы успешно установили платежный пароль');
                        }
                    }
                    else {
                        message('Пароль уже установлен. Перезагрузите страницу', false, false, 'error');
                    }
                }
            }            
            else {
                message('Подтвердите платежный пароль', false, false, 'info');
            }
        }
        else {
            message('Введите Ваш платежный пароль', false, false, 'info');    
        }
    }
    else {
        message('Введите Ваш пароль от аккаунта', false, false, 'info');    
    }
}
else if ($_POST['reset_payment_password_f']) {
    if ($_POST['confirm_request']) {
        $db->Query("SELECT * FROM `users` WHERE `id` = '$_SESSION[id]'");
        $NumRows = $db->NumRows();
        if ( !empty($NumRows) ) {
            $FetchAssoc = $db->FetchAssoc();

            $link_http_host = stristr($_SERVER['HTTP_REFERER'], '/', true ).'//'.$_SERVER['HTTP_HOST'];

            $pre_code = hash(sha256, time()).'_g'.$FetchAssoc['id'];
            $code_confirm = hash(sha256, $pre_code);

            $db->Query('INSERT INTO `confirm_reset_payment_password` VALUES (NULL, "'.$FetchAssoc['id'].'", "'.$FetchAssoc['email'].'", "'.$code_confirm.'", NOW())');
//"".$FetchAssoc['email'].""
            mail("".$FetchAssoc['email']."", 'Сброс платежного пароля на '.$_SERVER['HTTP_HOST'].'', "Для сброса платежного пароля перейдите по ссылке: ".$link_http_host."/allconfirm?codereset=".$code_confirm."");
            message('Письмо с сылкой для сброса платежного пароля отправлено на ваш email');
        }
        else {
            message('Пользователя не существует.', false, false, 'warning');
        }

        message('Потом напишем алгоритм отправки письма на почту, с инструкцией по восстановлению');
    }
    else {
        message('Ошибка перечдачи запроса 1x0z@Lup@', false, false, 'error');
    }
}
else {
    message('Ошибка 345x00z@Lup@', false, false, 'error');
}
?>