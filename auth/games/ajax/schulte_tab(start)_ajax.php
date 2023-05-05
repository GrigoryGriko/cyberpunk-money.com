<?php
    header("Content-Type: text/html; charset=utf-8");  // стандартная установка кодировки
?>
<?php
echo '  
        <div>
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

if ($_COOKIE['sorting'] == 'login_creater_direct') $db->Query("SELECT * FROM `games_schulte_tab` WHERE `id_user_win` = 0 ORDER BY `user_create_id` LIMIT 150");
else if ($_COOKIE['sorting'] == 'login_creater_reverse') $db->Query("SELECT * FROM `games_schulte_tab` WHERE `id_user_win` = 0 ORDER BY `user_create_id` DESC LIMIT 150");

else if ($_COOKIE['sorting'] == 'login_invite_direct') $db->Query("SELECT * FROM `games_schulte_tab` WHERE `id_user_win` = 0 ORDER BY `user_invite_id` LIMIT 150");
else if ($_COOKIE['sorting'] == 'login_invite_reverse') $db->Query("SELECT * FROM `games_schulte_tab` WHERE `id_user_win` = 0 ORDER BY `user_invite_id` DESC LIMIT 150");

else if ($_COOKIE['sorting'] == 'sum_bet_direct') $db->Query("SELECT * FROM `games_schulte_tab` WHERE `id_user_win` = 0 ORDER BY `sum_bet` LIMIT 150");
else if ($_COOKIE['sorting'] == 'sum_bet_reverse') $db->Query("SELECT * FROM `games_schulte_tab` WHERE `id_user_win` = 0 ORDER BY `sum_bet` DESC LIMIT 150");

else if ($_COOKIE['sorting'] == 'date_create_direct') $db->Query("SELECT * FROM `games_schulte_tab` WHERE `id_user_win` = 0 ORDER BY `date_create` LIMIT 150");
else if ($_COOKIE['sorting'] == 'date_create_reverse') $db->Query("SELECT * FROM `games_schulte_tab` WHERE `id_user_win` = 0 ORDER BY `date_create` DESC LIMIT 150");

else $db->Query("SELECT * FROM `games_schulte_tab` WHERE `id_user_win` = 0 ORDER BY `date_create` DESC LIMIT 150");

    //готовая для второго игрока, не законченная       
$NumRows = $db->NumRows();

if ( !empty($NumRows) ) {
    $a = 0;       
    while ( $row = $db->FetchAssoc() ) {
        $show_game = true;

        if ($row['user_invite_id'] == 0 and $row['user_create_id'] == $_SESSION['id']) {
            if ($row['ready_play_user_2'] == 0) {
                $action = '<a href="/schulte_tab_(event)?i='.$row['hash_link_game'].'">Продолжить игру</a> <a href="/schulte_tab_(event)?i='.$row['hash_link_game'].'&action=delete">Удалить</a>';
            }
            else {
                $action = 'В ожидании соперника';
            }
        }
        else if ($row['user_invite_id'] == 0 and $row['user_create_id'] != $_SESSION['id']) {
            if ($row['ready_play_user_2'] == 1) {
                $action = '<a href="/schulte_tab_(event)?i='.$row['hash_link_game'].'">Играть</a>';
            }
            else {
                $show_game = false;
            }
        }
        else if ($row['user_invite_id'] == 0 and $row['user_create_id'] != $_SESSION['id']) {
            if ($row['ready_play_user_2'] == 0) {
                $action = '<a href="/schulte_tab_(event)?i='.$row['hash_link_game'].'">Продолжить игру</a>';
            }
            else {
                $show_game = false;
            }
        }
        else if ($row['user_invite_id'] == $_SESSION['id']) {
            $action = '<a href="/schulte_tab_(event)?i='.$row['hash_link_game'].'">Продолжить игру</a>';
        }
        else {
            $action = 'Игра идет';
        }

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


        if ($show_game == true) {
            $a += 1;

            if ( ($a % 2) != 0) {
                $style_stroke = 'background: #b9b9b9;';
            }
            else {
                $style_stroke = 'background: #d6d6d6';
            }

            $date_create = day($row['date_create']).'/'.mounth($row['date_create']).'/'.year($row['date_create']).' в '.hours_minutes($row['date_create']).'';
        
            echo
                '<tr style="'.$style_stroke.'">
                    <td>
                        <a href="/user_wall?id='.$row['user_create_id'].'">'.$assoc_u1['login'].'</a>
                    </td>
                    <td>
                        <a href="/user_wall?id='.$row['user_invite_id'].'">'.$assoc_u2['login'].'</a>
                    </td> 
                    <td>
                        '.round($row['sum_bet'], 2).'   
                    </td>
                    <td>
                        '.$date_create.'   
                    </td>
                    <td>
                        '.$action.'  
                    </td>
                </tr>';
        }
    }
}


echo '
                </table>
            </div>
        </div>';
?>