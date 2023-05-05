<?php
    header("Content-Type: text/html; charset=utf-8");  // стандартная установка кодировки
?>
<?php
if ($_COOKIE['play_f'] == 1) {
    (int)$number_user[1] = $_COOKIE['number_1'];
    (int)$number_user[2] = $_COOKIE['number_2'];
    (int)$number_user[3] = $_COOKIE['number_3'];
    (int)$number_user[4] = $_COOKIE['number_4'];
    (int)$number_user[5] = $_COOKIE['number_5'];
    (int)$number_user[6] = $_COOKIE['number_6'];

    $all_numbers_diapazon = true;
    for ($n = 1; $n <= 6; $n++) {
        if ($number_user[$n] < 0 or $number_user[$n] > 9) {
            $all_numbers_diapazon = false;
        }
    }


    if ($all_numbers_diapazon == true) {
        $db->Query("SELECT * FROM `users_data` WHERE `uid` = '$_SESSION[id]'");
        $NumRows = $db->NumRows();
        if ( !empty($NumRows) ) {
            $assoc_u_d = $db->FetchAssoc();
        }
        else {
            $assoc_u_d['chance'] = 0;
        }

        $db->Query("SELECT * FROM `parametres_free_chance` WHERE `uid` = '$_SESSION[id]'");
        $NumRows = $db->NumRows();
        if ( !empty($NumRows) ) {
            $assoc_params = $db->FetchAssoc();
        }
        else {
            $assoc_params['date_ch_day'] = 0;
        }

        if ( ($assoc_u_d['chance'] + $assoc_params['date_ch_day']) > 0) {
            if ($assoc_u_d['chance'] != 0) {
                $db->Query("UPDATE `users_data` SET `chance` = (`chance` - 1) WHERE `uid` = '$_SESSION[id]'");
                $assoc_u_d['chance'] = $assoc_u_d['chance'] - 1;
            }
            else {
                $db->Query("UPDATE `parametres_free_chance` SET `date_ch_day` = (`date_ch_day` - 1) WHERE `uid` = '$_SESSION[id]'");
                $assoc_params['date_ch_day'] = $assoc_params['date_ch_day'] - 1;
            }

            $count_guess = 0;
            for ($n = 1; $n <= 6; $n++) {
                $number_system[$n] = random_int(0, 6);

                if ($number_user[$n] == $number_system[$n] ) {
                    $count_guess++;

                    if ($count_guess == 5) {   //перерандомируем число
                        $number_system[$n] = random_int(0, 6);
                        if ($number_user[$n] == $number_system[$n] ) {
                            $number_system[$n] = random_int(0, 6);

                            if ($number_user[$n] != $number_system[$n] ) {  //если число уже не равно числу пользователя..
                                $count_guess--; //..сбрасываем счетчик угаданных чисел
                            }    
                        }
                        else if ($number_user[$n] != $number_system[$n] ) {  //если число уже не равно числу пользователя..
                            $count_guess--; //..сбрасываем счетчик угаданных чисел
                        }
                    }
                    else if ($count_guess == 6) {   //перерандомируем число
                        $number_system[$n] = random_int(0, 6);
                        if ($number_user[$n] == $number_system[$n] ) {
                            $number_system[$n] = random_int(0, 6);
                            if ($number_user[$n] == $number_system[$n] ) {
                                $number_system[$n] = random_int(0, 6);
                                
                                if ($number_user[$n] != $number_system[$n] ) {  //если число уже не равно числу пользователя..
                                    $count_guess--; //..сбрасываем счетчик угаданных чисел
                                } 
                            }

                            if ($number_user[$n] != $number_system[$n] ) {  //если число уже не равно числу пользователя..
                                $count_guess--; //..сбрасываем счетчик угаданных чисел
                            }    
                        }
                        else if ($number_user[$n] != $number_system[$n] ) {  //если число уже не равно числу пользователя..
                            $count_guess--; //..сбрасываем счетчик угаданных чисел
                        }
                    }

                }
            }

            switch ($count_guess) { /*количество угаданных чисел*/
                case 1:
                    $earn = 0.01;
                    break;
                case 2:
                    $earn = 0.1;
                    break;
                case 3:
                    $earn = 1;
                    break;
                case 4:
                    $earn = 10;
                    break;
                case 5:
                    $earn = 100;
                    break;
                case 6:
                    $earn = 3000;
                    break;
                default:
                    $earn = 0;
                    break;
            }

            if ($earn != 0) {
                $db->Query("UPDATE `users_data` SET `balance_buy` = (`balance_buy` + $earn) WHERE `uid` = '$_SESSION[id]'");
                $db->Query("UPDATE `users_stats` SET `earn_free_chance` = (`earn_free_chance` + $earn) WHERE `uid` = '$_SESSION[id]'");

                $db->Query('INSERT INTO `get_money_free_chance` VALUES (NULL, "'.$_SESSION['id'].'", "'.$earn.'", NOW())');

                $total_text = '<div style="color: #42ad00;">Победа! Ваш выигрыш <span>'.$earn.'</span> руб.</div>';
            }
            else {
                $total_text = '<div style="color: #c50d0d;">Вы проиграли</div>';
            }

            for ($n = 1; $n <= 6; $n++) {
                if ($number_user[$n] == $number_system[$n]) {
                    $style[$n] = "color:#39b11b;";
                    $class[$n] = "win";
                } 
                else {
                    $style[$n] = "color:#da2e2e;";
                    $class[$n] = "lose";
                }
            }

            $message = 'Ваши числа: <span>'.$number_user[1].'-'.$number_user[2].'-'.$number_user[3].'-'.$number_user[4].'-'.$number_user[5].'-'.$number_user[6].'</span>';

            echo '
                
            ';
        }
        else {
            $message = 'У вас закончились шансы  <a href="free_chance_getchance">(Как получить?)</a>';
        }
    }
    else {
        $message = 'Цифра должна быть в диапазоне от 0 до 9';
    }
}
else {
    $message = 'Ошибка запроса';
}

echo '
    <div>
        <div id="message_get">'.$message.'</div>
        <div id="total_text_get">'.$total_text.'</div>
        <div id="result_numbers">
            <input class="number_lot '.$class[1].'" type="number" id="number_1" value="'.$number_system[1].'">
            <input class="number_lot '.$class[2].'" type="number" id="number_2" value="'.$number_system[2].'">
            <input class="number_lot '.$class[3].'" type="number" id="number_3" value="'.$number_system[3].'">
            <input class="number_lot '.$class[4].'" type="number" id="number_4" value="'.$number_system[4].'">
            <input class="number_lot '.$class[5].'" type="number" id="number_5" value="'.$number_system[5].'">
            <input class="number_lot '.$class[6].'" type="number" id="number_6" value="'.$number_system[6].'">
        </div>
        <div id="chance">'.($assoc_u_d['chance'] + $assoc_params['date_ch_day']).'</div>
    </div>';
