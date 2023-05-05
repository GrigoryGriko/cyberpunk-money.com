<?php
    header("Content-Type: text/html; charset=utf-8");  // стандартная установка кодировки
?>
<?php
top_auth('Таблица внимательности', 'auth/games/style/schulte_tabstyle(start)');
?>
<?php

if ( isset($_SESSION['id']) ) {
    $db->Query("SELECT * FROM `games_schulte_tab` WHERE `user_create_id` = '$_SESSION[id]' AND `user_invite_id` != 0 AND `id_user_win` = 0");
    $NumRows = $db->NumRows();
    if ( !empty($NumRows) ) {
        while ( $assoc = $db->FetchAssoc() ) {
            $assoc['date_user_2_start'] = strtotime($assoc['date_user_2_start']);
            $interval = time() - $assoc['date_user_2_start'];
            if ($interval > 1800) { //более получаса
                $COMISSION = 0.08; //8%
                

                $assoc['sum_bet'] = $assoc['sum_bet'] - ($assoc['sum_bet'] * $COMISSION);

                $user_id_win = $assoc['user_create_id'];

                $QUERY_UPDATE_1 = $db->Query_recordless("UPDATE `games_schulte_tab` SET `id_user_win` = '$user_id_win', `date_user_2_end` = NOW() WHERE `id` = '$assoc[id]'");
                $QUERY_UPDATE_2 = $db->Query_recordless("UPDATE `users_data` SET `balance_buy` = (`balance_buy` + '$assoc[sum_bet]') WHERE `uid` = '$user_id_win'");

                @mysqli_free_result($QUERY_UPDATE_1);
                @mysqli_free_result($QUERY_UPDATE_2);
            }
        }
    }
    $db->Query("SELECT * FROM `games_schulte_tab` WHERE `user_invite_id` = '$_SESSION[id]' AND `id_user_win` = 0");       /*обновление для игры "Таблица внимательности"*/
    $NumRows = $db->NumRows();
    if ( !empty($NumRows) ) {
        while ( $assoc = $db->FetchAssoc() ) {
            $assoc['date_user_2_start'] = strtotime($assoc['date_user_2_start']);
            $interval = time() - $assoc['date_user_2_start'];
            if ($interval > 1800) { //более получаса
                $COMISSION = 0.08; //8%
                

                $assoc['sum_bet'] = $assoc['sum_bet'] - ($assoc['sum_bet'] * $COMISSION);

                $user_id_win = $assoc['user_create_id'];

                $QUERY_UPDATE_1 = $db->Query_recordless("UPDATE `games_schulte_tab` SET `id_user_win` = '$user_id_win', `date_user_2_end` = NOW() WHERE `id` = '$assoc[id]'");
                $QUERY_UPDATE_2 = $db->Query_recordless("UPDATE `users_data` SET `balance_buy` = (`balance_buy` + '$assoc[sum_bet]') WHERE `uid` = '$user_id_win'");

                @mysqli_free_result($QUERY_UPDATE_1);
                @mysqli_free_result($QUERY_UPDATE_2);
            }
        }
    }
}


