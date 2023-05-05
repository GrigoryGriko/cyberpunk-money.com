<?php
    header("Content-Type: text/html; charset=utf-8");  // стандартная установка кодировки
?>
<?php
    if ($_SESSION['ADMIN_LOGIN_IN']) {
        /*function __autoload($name){ include("classes/_class.".$name.".php");}
        $config = new config;

        $db = new db($config->HostDB, $config->UserDB, $config->PassDB, $config->BaseDB);*/

        echo '<a href="/">На главную</a><br>Hello word. <a href="/page_a_list_users">Перейти в список новых пользователей</a>';

        $db->Query("SELECT SUM(`money_payin`) AS `sum_payin` FROM `history_money_payin` WHERE `uid` != 1"); //
        $numrows_sum_payin = $db->NumRows();
        $as_money_payin = $db->FetchAssoc();
        if ($as_money_payin['sum_payin'] == NULL) {
            $as_money_payin['sum_payin'] = 0;   //Сумма пополнений выплат
        }

        $db->Query("SELECT SUM(`money_payin`) AS `sum_payin` FROM `history_money_payin` WHERE `uid` != 1 AND `bot` = 0 AND `balance_type` = 'balance_buy'"); //
        $numrows_sum_payin_real = $db->NumRows();
        $as_money_payin_real = $db->FetchAssoc();
        if ($as_money_payin_real['sum_payin'] == NULL) {
            $as_money_payin_real['sum_payin'] = 0;   //Сумма пополнений выплат
        }

        $db->Query("SELECT SUM(`money_payin`) AS `sum_payin` FROM `history_money_payin` WHERE `uid` != 1 AND `bot` = 0 AND `balance_type` = 'balance_advertising'"); //
        $numrows_sum_ad = $db->NumRows();
        $as_money_ad = $db->FetchAssoc();
        if ($as_money_ad['sum_payin'] == NULL) {
            $as_money_ad['sum_payin'] = 0;   //Сумма пополнений выплат
        }

        $db->Query("SELECT * FROM `system_earn` WHERE `id` = 1"); //
        $system_earn = $db->NumRows();
        if ( !empty($system_earn) ) {
            $as_system_earn = $db->FetchAssoc();
            $total_system_earn = $as_system_earn['money_from_comission_surfing'] + $as_system_earn['money_from_raise_task'];
        }

        $db->Query("SELECT SUM(`money_payin`) AS `sum_payin` FROM `history_money_payin` WHERE `uid` != 1 AND `bot` = 0 AND `balance_type` = 'balance_game'"); //
        $numrows_sum_game = $db->NumRows();
        $as_money_game = $db->FetchAssoc();
        if ($as_money_game['sum_payin'] == NULL) {
            $as_money_game['sum_payin'] = 0;   //Сумма пополнений выплат
        }

        $db->Query("SELECT * FROM `system_earn_game_schulte_tab` WHERE `id` = 1"); //
        $system_earn_game = $db->NumRows();
        if ( !empty($system_earn_game) ) {
            $as_system_earn_game = $db->FetchAssoc();
        }



        $db->Query("SELECT SUM(`money_withdrawn`) AS `money_withdrawn` FROM `history_money_payout` WHERE `uid` != 1"); //
        $numrows_sum_payout = $db->NumRows();
        $as_money_payout = $db->FetchAssoc();
        if ($as_money_payout['money_withdrawn'] == NULL) {
            $as_money_payout['money_withdrawn'] = 0;
        }

        $db->Query("SELECT SUM(`money_withdrawn`) AS `money_withdrawn` FROM `history_money_payout` WHERE `uid` != 1 AND `bot` = 0"); //
        $numrows_sum_payin_real = $db->NumRows();
        $as_money_payout_real = $db->FetchAssoc();
        if ($as_money_payout_real['money_withdrawn'] == NULL) {
            $as_money_payout_real['money_withdrawn'] = 0;
        }


        $db->Query("SELECT COUNT(`id`) AS `amount_reg` FROM `users` WHERE `id` != 1"); //
        $numrows_date_reg = $db->NumRows();
        if ( !empty($numrows_date_reg) ) {
           $as_count_total = $db->FetchAssoc();
        }
        else {
            $as_count_total['amount_reg'] = 0;   //всего пользователей
        }

        $db->Query("SELECT COUNT(`id`) AS `amount_reg` FROM `users` WHERE `id` != 1 AND `isBOT` = 0"); //
        $numrows_date_reg_real = $db->NumRows();
        if ( !empty($numrows_date_reg_real) ) {
           $as_count_total_real = $db->FetchAssoc();
           $as_count_total_real['amount_reg'] += 0;
        }
        else {
            $as_count_total_real['amount_reg'] = 0;   //всего пользователей
        }

        $db->Query("SELECT SUM(`money_withdrawn`) AS `money_withdrawn` FROM `history_money_payout` WHERE `uid` != 1 AND `bot` = 0"); //
        $numrows_sum_payin_real = $db->NumRows();
        $as_money_payout_real = $db->FetchAssoc();
        if ($as_money_payout_real['money_withdrawn'] == NULL) {
            $as_money_payout_real['money_withdrawn'] = 0;
        }


        echo '<br><br>Пополнено людьми: <b style="color: green; font-size: 24px;">'.$as_money_payin_real['sum_payin'].' руб.</b> || Для Статистики: '.$as_money_payin['sum_payin'].' руб.
        <br>+Пополнено на рекламу: '.$as_money_ad['sum_payin'].' руб. - из которых заработано системой: <b style="color: green; font-size: 24px;">'.$total_system_earn.' руб.)</b>||
        <br>+Пополнено на игры: '.$as_money_game['sum_payin'].' руб. - заработано системой с игр: <b style="color: green; font-size: 24px;">'.$as_system_earn_game['earn_comission_game'].' руб.)</b>||';

        echo '<br><br>Выведено людьми: <b style="color: green; font-size: 24px;">'.$as_money_payout_real['money_withdrawn'].' руб.</b>|| ';

        echo 'Для Статистики: '.$as_money_payout['money_withdrawn'].' руб.';

        echo '<br><br>Всего людей <b style="color: green; font-size: 24px;">'.$as_count_total_real['amount_reg'].' чел.</b> || ';
        echo 'Для Статистики: '.$as_count_total['amount_reg'].' чел.';
    }
    else {
        exit(header('Location: /login_a'));
    }
?>