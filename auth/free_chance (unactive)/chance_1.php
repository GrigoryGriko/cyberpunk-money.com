<?php
    header("Content-Type: text/html; charset=utf-8");  // стандартная установка кодировки
?>
<?php
top_auth('Получить шансы', 'auth/free_chance/style/free_chance_getchancestyle', false, false, true);
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
    $chance_day = strtotime($assoc_params['date_ch1']);

    $interval_days = intval( ($today_day / 3600 / 24) - ($chance_day / 3600 / 24) );

    if ($interval_days >= 1) {
        $db->Query("UPDATE `parametres_free_chance` SET `date_ch1` = NOW() WHERE `uid` = '$_SESSION[id]'");

        $db->Query("UPDATE `users_data` SET `chance` = (`chance` + 2) WHERE `uid` = '$_SESSION[id]'");

        $message = 'Вы получили 2 шанса';
    }
    else {
        $message = 'Шансы здесь уже получены и пока недоступны (время возобновления 24 часа)';     
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
                            Мои шансы:&nbsp;<div id="chance_current"><span>'.$assoc_u_d['chance'].'</span> шт.</div>
                        </div>

                        <a href="free_chance_getchance">Вернуться назад</a>

                        <p class="t3" style="font-size: 35px;">'.$message.'</p>
                    </div>

                        <div class="banner_linkslot">';


echo '
                    <script type="text/javascript">
                        atOptions = {
                            \'key\' : \'5babdb02a0739943f4816c317d9533ff\',
                            \'format\' : \'iframe\',
                            \'height\' : 60,
                            \'width\' : 468,
                            \'params\' : {}
                        };
                        document.write(\'<scr\' + \'ipt type="text/javascript" src="http\' + (location.protocol === \'https:\' ? \'s\' : \'\') + \'://www.hiprofitnetworks.com/5babdb02a0739943f4816c317d9533ff/invoke.js"></scr\' + \'ipt>\');
                    </script>


                    <!-- <div class="container_native_a">
                        <p class="decript">Поставщики шансов</p>
                        <div class="container_datatable_part2">
                            <script async="async" data-cfasync="false" src="//pl15425883.passtechusa.com/931a2d994c6d51c66ed58834f39ce6dc/invoke.js"></script>
                            <div class="native_banner" id="container-931a2d994c6d51c66ed58834f39ce6dc"></div>
                        </div>
                    </div> -->
';

echo '
                        </div>';
echo '
                    </div>
                </div>
            </div>

            <div class="element_1_stroke_4_box_1_center_panel">

            </div>

            <div class="box_stroke_5_box_1_center_panel">';

echo '
                <div style="margin-left: 50px; margin-top: 50px; margin-bottom: 50px;">
                     <script type="text/javascript">
                        atOptions = {
                            \'key\' : \'b4898985c15b77a2ffb9c31c0183b847\',
                            \'format\' : \'iframe\',
                            \'height\' : 300,
                            \'width\' : 160,
                            \'params\' : {}
                        };
                        document.write(\'<scr\' + \'ipt type="text/javascript" src="http\' + (location.protocol === \'https:\' ? \'s\' : \'\') + \'://o626b32etkg6.com/b4898985c15b77a2ffb9c31c0183b847/invoke.js"></scr\' + \'ipt>\');
                    </script>
                </div>';                                     

echo '
                <div style="margin-left: 200px;">
                    <script type="text/javascript">
                        atOptions = {
                            \'key\' : \'32a339985288ba0c552e1a3b816c804b\',
                            \'format\' : \'iframe\',
                            \'height\' : 90,
                            \'width\' : 728,
                            \'params\' : {}
                        };
                        document.write(\'<scr\' + \'ipt type="text/javascript" src="http\' + (location.protocol === \'https:\' ? \'s\' : \'\') + \'://o626b32etkg6.com/32a339985288ba0c552e1a3b816c804b/invoke.js"></scr\' + \'ipt>\');
                    </script>
                </div>';
                echo
                    '
            </div>
        </div>
    </div>
    ';

    bottom_auth('exist_footer', true);

?>