echo '
        <script type="text/javascript">
            function delete_cookie ( cookie_name )  /*функция удаления куки*/
            {
              var cookie_date = new Date ( );  // Текущая дата и время
              cookie_date.setTime ( cookie_date.getTime() - 1 );
              document.cookie = cookie_name += "=; expires=" + cookie_date.toGMTString();
            }

            $(document).ready(function() {
                delete_cookie("sorting");
            });

            function ajax_get_list() {
                $.get("ajax/schulte_tab(start)_ajax", function(data) {
                    data = $(data);
                    $("#div_table_e1s4b1c").html( $("#div_table_e1s4b1c", data).html() );
                });
            };
            setInterval(ajax_get_list, 1000);


        /*возвращает куки с указанным name,
        или undefined, если ничего не найдено*/
        /*function getCookie(name) {
          let matches = document.cookie.match(new RegExp(
            "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, "\\$1") + "=([^;]*)"
          ));
          return matches ? decodeURIComponent(matches[1]) : undefined;
        }*/

        
        $(document).on("click", "#login_creater", function() {
            /*Передать данные через cookie*/

            var cookies_sorting = $("#cookie_sorting").val();
            if (cookies_sorting != 2 || cookies_sorting == 0) {
                document.cookie = "sorting=login_creater_direct";
                $("#cookie_sorting").val(2);
                $(".img_arrow").css({"display": "none"});
                $("#ar_up_1").css({"display": "block"});
            }
            else {
                document.cookie = "sorting=login_creater_reverse";
                $("#cookie_sorting").val(1);
                $(".img_arrow").css({"display": "none"});
                $("#ar_down_1").css({"display": "block"});
            }

            $.get("ajax/schulte_tab(start)_ajax", function(data) {
                data = $(data);
                $("#div_table_e1s4b1c").html( $("#div_table_e1s4b1c", data).html() );
            });
        });

        $(document).on("click", "#login_invite", function() {
            /*Передать данные через cookie*/

            var cookies_sorting = $("#cookie_sorting").val();
            if (cookies_sorting != 22 || cookies_sorting == 0) {
                document.cookie = "sorting=login_invite_direct";
                $("#cookie_sorting").val(22);
                $(".img_arrow").css({"display": "none"});
                $("#ar_up_2").css({"display": "block"});
            }
            else {
                document.cookie = "sorting=login_invite_reverse";
                $("#cookie_sorting").val(11);
                $(".img_arrow").css({"display": "none"});
                $("#ar_down_2").css({"display": "block"});
            }

            $.get("ajax/schulte_tab(start)_ajax", function(data) {
                data = $(data);
                $("#div_table_e1s4b1c").html( $("#div_table_e1s4b1c", data).html() );
            });
        });

        $(document).on("click", "#sum_bet", function() {
            /*Передать данные через cookie*/

            var cookies_sorting = $("#cookie_sorting").val();
            if (cookies_sorting != 222 || cookies_sorting == 0) {
                document.cookie = "sorting=sum_bet_direct";
                $("#cookie_sorting").val(222);
                $(".img_arrow").css({"display": "none"});
                $("#ar_up_3").css({"display": "block"});
            }
            else {
                document.cookie = "sorting=sum_bet_reverse";
                $("#cookie_sorting").val(111);
                $(".img_arrow").css({"display": "none"});
                $("#ar_down_3").css({"display": "block"});
            }

            $.get("ajax/schulte_tab(start)_ajax", function(data) {
                data = $(data);
                $("#div_table_e1s4b1c").html( $("#div_table_e1s4b1c", data).html() );
            });
        });

        $(document).on("click", "#date_create", function() {
            /*Передать данные через cookie*/

            var cookies_sorting = $("#cookie_sorting").val();
            if (cookies_sorting != 2222 || cookies_sorting == 0) {
                document.cookie = "sorting=date_create_direct";
                $("#cookie_sorting").val(2222);
                $(".img_arrow").css({"display": "none"});
                $("#ar_up_4").css({"display": "block"});
            }
            else {
                document.cookie = "sorting=date_create_reverse";
                $("#cookie_sorting").val(1111);
                $(".img_arrow").css({"display": "none"});
                $("#ar_down_4").css({"display": "block"});
            }

            $.get("ajax/schulte_tab(start)_ajax", function(data) {
                data = $(data);
                $("#div_table_e1s4b1c").html( $("#div_table_e1s4b1c", data).html() );
            });
        });                   
    </script>';
echo '
    <div class="box_strokeafter_2_center_panel">';

MessageShow();

echo '
        	<div class="centerer_e1s3_center_panel">
                <div class="element_1_stroke_3_box_1_center_panel">
                    <div class="box_elements_e1s3b1cp">
                        <div class="stroka_1"> 
                            <a href="/payin_gamemoney" class="l_1">Пополнить игровой баланс</a>
                            <a href="/payout_gamemoney" class="l_2">Вывести игровой баланс</a>
                            <a href="/schulte_tab_list_complete" class="l_3">Мои завершенные игры</a>
                        </div>
                        <p class="stroka_2"> 
                            Таблица внимательности - это игра на деньги с реальными противниками. В данной игре Вы должны найти числа по порядку от 1 до 25 как можно быстрее, кто справился с этим быстрее, тот и победил. Числа каждый раз при создании игры числа располагаются в таблице случайным образом. Вы можете как создать игру, сыграть в нее, и ожидать завершения действий соперника, так и присоединится к уже созданной. Для Вас и вашего соперника числа расположены в одном и том же порядке. Если соперник не завершает игру в течение суток, то она автоматически завершается и Вам присуждается победа. За ошибочное нажатие на число добавляется 30 штрафных секунд. Если второй игрок бездействует 30 минут, то игра завершается. Коэффициент на выигрыш - 1,92.
                        </p>
                        <!-- <div class="banner_linkslot">
                            Спонсоры игры:
                            <div id="linkslot_285612"><script src="https://linkslot.ru/bancode.php?id=285612" async></script></div> -->
                        ';
