<?php
    header("Content-Type: text/html; charset=utf-8");  // стандартная установка кодировки
?>
<?php
top_auth('Бесплатная лотерея', 'auth/style/free_chancestyle', false, false, true);
?>
<?php
echo '
    <script type="text/javascript">
        function get_cookie ( cookie_name )
        {
          var results = document.cookie.match ( "(^|;) ?" + cookie_name + "=([^;]*)(;|$)" );
         
          if ( results )
            return ( unescape ( results[2] ) );
          else
            return null;
        }


        $(document).on("click", "#random", function() {

            function getRandomInRange(min, max) {   /*случайно число в диапазоне включительно*/
              return Math.floor(Math.random() * (max - min + 1)) + min;
            }

            for (var num = 1; num <= 6; num++) {
                var random_number = getRandomInRange(0, 9);

                $("#number_"+num+"").val(random_number);
            }
            $(".number_lot").css({"background": "#fff"});

        });

        $(document).on("click", "#play", function() {

            $("#total_text").hide();
            $("#total_text").css({"font-size" : 0});

            function show_t() {
                $("#total_text").show();
            }
            setTimeout(show_t, 30);

            document.cookie = "play_f=1; max-age=2";
            for (var num = 1; num <= 6; num++) {
                var number_row = $("#number_"+num+"").val();
                document.cookie = "number_"+num+"="+number_row+"";
            }



            $.get("request/free_chance_request_GET_data", function(data) {    //функция получает данные data с файла по директории
                data = $(data);

                $("#total_text").html( $("#total_text_get", data).html() );
                
                $("#total_text").css({"font-size" : "30px"});

                $("#block_message").html( $("#message_get", data).html() );
                $("#lototron").html( $("#result_numbers", data).html() );
                $("#chance_current").html( $("#chance", data).html() );
            });

            $("#retry").show();

            ajax_index_top_auth();
        });

        $(document).on("click", "#retry", function() {
            
            $("#total_text").hide();
            $("#total_text").css({"font-size" : 0});

            function show_t() {
                $("#total_text").show();
            }
            setTimeout(show_t, 30);

            var number_user = [];
            for (var num = 1; num <= 6; num++) {
                if (get_cookie( "number_"+num+"" ) ) {
                    number_user[num] = get_cookie( "number_"+num+"" );
                }
                else {
                    number_user[num] = 0;
                }

            }
            for (var num = 1; num <= 6; num++) {
                $("#number_"+num+"").val(number_user[num]);
            }

            document.cookie = "play_f=1; max-age=2";
            for (var num = 1; num <= 6; num++) {
                var number_row = $("#number_"+num+"").val();
                document.cookie = "number_"+num+"="+number_row+"";
            }

            $.get("request/free_chance_request_GET_data", function(data) {    //функция получает данные data с файла по директории
                data = $(data);

                $("#total_text").html( $("#total_text_get", data).html() );
                
                $("#total_text").css({"font-size" : "30px"});

                $("#block_message").html( $("#message_get", data).html() );
                $("#lototron").html( $("#result_numbers", data).html() );
                $("#chance_current").html( $("#chance", data).html() );
            });
            ajax_index_top_auth();
        });
    </script>
        ';

$db->Query("SELECT * FROM `parametres_free_chance` WHERE `uid` = '$_SESSION[id]'");
$NumRows = $db->NumRows();
if ( !empty($NumRows) ) {
    $assoc_params = $db->FetchAssoc();

    $today_day = time();
    $chance_day = strtotime($assoc_params['date_day_chance']);

    $interval_days = intval( ($today_day / 3600 / 24) - ($chance_day / 3600 / 24) );

    if ($interval_days > 1 and $assoc_params['date_ch_day'] < 7) {
        $date_ch_day = 7;

        $db->Query("UPDATE `parametres_free_chance` SET `date_ch_day` = '$date_ch_day', `date_day_chance` = NOW() WHERE `uid` = '$_SESSION[id]'");
    }

}

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

if (!$_SESSION['id']) {
    $link_chance = '<a href="/login">';

    $link_auth_1 = '<a href="/login">';
    $link_auth_2 = '</a>';
}
else {
    $link_chance = '<a href="/free_chance_getchance">';

    $link_auth_1 = '';
    $link_auth_2 = '';
}

if ( isset($_COOKIE['number_1']) ) {
    $display = '';
}
else {
    $display = 'style = "display: none;"';
}

echo '
    <div class="box_strokeafter_2_center_panel">
            <div class="centerer_e1s3_center_panel">    
                <div class="element_1_stroke_3_box_1_center_panel">
                    <div class="box_elements_e1s3b1cp">

                    <div class="chance_est">
                        <div class="box">
                            Мои шансы:&nbsp;<b><div id="chance_current"><span>'.($assoc_u_d['chance'] + $assoc_params['date_ch_day']).'</span></div></b>&nbsp;шт.
                        </div>
                        
                        '.$link_chance.'Получить шансы</a>

                        <p class="t3">Шансы начисляются каждый день!</p>
                    </div>

                    <div class="box_text">
                        <div class="description">
                            Бесплатная лотерея, это отличный шанс выигрывать деньги без покупки билета. Средства идут на баланс для покупок. Данная лотерея это как дополнительный ежедневный бонус.<br>
                            Угадайте число от 0 до 999999
                        </div>
                        <div class="description_2">
                            Награда:<br>
                            1 угаданное число - <span>0.01 руб.</span><br>
                            2 угаданных числа - <span>0.1 руб.</span><br>
                            3 угаданных числа - <span>1 руб.</span><br>
                            4 угаданных числа - <span>10 руб.</span><br>
                            5 угаданных числа - <span>100 руб.</span><br>
                            6 угаданных числа - <span>3000 руб.</span>
                        </div>
                    </div>

                        <div id="lototron">
                            <input class="number_lot" type="number" id="number_1" value="0">
                            <input class="number_lot" type="number" id="number_2" value="0">
                            <input class="number_lot" type="number" id="number_3" value="0">
                            <input class="number_lot" type="number" id="number_4" value="0">
                            <input class="number_lot" type="number" id="number_5" value="0">
                            <input class="number_lot" type="number" id="number_6" value="0">
                        </div>
                        <div class="buttons">
                            <div class="fix_block">
                                <div class="fix_block_total_text">
                                    <div id="total_text"></div>
                                </div>
                                <div id="block_message"></div>
                            </div>
                            '.$link_auth_1.'
                                <div class="box">
                                    <button class="button_action random" id="random">Автонабор чисел</button>
                                    <button class="button_action play" id="play">Играть</button>
                                    <button '.$display.' class="button_action retry" id="retry">Повторить</button>
                                </div>
                            '.$link_auth_2.'
                        </div>

                        <div class="banner_linkslot">
                            Спонсоры игр:
                            <div id="linkslot_286956"><script src="https://linkslot.ru/bancode.php?id=286956" async></script></div>

                            <center style="font-size: 14px;"><a href="https://linkslot.ru/link.php?id=286957" target="_blank" rel="noopener">Купить ссылку здесь за <span id="linprice_286957"></span> руб.</a><div id="linkslot_286957" style="margin: 10px 0;"><script src="https://linkslot.ru/lincode.php?id=286957" async></script></div></center>';



echo '
                        </div>';
echo '
                    </div>
        		</div>
            </div>

            <div class="element_1_stroke_4_box_1_center_panel">

            </div>';
                    

                echo
                    '
        </div>
    </div>
    ';

    bottom_auth();

?>