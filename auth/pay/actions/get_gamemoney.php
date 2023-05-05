<?php
usleep(250000);
    header("Content-Type: text/html; charset=utf-8");  // стандартная установка кодировки
?>
<?php
/*

47ysMYX0jisdwriP
P44005118
954709632
*/

$db->Query("SELECT * FROM `users` WHERE `id` = '$_SESSION[id]'");
$NumRows = $db->NumRows();
if ( !empty($NumRows) ) {
    $assoc_users = $db->FetchAssoc();

function event_payout ($name_ps, $id_ps) {
    global $db;
    global $_POST;

    if ($_POST['sum_payout']) {
        if ( iconv_strlen($_POST['sum_payout']) < 61 ) {
            if ($_POST['sum_payout'] > 0) {
                $db->Query("SELECT * FROM `wallet_payout_info` WHERE `name` = '$name_ps'");
                $NumRows = $db->NumRows();
                $assoc_wallet_info = $db->FetchAssoc();

                if ( $_POST['sum_payout'] < $assoc_wallet_info['min_sum']) {
                    MessageSend('Сумма выплаты меньше допустимого значения', '/pay/payout_gamemoney');
                }
                else if ($_POST['sum_payout'] > $assoc_wallet_info['max_sum']) {
                    MessageSend('Сумма выплаты больше допустимого значения', '/pay/payout_gamemoney');
                }
                else {
                    //проверяем баланс для вывода у пользователя
                    $db->Query("SELECT * FROM `users_data` WHERE `uid` = '$_SESSION[id]'");
                    $NumRows = $db->NumRows();
                    if ( !empty($NumRows) ) {
                        $assoc_users_data = $db->FetchAssoc();

                        $db->Query("SELECT * FROM `users` WHERE `id` = '$_SESSION[id]'");
                        $NumRows_us = $db->NumRows();
                        if ( !empty($NumRows_us) ) {
                            $assoc_us = $db->FetchAssoc();
                        }

                        $db->Query("SELECT * FROM `users_stats` WHERE `uid` = '$_SESSION[id]'");
                        $NumRows_stats = $db->NumRows();
                        if ( !empty(NumRows_stats) ) {
                            $assoc_users_stats = $db->FetchAssoc();
                        
                            if ($_POST['sum_payout'] <= $assoc_users_data['balance_game']) {

                                $db->Query("SELECT * FROM `history_money_payout` WHERE `uid` != '$_SESSION[id]' AND `account_wallet` = '$_POST[acount_wallet]'");
                                $numrows = $db->NumRows();
                                if ( empty($numrows) ) {

                                    $db->Query("UPDATE `users_data` SET `balance_game` = (`balance_game` - '$_POST[sum_payout]') WHERE `uid` = '$_SESSION[id]'");

                                    $db->Query('INSERT INTO `history_money_payout` VALUES (NULL, "'.$_SESSION['id'].'", "'.$_POST['sum_payout'].'", "'.$name_ps.'", "'.$_POST['acount_wallet'].'", NOW(), 0, 0, "'.$assoc_users_data['balance_buy'].'", "'.$assoc_users_stats['money_payin'].'", "'.$assoc_users_stats['money_payout'].'", "'.$assoc_users_stats['money_earn_refs_total'].'", "'.$assoc_us['date_reg'].'", 2, "'.$_SESSION['email'].'")');

                                    MessageSend('Ваша заявка на выплату успешно создана. Обработка занимает до 5 минут', '/pay/payout_gamemoney');

                                }
                                else {
                                    MessageSend('На данный номер кошелька производилась выплата с другого аккаунта. Обратитесь в техподдержку.', '/pay/payout_gamemoney');  
                                }     
                            }
                            else {
                                MessageSend('Недостаточно средств для вывода', '/pay/payout_gamemoney'); 
                            }
                        }
                        else {
                            MessageSend('Недостаточно средств для вывода', '/pay/payout_gamemoney');    
                        }
                    }
                    else {
                        MessageSend('Вы не авторизованы', '/pay/payout_gamemoney');
                    }
                }
            }
            else {
                MessageSend('Введите корректную сумму', '/pay/payout_gamemoney');
            }
        }
        else {
            MessageSend('Сумма слишком большая', '/pay/payout_gamemoney');
        }
    }
    else {
        MessageSend('Укажите сумму для выплаты', '/pay/payout_gamemoney');
    }
}


    if ($assoc_users['isCONFIRM_email'] == 1) {
        if ( !empty($assoc_users['payment_password']) ) {
            if ($_POST['payment_password']) {
                if (iconv_strlen($_POST['payment_password']) < 61) {
                    if ( $assoc_users['payment_password'] != hash(sha256, $_POST['payment_password']) ) {
                        MessageSend('Платежный пароль введен неверно', '/pay/payout_gamemoney');    //exit скрипта
                    }
                }
                else {
                    MessageSend('Платежный пароль слишком длинный', '/pay/payout_gamemoney');
                }
            }
            else {
                MessageSend('Введите платежный пароль', '/pay/payout_gamemoney');
            }
        }

        if ($_POST['payin_sys_1']) {    //payeer
            if ($_POST['acount_wallet']) {
                $account_wallet_valid = preg_match('/^[Pp]{1}[0-9]{7,15}|.+@.+\..+$/', $_POST['acount_wallet']);
                if ( $account_wallet_valid == true) {
                    if ($_POST['sum_payout']) {
                        if ( iconv_strlen($_POST['sum_payout']) < 61 ) {
                            if ($_POST['sum_payout'] > 0) {
                                $db->Query("SELECT * FROM `wallet_payout_info` WHERE `name` = 'payeer'");
                                $NumRows = $db->NumRows();
                                $assoc_wallet_info = $db->FetchAssoc();

                                if ( $_POST['sum_payout'] < $assoc_wallet_info['min_sum']) {
                                    MessageSend('Сумма выплаты меньше допустимого значения', '/pay/payout_gamemoney');
                                }
                                else if ($_POST['sum_payout'] > $assoc_wallet_info['max_sum']) {
                                    MessageSend('Сумма выплаты больше допустимого значения', '/pay/payout_gamemoney');
                                }
                                else {
                                    //проверяем баланс для вывода у пользователя
                                    $db->Query("SELECT * FROM `users_data` WHERE `uid` = '$_SESSION[id]'");
                                    $NumRows = $db->NumRows();
                                    if ( !empty($NumRows) ) {
                                        $assoc_users_data = $db->FetchAssoc();

                                        $db->Query("SELECT * FROM `users_stats` WHERE `uid` = '$_SESSION[id]'");
                                        $NumRows_stats = $db->NumRows();
                                        if ( !empty(NumRows_stats) ) {
                                            $assoc_users_stats = $db->FetchAssoc();
                                        
                                            if ($_POST['sum_payout'] <= $assoc_users_data['balance_game']) {

                                                $db->Query("SELECT * FROM `history_money_payout` WHERE `uid` != '$_SESSION[id]' AND `account_wallet` = '$_POST[acount_wallet]'");
                                                $numrows = $db->NumRows();
                                                if ( empty($numrows) ) {
                                                    
                                                    $db->Query("UPDATE `users_data` SET `balance_game` = (`balance_game` - '$_POST[sum_payout]') WHERE `uid` = '$_SESSION[id]'");

                                                    $name_ps = 'payeer';

                                                    $db->Query('INSERT INTO `history_money_payout` VALUES (NULL, "'.$_SESSION['id'].'", "'.$_POST['sum_payout'].'", "'.$name_ps.'", "'.$_POST['acount_wallet'].'", NOW(), 0, 0, "'.$assoc_users_data['balance_buy'].'", "'.$assoc_users_stats['money_payin'].'", "'.$assoc_users_stats['money_payout'].'", "'.$assoc_users_stats['money_earn_refs_total'].'", "'.$assoc_users_data['date_reg'].'", 2, "'.$_SESSION['email'].'")');

                                                    MessageSend('Ваша заявка на выплату успешно создана', '/pay/payout_gamemoney');

                                                }
                                                else {
                                                    MessageSend('На данный номер кошелька производилась выплата с другого аккаунта. Обратитесь в техподдержку.', '/pay/payout_gamemoney');  
                                                }     
                                            }
                                            else {
                                                MessageSend('Недостаточно средств для вывода', '/pay/payout_gamemoney'); 
                                            }
                                        }
                                        else {
                                            MessageSend('Недостаточно средств для вывода', '/pay/payout_gamemoney');    
                                        }
                                    }
                                    else {
                                        MessageSend('Вы не авторизованы', '/pay/payout_gamemoney');
                                    }
                                }
                            }
                            else {
                                MessageSend('Введите корректную сумму', '/pay/payout_gamemoney');
                            }
                        }
                        else {
                            MessageSend('Сумма слишком большая', '/pay/payout_gamemoney');
                        }
                    }
                    else {
                        MessageSend('Укажите сумму для выплаты', '/pay/payout_gamemoney');
                    }
                }
                else {
                    MessageSend('Номер кошелька не соответсвует формату', '/pay/payout_gamemoney');
                }
            }
            else {
                MessageSend('Укажите номер кошелька для выплаты', '/pay/payout_gamemoney');
            }
        }
/*add payin_sys 2*/
        else if ($_POST['payin_sys_3']) {   //ADVCASH
            if ($_POST['acount_wallet']) {
                $account_wallet_valid = preg_match('/^[RUE]{1}[0-9]{7,15}|.+@.+\..+$/', $_POST['acount_wallet']);
                if ( $account_wallet_valid == true) {
                    event_payout('ADVCASH', '87893285');
                }
                else {
                    MessageSend('Номер кошелька не соответсвует формату', '/pay/payout_gamemoney');
                }
            }
            else {
                MessageSend('Укажите номер кошелька для выплаты', '/pay/payout_gamemoney');
            }
        }

        else if ($_POST['payin_sys_4']) {   //yandex money
            if ($_POST['acount_wallet']) {
                $account_wallet_valid = preg_match('/^41001[0-9]{7,11}$/', $_POST['acount_wallet']);
                if ( $account_wallet_valid == true) {
                    event_payout('Яндекс.Деньги', '57378077');
                }
                else {
                    MessageSend('Номер кошелька не соответсвует формату', '/pay/payout_gamemoney');
                }
            }
            else {
                MessageSend('Укажите номер кошелька для выплаты', '/pay/payout_gamemoney');
            }
        }

        else if ($_POST['payin_sys_5']) {   //QIWI
            if ($_POST['acount_wallet']) {
                $account_wallet_valid = preg_match('/^\+\d{9,15}$/', $_POST['acount_wallet']);
                if ( $account_wallet_valid == true) {
                    event_payout('QIWI', '26808');
                }
                else {
                    MessageSend('Номер кошелька не соответсвует формату', '/pay/payout_gamemoney');
                }
            }
            else {
                MessageSend('Укажите номер кошелька для выплаты', '/pay/payout_gamemoney');
            }
        }

        else if ($_POST['payin_sys_6']) {   //билайн
            if ($_POST['acount_wallet']) {
                $account_wallet_valid = preg_match('/^[\+]{1}[7]{1}[9]{1}[\d]{9}$/', $_POST['acount_wallet']);
                if ( $account_wallet_valid == true) {
                    event_payout('БИЛАЙН', '24898938');
                }
                else {
                    MessageSend('Номер кошелька не соответсвует формату', '/pay/payout_gamemoney');
                }
            }
            else {
                MessageSend('Укажите номер кошелька для выплаты', '/pay/payout_gamemoney');
            }
        }

        else if ($_POST['payin_sys_7']) {   //мегафон
            if ($_POST['acount_wallet']) {
                $account_wallet_valid = preg_match('/^[\+]{1}[7]{1}[9]{1}[\d]{9}$/', $_POST['acount_wallet']);
                if ( $account_wallet_valid == true) {
                    event_payout('МЕГАФОН', '24899391');
                }
                else {
                    MessageSend('Номер кошелька не соответсвует формату', '/pay/payout_gamemoney');
                }
            }
            else {
                MessageSend('Укажите номер кошелька для выплаты', '/pay/payout_gamemoney');
            }
        }

        else if ($_POST['payin_sys_8']) {   //мтс
            if ($_POST['acount_wallet']) {
                $account_wallet_valid = preg_match('/^[\+]{1}[7]{1}[9]{1}[\d]{9}$/', $_POST['acount_wallet']);
                if ( $account_wallet_valid == true) {
                    event_payout('МТС', '24899291');
                }
                else {
                    MessageSend('Номер кошелька не соответсвует формату', '/pay/payout_gamemoney');
                }
            }
            else {
                MessageSend('Укажите номер кошелька для выплаты', '/pay/payout_gamemoney');
            }
        }

        else if ($_POST['payin_sys_9']) {   //теле2
            if ($_POST['acount_wallet']) {
                $account_wallet_valid = preg_match('/^[\+]{1}[7]{1}[9]{1}[\d]{9}$/', $_POST['acount_wallet']);
                if ( $account_wallet_valid == true) {
                    event_payout('ТЕЛЕ 2', '95877310');
                }
                else {
                    MessageSend('Номер кошелька не соответсвует формату', '/pay/payout_gamemoney');
                }
            }
            else {
                MessageSend('Укажите номер кошелька для выплаты', '/pay/payout_gamemoney');
            }
        }

        else if ($_POST['payin_sys_10']) {   //mastercard
            if ($_POST['acount_wallet']) {
                $account_wallet_valid = preg_match('/^([45]{1}[\d]{15}|[6]{1}[\d]{17})$/', $_POST['acount_wallet']);
                if ( $account_wallet_valid == true) {
                    event_payout('MASTERCARD', '27322260');
                }
                else {
                    MessageSend('Номер кошелька не соответсвует формату', '/pay/payout_gamemoney');
                }
            }
            else {
                MessageSend('Укажите номер кошелька для выплаты', '/pay/payout_gamemoney');
            }
        }

        else if ($_POST['payin_sys_11']) {   //visa
            if ($_POST['acount_wallet']) {
                $account_wallet_valid = preg_match('/^([45]{1}[\d]{15}|[6]{1}[\d]{17})$/', $_POST['acount_wallet']);
                if ( $account_wallet_valid == true) {
                    event_payout('VISA', '27313794');
                }
                else {
                    MessageSend('Номер кошелька не соответсвует формату', '/pay/payout_gamemoney');
                }
            }
            else {
                MessageSend('Укажите номер кошелька для выплаты', '/pay/payout_gamemoney');
            }
        }

        else if ($_POST['payin_sys_12']) {   //maestro
            if ($_POST['acount_wallet']) {
                $account_wallet_valid = preg_match('/^([45]{1}[\d]{15}|[6]{1}[\d]{15,17})$/', $_POST['acount_wallet']);
                if ( $account_wallet_valid == true) {
                    event_payout('MAESTRO', '27323626');
                }
                else {
                    MessageSend('Номер кошелька не соответсвует формату', '/pay/payout_gamemoney');
                }
            }
            else {
                MessageSend('Укажите номер кошелька для выплаты', '/pay/payout_gamemoney');
            }
        }

        else if ($_POST['payin_sys_13']) {   //мир
            if ($_POST['acount_wallet']) {
                $account_wallet_valid = preg_match('/^([245]{1}[\d]{15}|[6]{1}[\d]{17})$/', $_POST['acount_wallet']);
                if ( $account_wallet_valid == true) {
                    event_payout('МИР', '510572988');
                }
                else {
                    MessageSend('Номер кошелька не соответсвует формату', '/pay/payout_gamemoney');
                }
            }
            else {
                MessageSend('Укажите номер кошелька для выплаты', '/pay/payout_gamemoney');
            }
        }
        else {
            MessageSend('Данная платежная система недоступна', '/pay/payout_gamemoney');
        }   

    }
    else {
        MessageSend('Для выплаты подтвердите свой e-mail в&nbsp<a href="/setting_account">настройках аккаунта</a>', '/pay/payout_gamemoney');
    }
}
else {
    MessageSend('Вы не авторизованы', '/pay/payout_gamemoney');
}

?>