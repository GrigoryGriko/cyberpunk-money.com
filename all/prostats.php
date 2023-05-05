<?php

    for ($days = 0; $days <= 6; $days++) {
        $days_ago_in_seconds = time() - (60 * 60 * 24 * $days);
        $a_days_ago_1 = date("Y-m-d 00:00:00", $days_ago_in_seconds);
        $a_days_ago_2 = date("Y-m-d 23:59:59", $days_ago_in_seconds);

        $date[$days] = date("Y-m-d", $days_ago_in_seconds);
        $db->Query("SELECT COUNT(`id`) AS `amount_reg` FROM `users` WHERE `date_reg` BETWEEN '$a_days_ago_1' AND '$a_days_ago_2' AND `id` != 1"); //пытается натйи строки с точным посекундным временем
        $numrows_date_reg = $db->NumRows();
        if ( !empty($numrows_date_reg) ) {
           $as_count_ar[$days] = $db->FetchAssoc();
        }
        else {
            $as_count_ar[$days]['amount_reg'] = 0;  //пользователей за сегодня
        }
    }

    for ($days = 0; $days <= 6; $days++) {
        $days_ago_in_seconds = time() - (60 * 60 * 24 * $days);

        $a_days_ago_2 = date("Y-m-d 23:59:59", $days_ago_in_seconds);

        $db->Query("SELECT COUNT(`id`) AS `amount_reg` FROM `users` WHERE `date_reg` BETWEEN '0000-00-00 00:00:00' AND '$a_days_ago_2' AND `id` != 1"); //
        $numrows_date_reg = $db->NumRows();
        if ( !empty($numrows_date_reg) ) {
           $as_count_total[$days] = $db->FetchAssoc();
        }
        else {
            $as_count_total[$days]['amount_reg'] = 0;   //всего пользователей
        }
    }


    for ($days = 0; $days <= 6; $days++) {
        $days_ago_in_seconds = time() - (60 * 60 * 24 * $days);

        $a_days_ago_2 = date("Y-m-d 23:59:59", $days_ago_in_seconds);

        $date_pay[$days] = date("Y-m-d", $days_ago_in_seconds);
        $db->Query("SELECT SUM(`money_payin`) AS `sum_payin` FROM `history_money_payin` WHERE `date_payin` BETWEEN '0000-00-00 00:00:00' AND '$a_days_ago_2' AND `uid` != 1"); //
        $numrows_sum_payin = $db->NumRows();
        $as_money_payin[$days] = $db->FetchAssoc();
        if ($as_money_payin[$days]['sum_payin'] == NULL) {
            $as_money_payin[$days]['sum_payin'] = 0;   //Резерв выплат
        }
    }

    for ($days = 0; $days <= 6; $days++) {
        $days_ago_in_seconds = time() - (60 * 60 * 24 * $days);

        $a_days_ago_1 = date("Y-m-d 00:00:00", $days_ago_in_seconds);
        $a_days_ago_2 = date("Y-m-d 23:59:59", $days_ago_in_seconds);

        $db->Query("SELECT SUM(`money_payin`) AS `sum_payin` FROM `history_money_payin` WHERE `date_payin` BETWEEN '$a_days_ago_1' AND '$a_days_ago_2' AND `uid` != 1"); //
        $numrows_sum_payin = $db->NumRows();
        if ( !empty($numrows_sum_payin) ) { //условие все равно выполняется, если значение пустое
           $as_money_payin_day[$days] = $db->FetchAssoc();
        }
        else {
            $as_money_payin_day[$days]['sum_payin'] = 0;   //Сумма пополнений за день
        }
    }
    for ($days = 0; $days <= 6; $days++) {
        if ( empty($as_money_payin_day[$days]['sum_payin']) ) {
            $as_money_payin_day[$days]['sum_payin'] = 0;    //Сумма пополнений за день
        }   
    }
