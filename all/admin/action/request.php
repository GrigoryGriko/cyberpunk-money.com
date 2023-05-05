<?php
usleep(250);   
    header("Content-Type: text/html; charset=utf-8");  // стандартная установка кодировки
?>
<?php
    /*if ($_SESSION['ADMIN_LOGIN_IN']) {*/
        if ($_GET['make_payout'] == 1) {

        	/*session_start();
        	$_SESSION['action_times'];
            $_SESSION['action_times'] += 0;*/

            $result_text = '';
            $db->Query("SELECT * FROM `history_money_payout` WHERE `id` != 1 AND `bot` = 0 AND `status` = 0 AND `off` = 0");
            $NumRows_list_payout = $db->NumRows();
            if ( !empty($NumRows_list_payout) ) {
                while ( $assoc_list_payout = $db->FetchAssoc() ) {

                    function event_payout ($name_ps, $id_ps) {
                        global $db;
                        global $_GET;
                        global $assoc_list_payout;


                        require_once('cpayeer.php');
                        $accountNumber = 'P44005118';
                        $apiId = '954709632';
                        $apiKey = '47ysMYX0jisdwriP';
                        $payeer = new CPayeer($accountNumber, $apiId, $apiKey);
                        if ($payeer->isAuth())
                        {
                            $initOutput = $payeer->initOutput(array(
                                'ps' => $id_ps,
                                //'sumIn' => 1,
                                'curIn' => 'RUB',
                                'sumOut' => $assoc_list_payout['money_withdrawn'],
                                'curOut' => 'RUB',
                                'param_ACCOUNT_NUMBER' => $assoc_list_payout['account_wallet']
                            ));

                            if ($initOutput) {
                                $historyId = $payeer->output();
                                if ($historyId > 0) {
                                    /*$UPDATE_1 = $db->Query_recordless("UPDATE `users_data` SET `balance_withdrawal` = (`balance_withdrawal` - '$assoc_list_payout[money_withdrawn]') WHERE `uid` = '$assoc_list_payout[uid]'");*/
                                            $UPDATE_2 = $db->Query_recordless("UPDATE `history_money_payout` SET `status` = 1, `date` = NOW() WHERE `id` = '$assoc_list_payout[id]'");

                                    /*@mysqli_free_result($UPDATE_1);*/
                                    
                                    /*$result_text .= ''.$assoc_list_payout['id'].'-'.$assoc_list_payout['uid'].'Вывод средств успешно выполнен<br>';*/
                                }
                                else {
                                	//mail("geologymoney@gmail.com", 'Выплата не удалась', "Выплата не удалась https://geologymoney.com/page_p");
                                    /*echo '<pre>'.print_r($payeer->getErrors()).'</pre> ОБРАТИТЕСЬ В ТЕХПОДДЕРЖКУ, СКОПИРОВАВ ЭТОТ КОД ОШИБКИ. <a href="/page_p">Вернуться назад</a>';*/
                                }
                            }
                            else {
                            	//mail("geologymoney@gmail.com", 'Выплата не удалась', "Выплата не удалась https://geologymoney.com/page_p");
                                    
                                /*echo '<pre>'.print_r($payeer->getErrors()).'</pre> - ОБРАТИТЕСЬ В ТЕХПОДДЕРЖКУ, СКОПИРОВАВ ЭТОТ КОД ОШИБКИ. <a href="/page_p">Вернуться назад</a>';*/
                            }
                        }
                        else {
                        	//mail("geologymoney@gmail.com", 'Выплата не удалась', "Выплата не удалась https://geologymoney.com/page_p");

                            /*echo '<pre>'.print_r($payeer->getErrors(), true).'</pre> - ОБРАТИТЕСЬ В ТЕХПОДДЕРЖКУ, СКОПИРОВАВ ЭТОТ КОД ОШИБКИ. <a href="/page_p">Вернуться назад</a>';*/
                        }                    
                    }

                    if ($assoc_list_payout['payment_system'] == 'payeer') {    //payeer
                    
                        require_once('cpayeer.php');
                        $accountNumber = 'P44005118';
                        $apiId = '954709632';
                        $apiKey = '47ysMYX0jisdwriP';
                        $payeer = new CPayeer($accountNumber, $apiId, $apiKey);
                        if ($payeer->isAuth()){
                            $arTransfer = $payeer->transfer(array(
                                'curIn' => 'RUB',
                                'sum' => $assoc_list_payout['money_withdrawn'],
                                'curOut' => 'RUB',
                                //'sumOut' => 1,
                                'to' => $assoc_list_payout['account_wallet'],
                                //'to' => 'client@mail.com',
                                //'comment' => 'test',
                                //'protect' => 'Y',
                                //'protectPeriod' => '3',
                                //'protectCode' => '12345',
                            ));
                            if (empty($arTransfer['errors'])) {   
                                $db->Query("SELECT * FROM `history_money_payout` WHERE `uid` != '$assoc_list_payout[uid]' AND `account_wallet` = '$assoc_list_payout[account_wallet]'");
                                $numrows = $db->NumRows();
                                if ( empty($numrows) ) {
                                    /*$UPDATE_1 = $db->Query_recordless("UPDATE `users_data` SET `balance_withdrawal` = (`balance_withdrawal` - '$assoc_list_payout[money_withdrawn]') WHERE `uid` = '$assoc_list_payout[uid]'");*/
                                           $UPDATE_2 = $db->Query_recordless("UPDATE `history_money_payout` SET `status` = 1, `date` = NOW() WHERE `id` = '$assoc_list_payout[id]'");
                                            
                                            /*@mysqli_free_result($UPDATE_1);*/
                                            
                                            $result_text .= ''.$assoc_list_payout['id'].'-'.$assoc_list_payout['uid'].'Вывод средств успешно выполнен<br>';
                                    
                                                 
                                }
                                else {
                                    MessageSend('На данный номер кошелька производилась выплата с другого аккаунта. Обратитесь в техподдержку.', '/page_p');  
                                }   
                            }
                            else {
                            	//mail("geologymoney@gmail.com", 'Выплата не удалась', "Выплата не удалась https://geologymoney.com/page_p");
                                /*echo '<pre>'.print_r($arTransfer["errors"], true).'</pre> - ОБРАТИТЕСЬ В ТЕХПОДДЕРЖКУ, СКОПИРОВАВ ЭТОТ КОД ОШИБКИ. <a href="/page_p">Вернуться назад</a>';*/
                            }
                        }
                        else {
                        	//mail("geologymoney@gmail.com", 'Выплата не удалась', "Выплата не удалась https://geologymoney.com/page_p");
                            /*echo '<pre>'.print_r($payeer->getErrors(), true).'</pre> - ОБРАТИТЕСЬ В ТЕХПОДДЕРЖКУ, СКОПИРОВАВ ЭТОТ КОД ОШИБКИ. <a href="/page_p">Вернуться назад</a>';*/
                        }       
                    }

                    else if ($assoc_list_payout['payment_system'] == 'ADVCASH') {   //ADVCASH
                        if ($assoc_list_payout['account_wallet']) {
                            $account_wallet_valid = preg_match('/^[RUE]{1}[0-9]{7,15}|.+@.+\..+$/', $assoc_list_payout['account_wallet']);
                            if ( $account_wallet_valid == true) {
                                event_payout('ADVCASH', '87893285');
                            }
                            else {
                                 MessageSend('Номер кошелька не соответсвует формату'.$assoc_list_payout['payment_system'].'+'.$assoc_list_payout['account_wallet'].'', '/page_p');
                            }
                        }
                        else {
                            MessageSend('Укажите номер кошелька для выплаты', '/page_p');
                        }
                    }

                    else if ($assoc_list_payout['payment_system'] == 'Яндекс.Деньги') {   //yandex money
                        if ($assoc_list_payout['account_wallet']) {
                            $account_wallet_valid = preg_match('/^41001[0-9]{7,11}$/', $assoc_list_payout['account_wallet']);
                            if ( $account_wallet_valid == true) {
                                event_payout('Яндекс.Деньги', '57378077');
                            }
                            else {
                                 MessageSend('Номер кошелька не соответсвует формату'.$assoc_list_payout['payment_system'].'+'.$assoc_list_payout['account_wallet'].'', '/page_p');
                            }
                        }
                        else {
                            MessageSend('Укажите номер кошелька для выплаты', '/page_p');
                        }
                    }

                    else if ($assoc_list_payout['payment_system'] == 'QIWI') {   //QIWI
                        if ($assoc_list_payout['account_wallet']) {
                            $account_wallet_valid = preg_match('/^\+\d{9,15}$/', $assoc_list_payout['account_wallet']);
                            if ( $account_wallet_valid == true) {
                                event_payout('QIWI', '26808');
                            }
                            else {
                                 MessageSend('Номер кошелька не соответсвует формату'.$assoc_list_payout['payment_system'].'+'.$assoc_list_payout['account_wallet'].'', '/page_p');
                            }
                        }
                        else {
                            MessageSend('Укажите номер кошелька для выплаты', '/page_p');
                        }
                    }

                    else if ($assoc_list_payout['payment_system'] == 'БИЛАЙН') {   //билайн
                        if ($assoc_list_payout['account_wallet']) {
                            $account_wallet_valid = preg_match('/^[\+]{1}[7]{1}[9]{1}[\d]{9}$/', $assoc_list_payout['account_wallet']);
                            if ( $account_wallet_valid == true) {
                                event_payout('БИЛАЙН', '24898938');
                            }
                            else {
                                 MessageSend('Номер кошелька не соответсвует формату'.$assoc_list_payout['payment_system'].'+'.$assoc_list_payout['account_wallet'].'', '/page_p');
                            }
                        }
                        else {
                            MessageSend('Укажите номер кошелька для выплаты', '/page_p');
                        }
                    }

                    else if ($assoc_list_payout['payment_system'] == 'МЕГАФОН') {   //билайн
                        if ($assoc_list_payout['account_wallet']) {
                            $account_wallet_valid = preg_match('/^[\+]{1}[7]{1}[9]{1}[\d]{9}$/', $assoc_list_payout['account_wallet']);
                            if ( $account_wallet_valid == true) {
                                event_payout('МЕГАФОН', '24899391');
                            }
                            else {
                                 MessageSend('Номер кошелька не соответсвует формату'.$assoc_list_payout['payment_system'].'+'.$assoc_list_payout['account_wallet'].'', '/page_p');
                            }
                        }
                        else {
                            MessageSend('Укажите номер кошелька для выплаты', '/page_p');
                        }
                    }

                    else if ($assoc_list_payout['payment_system'] == 'МТС') {   //билайн
                        if ($assoc_list_payout['account_wallet']) {
                            $account_wallet_valid = preg_match('/^[\+]{1}[7]{1}[9]{1}[\d]{9}$/', $assoc_list_payout['account_wallet']);
                            if ( $account_wallet_valid == true) {
                                event_payout('МТС', '24899291');
                            }
                            else {
                                 MessageSend('Номер кошелька не соответсвует формату'.$assoc_list_payout['payment_system'].'+'.$assoc_list_payout['account_wallet'].'', '/page_p');
                            }
                        }
                        else {
                            MessageSend('Укажите номер кошелька для выплаты', '/page_p');
                        }
                    }

                    else if ($assoc_list_payout['payment_system'] == 'ТЕЛЕ 2') {   //билайн
                        if ($assoc_list_payout['account_wallet']) {
                            $account_wallet_valid = preg_match('/^[\+]{1}[7]{1}[9]{1}[\d]{9}$/', $assoc_list_payout['account_wallet']);
                            if ( $account_wallet_valid == true) {
                                event_payout('ТЕЛЕ 2', '95877310');
                            }
                            else {
                                 MessageSend('Номер кошелька не соответсвует формату'.$assoc_list_payout['payment_system'].'+'.$assoc_list_payout['account_wallet'].'', '/page_p');
                            }
                        }
                        else {
                            MessageSend('Укажите номер кошелька для выплаты', '/page_p');
                        }
                    }

                    else if ($assoc_list_payout['payment_system'] == 'MASTERCARD') {   //билайн
                        if ($assoc_list_payout['account_wallet']) {
                            $account_wallet_valid = preg_match('/^([45]{1}[\d]{15}|[6]{1}[\d]{17})$/', $assoc_list_payout['account_wallet']);
                            if ( $account_wallet_valid == true) {
                                event_payout('MASTERCARD', '27322260');
                            }
                            else {
                                 MessageSend('Номер кошелька не соответсвует формату'.$assoc_list_payout['payment_system'].'+'.$assoc_list_payout['account_wallet'].'', '/page_p');
                            }
                        }
                        else {
                            MessageSend('Укажите номер кошелька для выплаты', '/page_p');
                        }
                    }

                    else if ($assoc_list_payout['payment_system'] == 'VISA') {   //билайн
                        if ($assoc_list_payout['account_wallet']) {
                            $account_wallet_valid = preg_match('/^([45]{1}[\d]{15}|[6]{1}[\d]{17})$/', $assoc_list_payout['account_wallet']);
                            if ( $account_wallet_valid == true) {
                                event_payout('VISA', '27313794');
                            }
                            else {
                                 MessageSend('Номер кошелька не соответсвует формату'.$assoc_list_payout['payment_system'].'+'.$assoc_list_payout['account_wallet'].'', '/page_p');
                            }
                        }
                        else {
                            MessageSend('Укажите номер кошелька для выплаты', '/page_p');
                        }
                    }

                    else if ($assoc_list_payout['payment_system'] == 'MAESTRO') {   //билайн
                        if ($assoc_list_payout['account_wallet']) {
                            $account_wallet_valid = preg_match('/^([45]{1}[\d]{15}|[6]{1}[\d]{15,17})$/', $assoc_list_payout['account_wallet']);
                            if ( $account_wallet_valid == true) {
                                event_payout('MAESTRO', '27323626');
                            }
                            else {
                                 MessageSend('Номер кошелька не соответсвует формату'.$assoc_list_payout['payment_system'].'+'.$assoc_list_payout['account_wallet'].'', '/page_p');
                            }
                        }
                        else {
                            MessageSend('Укажите номер кошелька для выплаты', '/page_p');
                        }
                    }

                    else if ($assoc_list_payout['payment_system'] == 'МИР') {   //билайн
                        if ($assoc_list_payout['account_wallet']) {
                            $account_wallet_valid = preg_match('/^([245]{1}[\d]{15}|[6]{1}[\d]{17})$/', $assoc_list_payout['account_wallet']);
                            if ( $account_wallet_valid == true) {
                                event_payout('МИР', '510572988');
                            }
                            else {
                                 MessageSend('Номер кошелька не соответсвует формату'.$assoc_list_payout['payment_system'].'+'.$assoc_list_payout['account_wallet'].'', '/page_p');
                            }
                        }
                        else {
                            MessageSend('Укажите номер кошелька для выплаты', '/page_p');
                        }
                    }
                    else {
                        MessageSend('Данная платежная система недоступна', '/page_p');
                    }  
                }

                @mysqli_free_result($UPDATE_2);
                /*echo '<a href="">Вернуться назад</a><br>'.$result_text.'<br><a href="/page_p">Вернуться назад</a>';*/
               	echo ''.$result_text.'';

               	/*$_SESSION['action_times']++;

                if ($_SESSION['action_times'] <= 35) {*/
                	usleep(800000);
                	$_SESSION['action_times'] = 0;
                    exit(header('Location: /request?make_payout=1'));
                //}
            }
            else {
            	$UPDATE_LLL = $db->Query_recordless("UPDATE `users` SET `Name` = 'Семён' WHERE `id` = 1");
            	 @mysqli_free_result($UPDATE_LLL);
                //MessageSend('Нет данных о строке', '/page_p');
            }
        }
        else if ($_GET['denied']) {
            if ($_GET['id_list']) {
                $db->Query("UPDATE `history_money_payout` SET `status` = -1 WHERE `id` = ".$_GET['id_list']."");

                if ( isset($_GET['page_p_quest']) ) {
                    MessageSend(''.$_GET['id_list'].' - Успешно перенесен в откза', '/page_p_quest');
                }
                else {
                    MessageSend(''.$_GET['id_list'].' - Успешно перенесен в откза', '/page_p');
                }
            }
            else {
                MessageSend('lv2_1', '/page_p');
            }
        }
        else if ($_GET['trust']) {
            if ($_GET['id_list']) {
                $db->Query("UPDATE `history_money_payout` SET `off` = 0 WHERE `id` = ".$_GET['id_list']."");
                MessageSend(''.$_GET['id_list'].' - Успешно перенесен в доверенный', '/page_p_quest');
            }
            else {
                MessageSend('lv2_1', '/page_pquest');
            }
        }
        else if ($_GET['quest']) {
            if ($_GET['id_list']) {
                $db->Query("UPDATE `history_money_payout` SET `off` = 1 WHERE `id` = ".$_GET['id_list']."");
                MessageSend(''.$_GET['id_list'].' - Успешно перенесен под вопрос', '/page_p');
            }
            else {
                MessageSend('lv2_2', '/page_p');
            }
        }
        else {
            MessageSend('Нет данных', '/page_p');
        }
    /*}
    else {
        exit(header('Location: /login_a'));
    }*/
?>