';

echo '
                    </div>
        				<div class="text_e1s3b1cp">';
if ( isset($_SESSION['id']) ) {
    echo '
                            <form method="post" action="request/schulte_tabrequest(start)">
                                Ваша ставка (руб.):
                                <input class="input_sum_bet" type="number" step="0.01" name="sum_bet" value="0">
                                <input type="hidden" name="user_id" value="'.$_SESSION['id'].'">
                                <button type="submit">Создать игру</button>
                            </form>';
}
else {
    echo '
                            <a href="/register">
                                Ваша ставка (руб.):
                                <input class="input_sum_bet" type="number" step="0.01" name="sum_bet" value="0">
                                <input type="hidden" name="user_id" value="'.$_SESSION['id'].'">
                                <button type="submit">Создать игру</button>
                            </a>';
}
echo '
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
                        <th id="login_creater"><div class="cell">Пользователь <img id="ar_up_1" class="img_arrow" style="display: none;" src="auth/games/img/ar_up.png"> <img id="ar_down_1" class="img_arrow" style="display: none;" src="auth/games/img/ar_down.png"></img></div></th>
                        <th id="login_invite"><div class="cell">Соперник <img id="ar_up_2" class="img_arrow" style="display: none;" src="auth/games/img/ar_up.png"> <img id="ar_down_2" class="img_arrow" style="display: none;" src="auth/games/img/ar_down.png"></img></div></th>
                        <th id="sum_bet"><div class="cell">Ставка (руб.) <img id="ar_up_3" class="img_arrow" style="display: none;" src="auth/games/img/ar_up.png"> <img id="ar_down_3" class="img_arrow" style="display: none;" src="auth/games/img/ar_down.png"></img></div></th>
                        <th id="date_create"><div class="cell">Время создания <img id="ar_up_4" class="img_arrow" style="display: none;" src="auth/games/img/ar_up.png"> <img id="ar_down_4" class="img_arrow" style="display: none;" src="auth/games/img/ar_down.png"></img></div></th>
                        <th></th>
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
$db->Query("SELECT * FROM `games_schulte_tab` WHERE `id_user_win` = 0 ORDER BY `date_create` DESC LIMIT 150");    //готовая для второго игрока, не законченная       
$NumRows = $db->NumRows();

if ( !empty($NumRows) ) {
    $a = 0;     //переменная чередует цвета строк
    while ( $row = $db->FetchAssoc() ) {
        
        $show_game = true;

        if ($row['user_invite_id'] == 0 and $row['user_create_id'] == $_SESSION['id']) {
            if ($row['ready_play_user_2'] == 0) {
                $action = '<a href="/schulte_tab_event?i='.$row['hash_link_game'].'">Продолжить игру</a> <a href="/schulte_tab_event?i='.$row['hash_link_game'].'&action=delete">Удалить</a>';
            }
            else {
                $action = 'В ожидании соперника';
            }
        }
        else if ($row['user_invite_id'] == 0 and $row['user_create_id'] != $_SESSION['id']) {
            if ($row['ready_play_user_2'] == 1) {
                $action = '<a href="/schulte_tab_event?i='.$row['hash_link_game'].'">Играть</a>';
            }
            else {
                $show_game = false;
            }
        }
        else if ($row['user_invite_id'] == 0 and $row['user_create_id'] != $_SESSION['id']) {
            if ($row['ready_play_user_2'] == 0) {
                $action = '<a href="/schulte_tab_event?i='.$row['hash_link_game'].'">Продолжить игру</a>';
            }
            else {
                $show_game = false;
            }
        }
        else if ($row['user_invite_id'] == $_SESSION['id']) {
            $action = '<a href="/schulte_tab_event?i='.$row['hash_link_game'].'">Продолжить игру</a>';
        }
        else {
            $action = 'Игра идет';
        }

        if ( !isset($_SESSION['id']) ) {
            $action = '<a href="/register">Играть</a>';
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
                        '.round($row['sum_bet'], 5).'   
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

echo
                    '</table>
                </div>

            </div>
        </div>
    </div>
    ';

bottom_auth();

?>