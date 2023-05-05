<?php
$merchant_id = '190249';
    $merchant_secret = 'p8myo6sy'; 

    function getIP() {
    if(isset($_SERVER['HTTP_X_REAL_IP'])) return $_SERVER['HTTP_X_REAL_IP'];
    return $_SERVER['REMOTE_ADDR'];
    }
    if (!in_array(getIP(), array('136.243.38.147', '136.243.38.149', '136.243.38.150', '136.243.38.151', '136.243.38.189', '88.198.88.98'))) {
    die("hacking attempt!");
    }
    $sign = md5($merchant_id.':'.$_REQUEST['AMOUNT'].':'.$merchant_secret.':'.$_REQUEST['MERCHANT_ORDER_ID']);


    if ($sign != $_REQUEST['SIGN']) {
    die('wrong sign');
    }


    $db->Query("SELECT * FROM `history_money_payin` WHERE `m_operation_id` = '$_REQUEST[intid]'");
    $NumRows = $db->NumRows();
    if ( empty($NumRows) ) {

        $db->Query("SET NAMES 'utf8'");
        $db->Query("SET CHARACTER SET 'utf8'");
        $db->Query("SET SESSION collation_connection = 'utf8_general_ci'");

        $db->Query("SELECT * FROM `users` WHERE `id` = '$_REQUEST[us_user_id]'");
        $NumRows_2 = $db->NumRows();
        if ( !empty($NumRows_2) ) {
            $assoc_user = $db->FetchAssoc();

            if ($assoc_user['ref'] == 0) $assoc_user['ref'] = 1;
            if ($assoc_user['ref_lvl_2'] == 0) $assoc_user['ref_lvl_2'] = 1;

            $sum_for_ref_1 = 0;
            $sum_for_ref_2 = 0;
            if ($_REQUEST['us_balance_type'] == 'balance_buy') {
                $sum_for_ref_1 = $_REQUEST['AMOUNT'] * ($assoc_user['percent_ref_level1'] / 100);
                $sum_for_ref_2 = $_REQUEST['AMOUNT'] * ($assoc_user['percent_ref_level2'] / 100);
                $db->Query("UPDATE `users_data` SET `balance_withdrawal` = (`balance_withdrawal` + '$sum_for_ref_2') WHERE `uid` = '$assoc_user[ref_lvl_2]'");  //начисление рефералу 1-го уровня

                $db->Query("UPDATE `users_stats` SET `money_earn_refs_2` = (`money_earn_refs_2` + $sum_for_ref_2) WHERE `uid` = '$assoc_user[ref_lvl_2]'"); //обновление статистики

                $percent_bullet = 40;  //баллы
                $total_bullet = $_POST['m_amount'] - ( $_POST['m_amount'] * ($percent_bullet / 100) );
            }
            else if ($BALANCE_TYPE == 'balance_game') {
                    $total_bullet = 0;

                    $percent_ad_balance = 0;
                    $sum_for_ref_1 = 0;     
            }
            else {
                $total_bullet = $_POST['m_amount'];

                $percent_ad_balance = 5;
                $sum_for_ref_1 = $_REQUEST['AMOUNT'] * ($percent_ad_balance / 100);    
            }
            $db->Query("UPDATE `users_data` SET `balance_withdrawal` = (`balance_withdrawal` + '$sum_for_ref_1') WHERE `uid` = '$assoc_user[ref]'");    //начисление рефералу 2-го уровня
            
            $db->Query("UPDATE `users_stats` SET `money_payin` = (`money_payin` + '$_REQUEST[AMOUNT]') WHERE `uid` = '$_REQUEST[us_user_id]'"); //обновление статистики

            if ($_REQUEST['us_balance_type'] == 'balance_buy') {
                $db->Query("UPDATE `users_stats` SET `money_payin` = (`money_payin` + $sum_for_ref_1) WHERE `uid` = '$assoc_user[ref]'"); //обновление статистики
                $db->Query("UPDATE `users_stats` SET `money_payin` = (`money_payin` + $sum_for_ref_2) WHERE `uid` = '$assoc_user[ref_lvl_2]'"); //обновление статистики
            }
            else {
                 $db->Query("UPDATE `users_stats` SET `money_payin` = (`money_payin` + $sum_for_ref_1) WHERE `uid` = '$assoc_user[ref]'"); //обновление статистики
            }

            $db->Query("UPDATE `users_stats` SET `money_earn_refs_1` = (`money_earn_refs_1` + $sum_for_ref_1) WHERE `uid` = '$assoc_user[ref]'"); //обновление статистики
            $db->Query("UPDATE `users_stats` SET `money_earn_refs_total` = (`money_earn_refs_1` + `money_earn_refs_2`) WHERE `uid` = '$assoc_user[ref]'"); //обновление 
            $db->Query("UPDATE `users_stats` SET `money_earn_refs_total` = (`money_earn_refs_1` + `money_earn_refs_2`) WHERE `uid` = '$assoc_user[ref_lvl_2]'"); //обновление статистики


            $db->Query("UPDATE `users_data` SET `".$_REQUEST['us_balance_type']."` = (`".$_REQUEST['us_balance_type']."` + '$_REQUEST[AMOUNT]'), `bullet` = (`bullet` + '$total_bullet') WHERE `uid` = '$_REQUEST[us_user_id]'");

            $db->Query('INSERT INTO `history_money_payin` VALUES(NULL, "'.$_REQUEST['us_user_id'].'", "'.$_REQUEST['AMOUNT'].'", "'.$_REQUEST['us_balance_type'].'", "freekassa", "'.$_REQUEST['intid'].'", "RUB", NOW(), NOW(), "success", 0)' );

            PAGE_leadrace_payin_refresh(1);
            die('|success');
        }
        else {
            die('|error not exists user');
        }
    }
    else {
        die('error_f duplicate');
    }

    //Так же, рекомендуется добавить проверку на сумму платежа и не была ли эта заявка уже оплачена или отменена
    //Оплата прошла успешно, можно проводить операцию.

    
?>