/*__________V___________сумма выплат за сегодня___________V___________*/
 /* for ($days = 0; $days <= 6; $days++) {
        $days_ago_in_seconds = time() - (60 * 60 * 24 * $days);

        $a_days_ago_1 = date("Y-m-d 00:00:00", $days_ago_in_seconds);
        $a_days_ago_2 = date("Y-m-d 23:59:59", $days_ago_in_seconds);

        $db->Query("SELECT SUM(`money_withdrawn`) AS `sum_withdrawn` FROM `history_money_payout` WHERE `date_payin` BETWEEN '$a_days_ago_1' AND '$a_days_ago_2' AND `uid` != 1"); //
        $numrows_sum_withdrawn = $db->NumRows();
        if ( !empty($numrows_sum_withdrawn) ) { //условие все равно выполняется, если значение пустое
           $as_money_withdrawn_day[$days] = $db->FetchAssoc();
        }
        else {
            $as_money_withdrawn_day[$days]['sum_withdrawn'] = 0;   //Сумма пополнений за день
        }
    }
    for ($days = 0; $days <= 6; $days++) {
        if ( empty($as_money_withdrawn_day[$days]['sum_withdrawn']) ) {
            $as_money_withdrawn_day[$days]['sum_withdrawn'] = 0;    //Сумма пополнений за день
        }   
    }*/


    $days = 0;
    $days_ago_in_seconds = time() - (60 * 60 * 24 * $days);

    $a_days_ago_1 = date("Y-m-d 00:00:00", $days_ago_in_seconds);
    $a_days_ago_2 = date("Y-m-d 23:59:59", $days_ago_in_seconds);

    $db->Query("SELECT SUM(`money_withdrawn`) AS `sum_withdrawn` FROM `history_money_payout` WHERE `date` BETWEEN '$a_days_ago_1' AND '$a_days_ago_2' AND `uid` != 1"); //
    $numrows_sum_withdrawn = $db->NumRows();
    if ( !empty($numrows_sum_withdrawn) ) { //условие все равно выполняется, если значение пустое
       $as_money_withdrawn_day = $db->FetchAssoc();
    }
    else {
        $as_money_withdrawn_day['sum_withdrawn'] = 0;   //Сумма пополнений за день
    } 
/*__________A___________сумма выплат за сегодня___________A___________*/

    for ($days = 0; $days <= 6; $days++) {
        $days_ago_in_seconds = time() - (60 * 60 * 24 * $days);

        $a_days_ago_1 = date("Y-m-d 00:00:00", $days_ago_in_seconds);
        $a_days_ago_2 = date("Y-m-d 23:59:59", $days_ago_in_seconds);

        $db->Query("SELECT SUM(`money_withdrawn`) AS `sum_payout` FROM `history_money_payout` WHERE `uid` != 1"); //
        $numrows_sum_payout = $db->NumRows();
        $as_money_sum_payout[$days] = $db->FetchAssoc();
        if ($as_money_sum_payout[$days]['sum_payout'] == NULL) {
            $as_money_sum_payout[$days]['sum_payout'] = 0;   //Сумма выплат
        }
    }

    top_guest('Статистика проекта', 'all/style/prostatsstyle', 'prostats');
    top_second_guest();
    
    echo '
            <div class="content_1">
                <div class="container_content">
                    <div class="stroke_1">
                        <div class="text_1">полная прозрачность</div>

                        <div class="container_round_shape">    
                            <div class="line_left"></div>
                            <div class="text_2">
                                <div class="text">Статистика</div>
                                <!-- <img src="../img/all/prostats/crystal_blue.png"> -->
                            </div>
                            <div class="line_left"></div>
                        </div>
                    </div>

                    <div class="stroke_2">
                        <div class="graphic_1">
                            <div class="text_1">График роста резерва проекта за неделю</div>

                                <div class="canvas_graphic">

                                    <div id="chart_div_1"></div>
                            
                                </div>

                            <div class="text_2 otstup">
                                <div>Сумма пополнений:</div> <div>'.$as_money_payin[0]['sum_payin'].' руб.</div>
                            </div>
                            <div class="text_2">
                                <div>Сумма выплат:</div> <div>'.$as_money_sum_payout[0]['sum_payout'].' руб.</div>
                            </div>

                            <div class="box_line_shadow">
                                <div class="sub_line_shadow">
                                    <div class="line_shadow_stroke_2">

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="graphic_2">
                            <div class="text_1">График кол-ва участников за неделю</div>
                                
                                <div class="canvas_graphic">

                                    <div id="chart_div_2"></div>
                            
                                </div>

                            <div class="text_2 otstup">
                                <div>Всего участников:</div> <div>'.$as_count_total[0]['amount_reg'].' чел.</div>
                            </div>

                            <div class="text_2">
                                <div>Новых за 24 часа:</div> <div>'.$as_count_ar[0]['amount_reg'].' чел.</div>
                            </div>

                            <div class="box_line_shadow">
                                <div class="sub_line_shadow">
                                    <div class="line_shadow_stroke_2">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="stroke_3">
                        <div class="two_tables">
