<?php
usleep(250000);
function query_money_payin($date1, $date2, $time) {
    global $db;

    switch ($time) {
        case 24:
            $time_period = $time * 3600;
            break;
        default:
            $time_period = $time * 24 * 3600;
            break;
    }

    $start_new_period = strtotime($date1) + $time_period;

    if ( $start_new_period <= time() ) {
        $start_new_period_formated = date('Y-m-d h:m:s', $start_new_period);
        $db->Query("UPDATE `leadrace_date` SET `date_start` = NOW() WHERE `time_period` = $time");

        switch ($time) {
            case 24:
                $leader_bonus_percent_income = 'leader_bonus_percent_income1';
                break;
            case 7:
                $leader_bonus_percent_income = 'leader_bonus_percent_income2';
                break;
            case 30:
                $leader_bonus_percent_income = 'leader_bonus_percent_income3';
                break;
            case 365:
                $leader_bonus_percent_income = 'leader_bonus_percent_income4';
                break;
        
            default:
                $leader_bonus_percent_income = 'leader_bonus_percent_income1';
                break;
        }

        $db->Query("SELECT `id`, ".$leader_bonus_percent_income." FROM `users` WHERE ".$leader_bonus_percent_income." != 0");    //не знаю для чего я это делал
        $numrows = $db->NumRows();
        if ( !empty($numrows) ) {
            while ( $row = $db->FetchAssoc() ) {
                $num = $row['id'];
                foreach ($row as $key => $value) {
                    $assoc_percent[$num][$key] = $value; // $value = $row[$key]
                }
            }

        $db->Query('INSERT INTO `user_date_start_end_percent_per_leadrace` VALUES ("", "'.$_SESSION['id'].'", ".$leader_bonus_percent_income.", 0, NOW(), NOW())');
        }

        $db->Query("UPDATE `users` SET ".$leader_bonus_percent_income." = 0");    //не знаю для чего я это делал. ТА Чтобы обнулить процент бонуса по истечении срока
    }
}


//Лидеры за 24 часа

$db->Query("SELECT * FROM `leadrace_date`");
$numrows_leadrace = $db->NumRows();
if ( !empty($numrows_leadrace) ) {
    while ( $row = $db->FetchAssoc() ) {
        $num = $row['time_period'];
        foreach ($row as $key => $value) {
            $assoc_leadrace[$num][$key] = $value; // $value = $row[$key]
        }
    }

    $section_date2 = date('y/m/j').' 23:59:59.999';     //вычисление текущей даты

    //Лидеры за 24 часа'; 
    query_money_payin($assoc_leadrace[24]['date_start'], $section_date2, 24);

    //Лидеры за 7 дней';                            
    query_money_payin($assoc_leadrace[7]['date_start'], $section_date2, 7);

    //Лидеры за 30 дней';             
    query_money_payin($assoc_leadrace[30]['date_start'], $section_date2, 30);

    //Лидеры за 365 дней';
    query_money_payin($assoc_leadrace[365]['date_start'], $section_date2, 365);
}

?>