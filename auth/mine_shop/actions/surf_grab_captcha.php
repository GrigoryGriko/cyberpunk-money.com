<?php
    usleep(50000);
    header("Content-Type: text/html; charset=utf-8");  // стандартная установка кодировки
?>
<?php
$comission_from_earn = 0.16; //комиссия от заработка пользователя в долях единиц

if ($_POST['captcha_confirm_f']) {
    //$captcha_correct = captcha_valid();
    $captcha_correct = 'yes';
    if ($captcha_correct != 'denied') {
        /*
            Сделать проверку на просмотренность сайта
            снять деньги с рекламного баланса создателя
            исполнителю добавить деньги на баланс для вывда с вычетом процента для системы
            watch_stats прибавить 1
            spending_stats прибавить cost_watch

            в user_seen_surf_list добавить просмотренное задание
        */
        $db->Query("SELECT `id` FROM `user_seen_surf_list` WHERE `uid` = '$_SESSION[id]' AND `uas_id` = '$_SESSION[it]'");
        $NumRows_seen_surf = $db->NumRows();
        if ( empty($NumRows_seen_surf) ) {   //если  в бд задания нет в просмотренных, то выводим его

            $db->Query("SELECT * FROM `user_addsurf_sites` WHERE `id` = '$_SESSION[it]'");
            $NumRows_user_a_s = $db->NumRows();
            if ( !empty($NumRows_user_a_s) ) {
                $assoc_user_a_s = $db->FetchAssoc();
                
                $db->Query("SELECT * FROM `users_data` WHERE `uid` = '$assoc_user_a_s[uid]'");
                $NumRows_user_data = $db->NumRows();
                if ( !empty($NumRows_user_data) ) {
                    $assoc_user_data = $db->FetchAssoc();

                    $db->Query("SELECT * FROM `users` WHERE `id` = '$_SESSION[id]'");
                    $NumRows_users = $db->NumRows();
                    if ( !empty($NumRows_users) ) {
                        $assoc_users = $db->FetchAssoc();

                        if ($assoc_user_data['balance_advertising'] >= $assoc_user_a_s['cost_watch']) {
                            $db->Query("UPDATE `users_data` SET `balance_advertising` = (`balance_advertising` - '$assoc_user_a_s[cost_watch]') WHERE `uid` = '$assoc_user_a_s[uid]'");

                            $comission_from_earn = 10 / 100;        //реферальные оплачивает создател задания, + комиссия 10%, а системе идет 5%
                            $clear_earn_system = 5 / 100;
                            if ($assoc_user_data['uid'] == $_SESSION['id']) {	//если это твое же задание, то комиссии нет, деньги возвращаются
                                $comission_from_earn = 0;
                                $clear_earn_system = 0;
                            	 //чистая прибыль системы
                            }

                            $sum_ref_surf = 0.005;

                            $money_to_user_executor = $assoc_user_a_s['cost_watch'] - ($assoc_user_a_s['cost_watch'] * $comission_from_earn) - $sum_ref_surf;   //минус 0.005 руб.
                            $db->Query("UPDATE `users_data` SET `balance_withdrawal` = (`balance_withdrawal` + $money_to_user_executor) WHERE `uid` = '$_SESSION[id]'");

                            $db->Query("UPDATE `users_data` SET `balance_withdrawal` = (`balance_withdrawal` + $sum_ref_surf) WHERE `uid` = '$assoc_users[ref]'");      //начисление рефералу                    

                            $db->Query("UPDATE `user_addsurf_sites` SET `watch_stats` = (`watch_stats` + 1), `spending_stats` = (`spending_stats` + `cost_watch`) WHERE `id` = '$assoc_user_a_s[id]'");

                            $money_to_system_from_comission = ($assoc_user_a_s['cost_watch'] * $clear_earn_system);
                            $db->Query("UPDATE `system_earn` SET `money_from_comission_surfing` = (`money_from_comission_surfing` + $money_to_system_from_comission)");

                            $db->Query('INSERT INTO `user_seen_surf_list` VALUES("", "'.$_SESSION['id'].'", "'.$assoc_user_a_s['id'].'", "'.$assoc_user_a_s['url_site'].'", "'.$money_to_user_executor.'", NOW())');

                            message('Просмотр засчитан', $assoc_user_a_s['url_site']);
                        }
                        else {
                            message('У создателя задания кончились средтсва', false, true, 'info');		//Вывести уведомление, после нажатия "ок" никуда не перенаправлять, закрыть вкладку
                        }
                    }
                    else {
                        message('Crytical errorusz0', false, true, 'error');
                    }
                }
                else {
                    message('Crytical error0002', false, true, 'error');
                }
            }
            else {
                message('Crytical error0001', false, true, 'error');
            }
        }
        else {
            message('Просмотр сайта уже засчитан', false, true, 'error');
        }
    }
    else {
        message($_SESSION['message_surfing'], false, true, 'info');
    }
}
else {
    message('Ошибка система <Ex00', false, true, 'error');
}

?>