<!-- ___________V________________ТАБЛИЦА СУММЫ ПОПОЛНЕНИЙ___________________V_______________________-->

                            <div class="block">
                                <div class="block_stroke_1">
                                    <img class="icon_img" src="../img/all/prostats/icon_stats_01.png" width="60px" height="60px">

                                    <div class="block_text">
                                        <div class="text_1">РЕЙТИНГ УЧАСТНИКОВ ПО СУММЕ ПОПОЛНЕНИЙ</div>
                                        <div class="text_2">
                                            <img class="icon_time" src="../img/all/prostats/icon_time.png" width="17px" height="17px">
                                            <div class="text">ОБНОВЛЕНИЕ РАЗ В 60 МИНУТ</div>
                                        </div>
                                    </div>
                                </div>

                                <table class="table">
                                    <tr><th>Логин</th> <th>Дата регистрации</th> <th>Сумма</th></tr>';

                                    $db->Query("SELECT SUM(`money_payin`) AS `money_payin`, `uid` FROM `history_money_payin` WHERE `uid` != 1 GROUP BY `uid` ORDER BY `money_payin` DESC LIMIT 5");
                                    $NumRows_1 = $db->NumRows();
                                    if ( !empty($NumRows_1) ) {

                                        while ( $assoc_money_payin = $db->FetchAssoc() ) {
                                            $QUERY_USER_M_P = $db->query_recordless("SELECT `login`, `date_reg` FROM `users` WHERE `id` = '$assoc_money_payin[uid]'");
                                            $NumRows_2 = mysqli_num_rows($QUERY_USER_M_P);
                                            if ( !empty($NumRows_2) ) {
                                                $assoc_users_m_p = mysqli_fetch_assoc($QUERY_USER_M_P);

                                                $assoc_users_m_p['date_reg'] = date( "d/m/Y в H:i", strtotime($assoc_users_m_p['date_reg']) );

                                                echo '
                                                    <tr><td>'.$assoc_users_m_p['login'].'</td> <td>'.$assoc_users_m_p['date_reg'].'</td> <td>'.round($assoc_money_payin['money_payin'], 2).' руб.</td></tr>';
                                            }
                                            @mysqli_free_result($QUERY_USER_M_P);
                                        }
                                    }
        echo '                            
                                </table>

                            </div>

