<?php
    header("Content-Type: text/html; charset=utf-8");  // стандартная установка кодировки
?>
<?php

if ($_COOKIE['choose_number_f']) {
    (int)$_COOKIE['number_choose'];
    $max_number = 25;   //номер, завершающий игру
    $penalty_time_seconds = 30;

    $db->Query("SELECT * FROM `games_schulte_tab` WHERE `id` = '$_COOKIE[id_game]'");
    $NumRows = $db->NumRows();
    if ( !empty($NumRows) ) {
        $as_games_data_first = $db->FetchAssoc();

        if ($as_games_data_first['ready_play_user_2'] == 0 ) {
           
            $db->Query("SELECT * FROM `games_schulte_tab` WHERE `id` = '$_COOKIE[id_game]' AND `user_create_id` = '$_SESSION[id]'");
            $NumRows = $db->NumRows();
            if ( !empty($NumRows) ) {
                $as_games_data = $db->FetchAssoc();

                $next_number = ($as_games_data['current_number_user_1'] + 1);

                if ($as_games_data['current_number_user_1'] == ($max_number - 1) and $_COOKIE['number_choose'] == $max_number ) {     //если следующий номер завершающий и выбранный номер равен завершающему
                    $db->Query("UPDATE `games_schulte_tab` SET `date_user_1_end` = NOW(), `current_number_user_1` = '$max_number', `ready_play_user_2` = 1 WHERE `id` = '$_COOKIE[id_game]' AND `user_create_id` = '$_SESSION[id]'");
                    $text_next_number = 'Игра завершена';
                }
                else if ($as_games_data['current_number_user_1'] == $max_number) {
                    $message = 'Игра завершена';
                    $text_next_number = 'Игра завершена';
                }
                else if ($_COOKIE['number_choose'] == ($as_games_data['current_number_user_1'] + 1) ) {
                    $db->Query("UPDATE `games_schulte_tab` SET `current_number_user_1` = '$_COOKIE[number_choose]' WHERE `id` = '$_COOKIE[id_game]' AND `user_create_id` = '$_SESSION[id]'");
                    $text_next_number = 'Найдите '.($_COOKIE['number_choose'] + 1);
                }
                else {
                    $db->Query("UPDATE `games_schulte_tab` SET `penalty_time_seconds_user_1` = (`penalty_time_seconds_user_1` + '$penalty_time_seconds') WHERE `id` = '$_COOKIE[id_game]' AND `user_create_id` = '$_SESSION[id]'");
                    $message = 'Неверно, Вам добавлено штрафное время +30 сек';
                    $text_next_number = 'Найдите '.($as_games_data['current_number_user_1'] + 1);
                }
            }
            else {
                $message = 'Ошибка0x1';
            }

        }
        else if ($as_games_data_first['ready_play_user_2'] == 1) {

            $db->Query("SELECT * FROM `games_schulte_tab` WHERE `id` = '$_COOKIE[id_game]'");
            $NumRows = $db->NumRows();
            if ( !empty($NumRows) ) {
                $as_games_data = $db->FetchAssoc();
                $next_number = ($as_games_data['current_number_user_2'] + 1);

                if ($as_games_data['current_number_user_2'] == ($max_number - 1) and $_COOKIE['number_choose'] == $max_number ) {     //если следующий номер завершающий и выбранный номер равен завершающему

                    $COMISSION = 0.08; //8%
                    $db->Query("UPDATE `games_schulte_tab` SET `date_user_2_end` = NOW(), `current_number_user_2` = '$max_number' WHERE `id` = '$_COOKIE[id_game]' AND `user_invite_id` = '$_SESSION[id]'");

                    $db->Query("SELECT * FROM `games_schulte_tab` WHERE `id` = '$_COOKIE[id_game]'");
                    $NumRows = $db->NumRows();
                    if ( !empty($NumRows) ) {
                        $as_games_data_2 = $db->FetchAssoc();
                    }
                    $time_user_1 = strtotime($as_games_data_2['date_user_1_end']) - strtotime($as_games_data_2['date_user_1_start']) + $as_games_data_2['penalty_time_seconds_user_1'];
                    $time_user_2 = strtotime($as_games_data_2['date_user_2_end']) - strtotime($as_games_data_2['date_user_2_start']) + $as_games_data_2['penalty_time_seconds_user_2'];

                    $sum_percente = $as_games_data_2['sum_bet'] * $COMISSION;
                    $as_games_data_2['sum_bet'] = $as_games_data_2['sum_bet'] - $sum_percente;  //итого выиграно

                    if ($time_user_1 < $time_user_2) {
                        $user_id_win = $as_games_data_2['user_create_id'];

                        $db->Query("UPDATE `games_schulte_tab` SET `id_user_win` = '$user_id_win' WHERE `id` = '$_COOKIE[id_game]'");
                        $db->Query("UPDATE `users_data` SET `balance_buy` = (`balance_buy` + '$as_games_data_2[sum_bet]') WHERE `uid` = '$user_id_win'");
                    }
                    else if ($time_user_1 > $time_user_2) {
                        $user_id_win = $as_games_data_2['user_invite_id'];

                        $db->Query("UPDATE `games_schulte_tab` SET `id_user_win` = '$user_id_win' WHERE `id` = '$_COOKIE[id_game]'");
                        $db->Query("UPDATE `users_data` SET `balance_buy` = (`balance_buy` + '$as_games_data_2[sum_bet]') WHERE `uid` = '$user_id_win'");
                    }
                    else {
                        $u_w = rand(1, 2);
                        if ($u_w == 1) $user_id_win = $as_games_data_2['user_create_id'];
                        else $user_id_win = $as_games_data_2['user_invite_id'];

                        $db->Query("UPDATE `games_schulte_tab` SET `id_user_win` = '$user_id_win' WHERE `id` = '$_COOKIE[id_game]'");
                        $db->Query("UPDATE `users_data` SET `balance_buy` = (`balance_buy` + '$as_games_data_2[sum_bet]') WHERE `uid` = '$user_id_win'");
                    }
                    $db->Query("UPDATE `system_earn_game_schulte_tab` SET `earn_comission_game` = (`earn_comission_game` + '$sum_percente')");    //запись данных о заработке системы

                    $text_next_number = 'Игра завершена';       //момент завершения игры вторым игроком
                }
                else if ($as_games_data['current_number_user_2'] == $max_number) {
                    $message = 'Игра завершена';
                    $text_next_number = 'Игра завершена';
                }
                else if ($_COOKIE['number_choose'] == ($as_games_data['current_number_user_2'] + 1) ) {
                    $db->Query("UPDATE `games_schulte_tab` SET `current_number_user_2` = '$_COOKIE[number_choose]' WHERE `id` = '$_COOKIE[id_game]'");
                    $text_next_number = 'Найдите '.($_COOKIE['number_choose'] + 1);
                }
                else {
                    $db->Query("UPDATE `games_schulte_tab` SET `penalty_time_seconds_user_2` = (`penalty_time_seconds_user_2` + '$penalty_time_seconds') WHERE `id` = '$_COOKIE[id_game]'");
                    $message = 'Неверно, Вам добавлено штрафное время +30 сек';
                    $text_next_number = 'Найдите '.($as_games_data['current_number_user_2'] + 1);
                }
            }
            else {
                $message = 'Для Вас игра завершена';
            }

        }
        else {
            $message = 'Игра завершена';
        }
    }         
}
else {
    $message = 'Ошибка0x3';
}
echo '
    <div>
        <div id="message_get">'.$message.'</div>
        <div id="next_number_get">'.$text_next_number.'</div>
    </div>';
?>