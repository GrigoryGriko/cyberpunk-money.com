<?php
    header("Content-Type: text/html; charset=utf-8");  // стандартная установка кодировки
?>
<?php
top_auth('Таблица внимательности', 'auth/games/style/schulte_tabstyle(list_complete)');
?>
<?php

echo '
    <div class="box_strokeafter_2_center_panel">';

MessageShow();

echo '
            <div class="centerer_e1s3_center_panel">
                <div class="element_1_stroke_3_box_1_center_panel">
                    <div class="box_elements_e1s3b1cp">
                        <div class="stroka_1"> 
                            <a href="/schulte_tab_start" class="l_3">Список текущих игр</a>
                            <a href="/payin_gamemoney" class="l_1">Пополнить игровой баланс</a>
                            <a href="/payout_gamemoney" class="l_2">Вывести игровой баланс</a>
                        </div>
                        <p class="stroka_2"> 
                            Таблица внимательности - это игра на деньги с реальными противниками. В данной игре Вы должны найти числа по порядку от 1 до 25 как можно быстрее, кто справился с этим быстрее, тот и победил. Числа каждый раз при создании игры числа располагаются в таблице случайным образом. Вы можете как создать игру, сыграть в нее, и ожидать завершения действий соперника, так и присоединится к уже созданной. Для Вас и вашего соперника числа расположены в одном и том же порядке. Если соперник не завершает игру в течение суток, то она автоматически завершается и Вам присуждается победа. За ошибочное нажатие на число добавляется 30 штрафных секунд. Коэффициент на выигрыш - 1,92.
                        </p>
                        <div class="text_e1s3b1cp">    

                            <form method="post" action="request/schulte_tabrequest(start)">
                                Ваша ставка (руб.):
                                <input class="input_sum_bet" type="number" step="0.01" name="sum_bet" value="0">
                                <input type="hidden" name="user_id" value="'.$_SESSION['id'].'">
                                <button type="submit">Создать игру</button>
                            </form>
                            <input type="hidden" id="cookie_sorting" value="0">';


echo ' 
                        </div>
                    </div>
                </div>
            </div>

            <div class="element_1_stroke_4_box_1_center_panel">
                
            </div>

            <div id="box_stroke_5_box_1_center_panel">

                <table class="table_e1s4b1c_2">
                    <tr>
                        <th id="login_creater"><div class="cell">Создатель</div></th>
                        <th id="login_invite"><div class="cell">Присоединившийся</div></th>
                        <th id="sum_bet"><div class="cell">Ставка (руб.)</div></th>
                        <th id="sum_bet"><div class="cell">Выигрыш (руб.)</div></th>
                        <th id="date_create"><div class="cell">Время окончания</div></th>
                        <th>Результат</th>
                    </tr>
                </table>
                <div id="div_table_e1s4b1c">
                    <table class="table_e1s4b1c">';

/*-------история получения бонусов----VVVVVVVVVVVVV-----*/

function day ($date) {
    $value = substr($date, 8, 2);
    return $value;
}
function mounth ($date) {
    $value = substr($date, 5, 2);
    return $value;
}
function year ($date) {
    $value = substr($date, 0, 4);
    return $value;
}


function hours_minutes ($date) {
    $value = substr($date, 11, 5);
    return $value;
}
$db->Query("SELECT * FROM `games_schulte_tab` WHERE `id_user_win` != 0 AND (`user_create_id` = '$_SESSION[id]' OR `user_invite_id` = '$_SESSION[id]') ORDER BY `date_user_2_end` DESC LIMIT 150");
$NumRows = $db->NumRows();

if ( !empty($NumRows) ) {
    $a = 0;     //переменная чередует цвета строк
    while ( $row = $db->FetchAssoc() ) {
        
        $time_user_1 = strtotime($row['date_user_1_end']) - strtotime($row['date_user_1_start']) + $row['penalty_time_seconds_user_1'];    //время первого игрока
        $time_user_2 = strtotime($row['date_user_2_end']) - strtotime($row['date_user_2_start']) + $row['penalty_time_seconds_user_2'];    //время второго игрока


        $QUERY_SELECT1 = $db->Query_recordless("SELECT `id`, `login` FROM `users` WHERE `id` = '$row[user_create_id]'");
        $NumRows1 = mysqli_num_rows($QUERY_SELECT1);
        if ( !empty($NumRows) ) {
            $assoc_u1 = mysqli_fetch_assoc($QUERY_SELECT1);
            @mysqli_free_result($QUERY_SELECT1);
        }
        $QUERY_SELECT2 = $db->Query_recordless("SELECT `id`, `login` FROM `users` WHERE `id` = '$row[user_invite_id]'");
        $NumRows2 = mysqli_num_rows($QUERY_SELECT2);
        if ( !empty($NumRows) ) {
            $assoc_u2 = mysqli_fetch_assoc($QUERY_SELECT2);
            @mysqli_free_result($QUERY_SELECT2);
        }

            $kef = 1.92;
            if ($row['id_user_win'] == $_SESSION['id']) {
                $result_game = 'победа';
                $obnulenie = 1;
                $style_text = 'color: #009400';
            }
            else {
                $result_game = 'поражение';
                $obnulenie = 0;
                $style_text = 'color: #d60000';
            }
            $kef = 1.92;
            $total_win = $row['sum_bet'] * $kef * $obnulenie;

            $a += 1;
 
            if ( ($a % 2) != 0) {
                $style_stroke = 'background: #b9b9b9;';
            }
            else {
                $style_stroke = 'background: #d6d6d6';
            }

            $date_end = day($row['date_user_2_end']).'/'.mounth($row['date_user_2_end']).'/'.year($row['date_user_2_end']).' в '.hours_minutes($row['date_user_2_end']).'';
        
            echo
                '<tr style="'.$style_stroke.'">
                    <td>
                        <a href="/user_wall?id='.$row['user_create_id'].'">'.$assoc_u1['login'].' ('.$time_user_1.' сек.)</a>
                    </td>
                    <td>
                        <a href="/user_wall?id='.$row['user_invite_id'].'">'.$assoc_u2['login'].' ('.$time_user_2.' сек.)</a>
                    </td> 
                    <td>
                        '.round($row['sum_bet'], 2).'   
                    </td>
                    <td style="'.$style_text.'">
                        '.$total_win.'   
                    </td>
                    <td>
                        '.$date_end.'   
                    </td>
                    <td style="'.$style_text.'">
                        '.$result_game.'  
                    </td>
                </tr>';
    }
}

echo
                    '</table>
                </div>

            </div>
        </div>
    </div>
    ';

bottom_auth();

?>