<!-- ___________A________________ТАБЛИЦА СУММЫ ПОПОЛНЕНИЙ___________________A_______________________-->
<!-- ___________V________________ТАБЛИЦА ДОХОДА С РЕФЕРАЛОВ___________________V_______________________-->

                            <div class="block">
                                <div class="block_stroke_1">
                                    <img class="icon_img" src="../img/all/prostats/icon_stats_02.png" width="60px" height="60px">

                                    <div class="block_text">
                                        <div class="text_1">РЕЙТИНГ УЧАСТНИКОВ ПО ДОХОДУ С РЕФЕРАЛОВ</div>
                                        <div class="text_2">
                                            <img class="icon_time" src="../img/all/prostats/icon_time.png" width="17px" height="17px">
                                            <div class="text">ОБНОВЛЕНИЕ РАЗ В 60 МИНУТ</div>
                                        </div>
                                    </div>
                                </div>

                                <table class="table">
                                    <tr><th>Логин</th> <th>Дата регистрации</th> <th>Реф. доход</th></tr>';

                                    $db->Query("SELECT `uid`, `money_earn_refs_total` FROM `users_stats` WHERE `uid` != 1 ORDER BY `money_earn_refs_total` DESC LIMIT 5");
                                    $NumRows_1 = $db->NumRows();
                                    if ( !empty($NumRows_1) ) {

                                        while ( $assoc_u_stats = $db->FetchAssoc() ) {
                                            $QUERY_USER = $db->query_recordless("SELECT `login`, `date_reg` FROM `users` WHERE `id` = '$assoc_u_stats[uid]'");
                                            $NumRows_2 = mysqli_num_rows($QUERY_USER);
                                            if ( !empty($NumRows_2) ) {
                                                $assoc_users = mysqli_fetch_assoc($QUERY_USER);

                                                $assoc_users['date_reg'] = date( "d/m/Y в H:i", strtotime($assoc_users['date_reg']) );

                                                echo '
                                                    <tr><td>'.$assoc_users['login'].'</td> <td>'.$assoc_users['date_reg'].'</td> <td>'.round($assoc_u_stats['money_earn_refs_total'], 2).' руб.</td></tr>';
                                            }
                                            @mysqli_free_result($QUERY_USER);
                                        }
                                    }
        echo '                            
                                </table>

                            </div>
<!-- ___________A________________ТАБЛИЦА ДОХОДА С РЕФЕРАЛОВ___________________A_______________________-->
                        </div>

                        <div class="two_tables bottom_two_tables">
<!-- ___________V________________ТАБЛИЦА СУММ ЗАРАБОТКА___________________V_______________________-->

                            <div class="block">
                                <div class="block_stroke_1">
                                    <img class="icon_img" src="../img/all/prostats/icon_stats_03.png" width="60px" height="60px">

                                    <div class="block_text">
                                        <div class="text_1">РЕЙТИНГ УЧАСТНИКОВ ПО СУММЕ ЗАРАБОТКА</div>
                                        <div class="text_2">
                                            <img class="icon_time" src="../img/all/prostats/icon_time.png" width="17px" height="17px">
                                            <div class="text">ОБНОВЛЕНИЕ РАЗ В 10 МИНУТ</div>
                                        </div>
                                    </div>
                                </div>

                                <table class="table">
                                    <tr><th>Логин</th> <th>Дата регистрации</th> <th>Сумма</th></tr>';

                                    $db->Query("SELECT SUM(`money_withdrawn`) AS `money_withdrawn`, `uid` FROM `history_money_payout` WHERE `uid` != 1 AND `status` = 1 GROUP BY `uid` ORDER BY `money_withdrawn` DESC LIMIT 5");
                                    $NumRows_1 = $db->NumRows();
                                    if ( !empty($NumRows_1) ) {

                                        while ( $assoc_money_withdrawn = $db->FetchAssoc() ) {
                                            $QUERY_USER_M_P = $db->query_recordless("SELECT `login`, `date_reg` FROM `users` WHERE `id` = '$assoc_money_withdrawn[uid]'");
                                            $NumRows_2 = mysqli_num_rows($QUERY_USER_M_P);
                                            if ( !empty($NumRows_2) ) {
                                                $assoc_users_m_p = mysqli_fetch_assoc($QUERY_USER_M_P);

                                                $assoc_users_m_p['date_reg'] = date( "d/m/Y в H:i", strtotime($assoc_users_m_p['date_reg']) );
                                                echo '
                                                    <tr><td>'.$assoc_users_m_p['login'].'</td> <td>'.$assoc_users_m_p['date_reg'].'</td> <td>'.round($assoc_money_withdrawn['money_withdrawn'], 2).' руб.</td></tr>';
                                            }
                                            @mysqli_free_result($QUERY_USER_M_P);
                                        }
                                    }
        echo '                            
                                </table>

                            </div>
