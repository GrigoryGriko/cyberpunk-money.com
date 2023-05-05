<?php

if (!in_array($_SERVER['REMOTE_ADDR'], array('185.71.65.92', '185.71.65.189', '149.202.17.210'))) return;

if (isset($_POST['m_operation_id']) && isset($_POST['m_sign']))
{
	$m_key = 'RGqiFNoGX2jlxmqe';

	$arHash = array(
		$_POST['m_operation_id'],
		$_POST['m_operation_ps'],
		$_POST['m_operation_date'],
		$_POST['m_operation_pay_date'],
		$_POST['m_shop'],
		$_POST['m_orderid'],
		$_POST['m_amount'],
		$_POST['m_curr'],
		$_POST['m_desc'],
		$_POST['m_status']
	);

	if (isset($_POST['m_params']))
	{
		$arHash[] = $_POST['m_params'];
	}

	$arHash[] = $m_key;

	$sign_hash = strtoupper(hash('sha256', implode(':', $arHash)));

	if ($_POST['m_sign'] == $sign_hash && $_POST['m_status'] == 'success')
	{
		
		$db->Query("SELECT * FROM `history_money_payin` WHERE `m_operation_id` = '$_POST[m_operation_id]'");
		$NumRows = $db->NumRows();
    	if ( empty($NumRows) ) {

			$db->Query("SET NAMES 'utf8'");
			$db->Query("SET CHARACTER SET 'utf8'");
			$db->Query("SET SESSION collation_connection = 'utf8_general_ci'");

			$json = json_decode($_POST['m_params']); 	//рсшифровка json данных
			$USER_ID = $json->{'reference'}->{'user_id'};
			$BALANCE_TYPE = $json->{'reference'}->{'balance_type'};

            $db->Query("SELECT * FROM `users` WHERE `id` = '$USER_ID'");
            $NumRows_2 = $db->NumRows();
            if ( !empty($NumRows_2) ) {
                $assoc_user = $db->FetchAssoc();

                if ($assoc_user['ref'] == 0) $assoc_user['ref'] = 1;
                if ($assoc_user['ref_lvl_2'] == 0) $assoc_user['ref_lvl_2'] = 1;

                $sum_for_ref_1 = 0;
                $sum_for_ref_2 = 0;
                if ($BALANCE_TYPE == 'balance_buy') {
                    $sum_for_ref_1 = $_POST['m_amount'] * ($assoc_user['percent_ref_level1'] / 100);
                    $sum_for_ref_2 = $_POST['m_amount'] * ($assoc_user['percent_ref_level2'] / 100);
                    $db->Query("UPDATE `users_data` SET `balance_withdrawal` = (`balance_withdrawal` + '$sum_for_ref_2'), `bullet` = (`bullet` + '$sum_for_ref_2') WHERE `uid` = '$assoc_user[ref_lvl_2]'");  //начисление рефералу 1-го уровня

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
                    $sum_for_ref_1 = $_POST['m_amount'] * ($percent_ad_balance / 100);    
                }
                $db->Query("UPDATE `users_data` SET `balance_withdrawal` = (`balance_withdrawal` + '$sum_for_ref_1'), `bullet` = (`bullet` + '$sum_for_ref_1') WHERE `uid` = '$assoc_user[ref]'");    //начисление рефералу 2-го уровня
                
                $db->Query("UPDATE `users_stats` SET `money_payin` = (`money_payin` + '$_POST[m_amount]') WHERE `uid` = '$USER_ID'"); //обновление статистики

                if ($BALANCE_TYPE == 'balance_buy') {
                    $db->Query("UPDATE `users_stats` SET `money_payin` = (`money_payin` + $sum_for_ref_1) WHERE `uid` = '$assoc_user[ref]'"); //обновление статистики
                    $db->Query("UPDATE `users_stats` SET `money_payin` = (`money_payin` + $sum_for_ref_2) WHERE `uid` = '$assoc_user[ref_lvl_2]'"); //обновление статистики
                }
                else {   
                    $db->Query("UPDATE `users_stats` SET `money_payin` = (`money_payin` + $sum_for_ref_1) WHERE `uid` = '$assoc_user[ref]'"); //обновление статистики
                }

                $db->Query("UPDATE `users_stats` SET `money_earn_refs_1` = (`money_earn_refs_1` + $sum_for_ref_1) WHERE `uid` = '$assoc_user[ref]'"); //обновление статистики
                $db->Query("UPDATE `users_stats` SET `money_earn_refs_total` = (`money_earn_refs_1` + `money_earn_refs_2`) WHERE `uid` = '$assoc_user[ref]'"); //обновление 
                $db->Query("UPDATE `users_stats` SET `money_earn_refs_total` = (`money_earn_refs_1` + `money_earn_refs_2`) WHERE `uid` = '$assoc_user[ref_lvl_2]'"); //обновление статистики


                $db->Query("UPDATE `users_data` SET `".$BALANCE_TYPE."` = `".$BALANCE_TYPE."` + '$_POST[m_amount]', `bullet` = (`bullet` + '$total_bullet') WHERE `uid` = '$USER_ID'");

                $db->Query('INSERT INTO `history_money_payin` VALUES(NULL, "'.$USER_ID.'", "'.$_POST['m_amount'].'", "'.$BALANCE_TYPE.'", "payeer", "'.$_POST['m_operation_id'].'", "'.$_POST['m_curr'].'", "'.$_POST['m_operation_date'].'", NOW(), "'.$_POST['m_status'].'", 0)' );

                PAGE_leadrace_payin_refresh(1);
                    
                echo $_POST['m_orderid'].'|success';
                exit;
			}
			else {
    			echo $_POST['m_orderid'].'|error not exists user';
    		}
    	}
    	else {
    		echo $_POST['m_orderid'].'|error duplicate';
    	}
		
	}

	echo $_POST['m_orderid'].'|error';
}
else{
    die('NO OPERATION DATA');
}
?>