<?php
/*for ($q=1; $q<=200; $q++) {

    $db->Query("SELECT SUM(`money_payin`) AS `sum_payin` FROM `history_money_payin` WHERE `uid` = '$q' AND `bot` = 0"); //
    $numrows_sum_payin = $db->NumRows();
    $as_money_payin = $db->FetchAssoc();
    if ($as_money_payin['sum_payin'] == NULL) {
    $as_money_payin['sum_payin'] = 0;   //Резерв выплат
    }

    $db->Query("SELECT SUM(`money_withdrawn`) AS `sum_payout` FROM `history_money_payout` WHERE `uid` = '$q' AND `bot` = 0"); //
    $numrows_sum_payout = $db->NumRows();
    $as_money_payout = $db->FetchAssoc();
    if ($as_money_payout['sum_payout'] == NULL) {
    $as_money_payout['sum_payout'] = 0;   //Резерв выплат
    }

    $total_bulet = ($as_money_payin['sum_payin'] * 0.03 - $as_money_payout['sum_payout']);
    if ($total_bulet < 0) $total_bulet = 0;

    $QUERY = $db->Query_recordless("UPDATE `users_data` SET `bullet` = '$total_bulet' WHERE `uid` = '$q' AND `bot` = 0");
    @mysqli_free_result($QUERY);
}*/
?>


		
	