<!-- ___________A________________ТАБЛИЦА СУММ ЗАРАБОТКА___________________A_______________________-->
<!-- ___________V________________ТАБЛИЦА КОЛ-ВА РЕФЕРАЛОВ___________________V_______________________-->

                            <div class="block">
                                <div class="block_stroke_1">
                                    <img class="icon_img" src="../img/all/prostats/icon_stats_04.png" width="60px" height="60px">

                                    <div class="block_text">
                                        <div class="text_1">РЕЙТИНГ УЧАСТНИКОВ ПО КОЛИЧЕСТВУ РЕФЕРАЛОВ</div>
                                        <div class="text_2">
                                            <img class="icon_time" src="../img/all/prostats/icon_time.png" width="17px" height="17px">
                                            <div class="text">ОБНОВЛЕНИЕ РАЗ В 10 МИНУТ</div>
                                        </div>
                                    </div>
                                </div>

                                <table class="table">
                                    <tr><th>Логин</th> <th>Дата регистрации</th> <th>Рефералы</th></tr>';

                                    $db->Query("SELECT COUNT(`ref_lvl_2`) AS `amount_refs`, `ref_lvl_2` FROM `users` WHERE `ref_lvl_2` != 1 GROUP BY `ref_lvl_2` ORDER BY `amount_refs` DESC LIMIT 5");    //извлечение количества рефералов 2-го уровня у пользователей (!= 1 не брать id Администратора)
                                    $NumRows_0 = $db->NumRows();
                                    if ( !empty($NumRows_0) ) {
                                        while ( $row = $db->FetchAssoc() ) {
                                            $num = $row['ref_lvl_2'];
                                            foreach ($row as $key => $value) {
                                                $assoc_sum_ref_lvl_2[$num][$key] = $value; // $value = $row[$key]
                                            }
                                        }
                                    }


                                    $db->Query("SELECT COUNT(`ref`) AS `amount_refs`, `ref` FROM `users` WHERE `ref` != 1 GROUP BY `ref` ORDER BY `amount_refs` DESC LIMIT 5"); //извлечение количества рефералов 1-го уровня у пользователей
                                    $NumRows_1 = $db->NumRows();
                                    if ( !empty($NumRows_1) ) {
                                        while ( $assoc_u_stats = $db->FetchAssoc() ) {
                                            $num = $assoc_u_stats['ref'];
                                            foreach ($assoc_u_stats as $key => $value) {
                                                $assoc_amount_ref[$num][$key] = $value; // $value = $row[$key]


                                                $amount_refs_total[$num]['amount_refs'] = $assoc_amount_ref[$num]['amount_refs'] + $assoc_sum_ref_lvl_2[$num]['amount_refs'];
                                                 
                                                $QUERY_USER = $db->query_recordless("SELECT `date_reg`, `login` FROM `users` WHERE `id` = $num"); //извлечение количества рефералов 1-го уровня у пользователей
                                                $NumRows_15 = mysqli_num_rows($QUERY_USER);
                                                if ( !empty($NumRows_15) ) {
                                                    $amount_refs_total[$num]['data_user'] = mysqli_fetch_assoc($QUERY_USER); //$amount_refs_total[$num]['data_user']['login']..['date_reg']
                                                }
                                                @mysqli_free_result($QUERY_USER);
                                            }
                                        }
/*____________________V____________________СОРТИРОВКА МАССИВА ПО УБЫВАНИЮ____________________V____________________*/

                                            foreach ($amount_refs_total as $key => $row) {
                                                $amount_refs_total_sort[$key] = $row['amount_refs'];  
                                            }
                                            array_multisort($amount_refs_total_sort, SORT_DESC, $amount_refs_total);    //сортировка массива

                                            for ($n = 0; $n < 5; $n++) {

                                                $amount_refs_total[$n]['data_user']['date_reg'] = date( "d/m/Y в H:i", strtotime($amount_refs_total[$n]['data_user']['date_reg']) );
                                                
                                                echo '
                                                    <tr><td>'.$amount_refs_total[$n]['data_user']['login'].'</td><td>'.$amount_refs_total[$n]['data_user']['date_reg'].'</td><td>'.$amount_refs_total[$n]['amount_refs'].' чел.</td></tr>';
                                            }
/*____________________A____________________СОРТИРОВКА МАССИВА ПО УБЫВАНИЮ____________________A____________________*/ 


                                       /*while ( $assoc_u_stats = $db->FetchAssoc() ) {
                                            $QUERY_USER = $db->query_recordless("SELECT `id`, `login`, `date_reg` FROM `users` WHERE `id` = '$assoc_u_stats[ref]'");
                                            $NumRows_2 = mysqli_num_rows($QUERY_USER);
                                            if ( !empty($NumRows_2) ) {
                                                $assoc_users = mysqli_fetch_assoc($QUERY_USER);

                                                $amount_referals_total = $assoc_u_stats['amount_refs'] + $assoc_sum_ref_lvl_2[ $assoc_users['id'] ]['amount_refs']; //суммирования количества рефералов 1-го и 2-го уровня
                                                echo '
                                                    <tr><td>'.$assoc_users['login'].'</td><td>'.$assoc_users['date_reg'].'</td><td>'.$amount_referals_total.' чел.'.$assoc_amount_ref[2]['amount_refs'].'</td></tr>';
                                            }
                                            @mysqli_free_result($QUERY_USER);
                                        }*/
                                    }
        echo '                            
                                </table>

                            </div>
<!-- ___________A________________ТАБЛИЦА КОЛ-ВА РЕФЕРАЛОВ___________________A_______________________-->

                        </div>

                        <div class="two_tables bottom_two_tables">
<!-- ___________V________________ПОСЛЕДНИЕ 20 ВЫПЛАТ___________________V_______________________-->';


        echo '
                            <div class="block">
                                <div class="block_stroke_1">
                                    <img class="icon_img" src="../img/all/prostats/icon_stats_05.png" width="60px" height="60px">

                                    <div class="block_text">
                                        <div class="text_1">ПОСЛЕДНИЕ 20 ВЫПЛАТ УЧАСТНИКАМ</div>
                                        <div class="text_2">
                                            <img class="icon_time" src="../img/all/prostats/icon_time.png" width="17px" height="17px">
                                            <div class="text">ЗА ПОСЛЕДНИЕ 24 ЧАСА: '.$as_money_withdrawn_day['sum_withdrawn'].' РУБ.</div>
                                        </div>
                                    </div>
                                </div>

                                <table class="table">
                                    <tr><th>Логин</th> <th>Платежная система</th> <th>Сумма</th></tr>';

                                    $db->Query("SELECT `uid`, `money_withdrawn`, `payment_system`, `date` FROM `history_money_payout` WHERE `uid` != 1 AND `status` = 1 ORDER BY `date` DESC LIMIT 20");
                                    $NumRows = $db->NumRows();
                                    if ( !empty($NumRows) ) {

                                        while ( $assoc_withdrawn = $db->FetchAssoc() ) {
                                            $QUERY_USER_W = $db->query_recordless("SELECT `login` FROM `users` WHERE `id` = '$assoc_withdrawn[uid]'");
                                            $NumRows_2 = mysqli_num_rows($QUERY_USER_W);
                                            if ( !empty($NumRows_2) ) {
                                                $assoc_users_w = mysqli_fetch_assoc($QUERY_USER_W);

                                                switch ($assoc_withdrawn['payment_system']) {
                                                    case 'payeer':
                                                        $assoc_withdrawn['payment_system'] = '<img class="img_ps_system" src="../img/auth/payout/ps_1.png">';
                                                        break;
                                                    case 'freekassa':
                                                        $assoc_withdrawn['payment_system'] = '<img class="img_ps_system" src="../img/auth/payout/ps_2.png">';
                                                        break;
                                                    case 'ADVCASH':
                                                        $assoc_withdrawn['payment_system'] = '<img class="img_ps_system" src="../img/auth/payout/ps_3.png">';
                                                        break;
                                                    case 'Яндекс.Деньги':
                                                        $assoc_withdrawn['payment_system'] = '<img src="../img/auth/payout/ps_4.png" height="30px" width: "auto">';
                                                        break;
                                                    case 'QIWI':
                                                        $assoc_withdrawn['payment_system'] = '<img src="../img/auth/payout/ps_5.png" height="35px" width: "auto">';
                                                        break;
                                                    case 'БИЛАЙН':
                                                        $assoc_withdrawn['payment_system'] = '<img class="img_ps_system" src="../img/auth/payout/ps_6.png">';
                                                        break;
                                                    case 'МЕГАФОН':
                                                        $assoc_withdrawn['payment_system'] = '<img src="../img/auth/payout/ps_7.png" height="30px" width: "auto">';
                                                        break;
                                                    case 'МТС':
                                                        $assoc_withdrawn['payment_system'] = '<img class="img_ps_system" src="../img/auth/payout/ps_8.png">';
                                                        break;
                                                    case 'ТЕЛЕ 2':
                                                        $assoc_withdrawn['payment_system'] = '<img class="img_ps_system" src="../img/auth/payout/ps_9.png">';
                                                        break;
                                                    case 'MASTERCARD':
                                                        $assoc_withdrawn['payment_system'] = '<img src="../img/auth/payout/ps_10.png" height="40px" width: "auto">';
                                                        break;
                                                    case 'VISA':
                                                        $assoc_withdrawn['payment_system'] = '<img class="img_ps_system" src="../img/auth/payout/ps_11.png">';
                                                        break;
                                                    case 'MAESTRO':
                                                        $assoc_withdrawn['payment_system'] = '<img src="../img/auth/payout/ps_12.png" height="40px" width: "auto">';
                                                        break;
                                                    case 'МИР':
                                                        $assoc_withdrawn['payment_system'] = '<img src="../img/auth/payout/ps_13.png" height="40px" width: "auto">';
                                                        break;

                                                    default:
                                                        $assoc_withdrawn['payment_system'] = '<img class="img_ps_system" src="../img/auth/payout/ps_1.png">';
                                                        break;    
                                                }

                                                echo '
                                                    <tr><td>'.$assoc_users_w['login'].'</td> <td>'.$assoc_withdrawn['payment_system'].'</td> <td>'.round($assoc_withdrawn['money_withdrawn'], 2).' руб.</td></tr>';
                                            }
                                            @mysqli_free_result($QUERY_USER_W);
                                        }
                                    }
        echo '                            
                                </table>

                            </div>

<!-- ___________A________________ПОСЛЕДНИЕ 20 ВЫПЛАТ___________________A_______________________-->                        
<!-- ___________V________________ПОСЛЕДНИЕ 20 ПОПОЛНЕНИЙ___________________V_______________________-->

                            <div class="block">
                                <div class="block_stroke_1">
                                    <img class="icon_img" src="../img/all/prostats/icon_stats_06.png" width="60px" height="60px">

                                    <div class="block_text">
                                        <div class="text_1">ПОСЛЕДНИЕ 20 ПОПОЛНЕНИЙ БАЛАНСА</div>
                                        <div class="text_2">
                                            <img class="icon_time" src="../img/all/prostats/icon_time.png" width="17px" height="17px">
                                            <div class="text">ЗА ПОСЛЕДНИЕ 24 ЧАСА: '.$as_money_payin_day[0]['sum_payin'].' РУБ.</div>
                                        </div>
                                    </div>
                                </div>

                                <table class="table">
                                    <tr><th>Логин</th> <th>Платежная система</th> <th>Сумма</th></tr>';

                                    $db->Query("SELECT `uid`, `money_payin`, `payment_system`, `date_payin` FROM `history_money_payin` WHERE `uid` != 1 ORDER BY `date_payin` DESC LIMIT 20");   /*checkpoint чекпоинт сортировка по дате*/
                                    $NumRows = $db->NumRows();
                                    if ( !empty($NumRows) ) {

                                        while ( $assoc_payin = $db->FetchAssoc() ) {
                                            $QUERY_USER_P = $db->query_recordless("SELECT `login` FROM `users` WHERE `id` = '$assoc_payin[uid]'");
                                            $NumRows_2 = mysqli_num_rows($QUERY_USER_P);
                                            if ( !empty($NumRows_2) ) {
                                                $assoc_users_p = mysqli_fetch_assoc($QUERY_USER_P);

                                                switch ($assoc_payin['payment_system']) {
                                                    case 'payeer':
                                                        $assoc_payin['payment_system'] = '<img class="img_ps_system" src="../img/auth/payout/ps_1.png">';
                                                        break;
                                                    case 'freekassa':
                                                        $assoc_payin['payment_system'] = '<img src="../img/auth/payout/ps_2.png" height="30px" width: "auto">';
                                                        break;
                                                    case 'ADVCASH':
                                                        $assoc_payin['payment_system'] = '<img class="img_ps_system" src="../img/auth/payout/ps_3.png">';
                                                        break;
                                                    case 'Яндекс.Деньги':
                                                        $assoc_payin['payment_system'] = '<img src="../img/auth/payout/ps_4.png" height="30px" width: "auto">';
                                                        break;
                                                    case 'QIWI':
                                                        $assoc_payin['payment_system'] = '<img src="../img/auth/payout/ps_5.png" height="35px" width: "auto">';
                                                        break;
                                                    case 'БИЛАЙН':
                                                        $assoc_payin['payment_system'] = '<img src="../img/auth/payout/ps_6.png" height="30px" width: "auto">';
                                                        break;
                                                    case 'МЕГАФОН':
                                                        $assoc_payin['payment_system'] = '<img src="../img/auth/payout/ps_7.png" height="30px" width: "auto">';
                                                        break;
                                                    case 'МТС':
                                                        $assoc_payin['payment_system'] = '<img class="img_ps_system" src="../img/auth/payout/ps_8.png">';
                                                        break;
                                                    case 'ТЕЛЕ 2':
                                                        $assoc_payin['payment_system'] = '<img class="img_ps_system" src="../img/auth/payout/ps_9.png">';
                                                        break;
                                                    case 'MASTERCARD':
                                                        $assoc_payin['payment_system'] = '<img src="../img/auth/payout/ps_10.png" height="40px" width: "auto">';
                                                        break;
                                                    case 'VISA':
                                                        $assoc_payin['payment_system'] = '<img class="img_ps_system" src="../img/auth/payout/ps_11.png">';
                                                        break;
                                                    case 'MAESTRO':
                                                        $assoc_payin['payment_system'] = '<img src="../img/auth/payout/ps_12.png" height="40px" width: "auto">';
                                                        break;
                                                    case 'МИР':
                                                        $assoc_payin['payment_system'] = '<img src="../img/auth/payout/ps_13.png" height="40px" width: "auto">';
                                                        break;

                                                    default:
                                                        $assoc_payin['payment_system'] = '<img class="img_ps_system" src="../img/auth/payout/ps_1.png">';
                                                        break;
                                                }
                                                echo '
                                                    <tr><td>'.$assoc_users_p['login'].'</td> <td>'.$assoc_payin['payment_system'].'</td> <td>'.round($assoc_payin['money_payin'], 2).' руб.</td></tr>';
                                            }
                                            @mysqli_free_result($QUERY_USER_P);
                                        }
                                    }
        echo '                            
                                </table>

                            </div>

<!-- ___________A________________ПОСЛЕДНИЕ 20 ПОПОЛНЕНИЙ___________________A_______________________-->

                        </div>
                    </div>

                </div>
            </div>';

    bottom_guest_second();

    echo '
        </div>';

    bottom_guest();
?>