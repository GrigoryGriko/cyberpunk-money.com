<?php
    header("Content-Type: text/html; charset=utf-8");  // стандартная установка кодировки
?>
<?php
if ( !isset($_SESSION['id']) ) {

exit('/register');
}

$db->Query("SELECT * FROM `games_schulte_tab` WHERE `hash_link_game` = '$_GET[i]'");
$NumRows = $db->NumRows();
if ( !empty($NumRows) ) {
    $as_games_data = $db->FetchAssoc();
    if ($as_games_data['user_create_id'] == $_SESSION['id'] and $as_games_data['ready_play_user_2'] == 0) {
        
        if ($_GET['action'] == 'delete') {     //если есть запрос на удаление игры
            $db->Query("DELETE FROM `games_schulte_tab` WHERE `hash_link_game` = '$_GET[i]'");  //удалять и возвращать деньги на баланс
            $db->Query("UPDATE `users_data` SET `balance_game` = (`balance_game` + '$as_games_data[sum_bet]') WHERE `uid` = '$as_games_data[user_create_id]'");
            MessageSend('Игра удалена', '/schulte_tab_start');
        }

        
        $next_number = ($as_games_data['current_number_user_1'] + 1);
        $number = unserialize( base64_decode( $as_games_data['array_position_number']) );        //декодирование строки в массив

        if ($as_games_data['date_user_1_start'] == "0000-00-00 00:00:00") {
            $db->Query("UPDATE `games_schulte_tab` SET `date_user_1_start`= NOW() WHERE `id` = '$as_games_data[id]'");
            $db->Query("SELECT * FROM `games_schulte_tab` WHERE `hash_link_game` = '$_GET[i]'");    //извлечение, для того, чтобы получить дату старта игрока
            $NumRows = $db->NumRows();
            if ( !empty($NumRows) ) {
                $as_games_data = $db->FetchAssoc();
            }
        }

        $time_start = $as_games_data['date_user_1_start'];
    }
    else if ($as_games_data['user_create_id'] != $_SESSION['id'] and $as_games_data['user_invite_id'] == 0 and $as_games_data['ready_play_user_2'] == 1) {      //вступление второго игрока в игру
        
        $db->Query("SELECT * FROM `users_data` WHERE `uid` = '$_SESSION[id]'");
        $NumRows = $db->NumRows();
        if ( !empty($NumRows) ) {
            $assoc_u_d = $db->FetchAssoc();
            if ($assoc_u_d['balance_game'] >= $as_games_data['sum_bet']) {
                $db->Query("UPDATE `users_data` SET `balance_game` = (`balance_game` - '$as_games_data[sum_bet]') WHERE `uid` = '$_SESSION[id]'");
                /*$db->Query('INSERT INTO `games_schulte_tab` VALUES(NULL, "'.$_SESSION['id'].'", 0, "0000-00-00 00:00:00", "0000-00-00 00:00:00", "0000-00-00 00:00:00", "0000-00-00 00:00:00", NOW(), 0, 0, "'.$_POST['sum_bet'].'", "'.$array_number.'", 0, 0, 0, 0, "'.$hash_link.'")');*/

                header('location: '.$secret_link.'');
            }
            else {
                MessageSend('Недостаточно средств', '/schulte_tab_start');    
            }
        }
        else {
            MessageSend('Вас не существует', '/schulte_tab_start');
        }

        $db->Query("UPDATE `games_schulte_tab` SET `user_invite_id`= '$_SESSION[id]' WHERE `id` = '$as_games_data[id]'");

        $next_number = ($as_games_data['current_number_user_2'] + 1);
        $number = unserialize( base64_decode( $as_games_data['array_position_number']) );        //декодирование строки в массив

        if ($as_games_data['date_user_2_start'] == "0000-00-00 00:00:00") {
            $db->Query("UPDATE `games_schulte_tab` SET `date_user_2_start`= NOW() WHERE `id` = '$as_games_data[id]'");
            $db->Query("SELECT * FROM `games_schulte_tab` WHERE `hash_link_game` = '$_GET[i]'");    //извлечение, для того, чтобы получить дату старта игрока
            $NumRows = $db->NumRows();
            if ( !empty($NumRows) ) {
                $as_games_data = $db->FetchAssoc();
            }
        }

        $time_start = $as_games_data['date_user_2_start'];
    }
    else if ($as_games_data['user_invite_id'] == $_SESSION['id'] and $as_games_data['ready_play_user_2'] == 1) {    //условие для вступившего игрока
        $next_number = ($as_games_data['current_number_user_2'] + 1);
        $number = unserialize( base64_decode( $as_games_data['array_position_number']) );        //декодирование строки в массив

        $time_start = $as_games_data['date_user_2_start'];
    }
    else {
        MessageSend('Игра завершена', '/schulte_tab_start');
    }
}
else {
    MessageSend('Доступ запрещен', '/schulte_tab_start');
}
?>
<?php
top_auth('Таблица внимательности', 'auth/games/events/style/schulte_tab_(event)style');
?>
<?php
$time_passed = time() - strtotime($time_start);

echo '
    <script type="text/javascript">
        $(document).ready(function() {

            /*var time_passed = '.$time_passed.';
            var H = (time_passed / 3600) - 1;
            var m = (H - Math.floor(H) ) * 60;
            var s = (m - Math.floor(m) ) * 60;

            var H = Math.floor(H);
            var m = Math.floor(m);
            var s = Math.floor(s);*/

            var time_passed_format = "Время: "+time_passed+" сек";
            $("#block_timer").html(time_passed_format);

        });

        var time_passed = '.$time_passed.';
        function time_tik() {
            
            time_passed++;
            /*var H = (time_passed / 3600) - 1;
            var m = (H - Math.floor(H) ) * 60;
            var s = (m - Math.floor(m) ) * 60;

            var H = Math.floor(H);
            var m = Math.floor(m);
            var s = Math.floor(s);*/

            var time_passed_format = "Время: "+time_passed+" сек";
            $("#block_timer").html(time_passed_format);
        }
        setInterval(time_tik, 1000);


        $(document).on("click", ".param_blocks", function() { 
            var id_block_number = $(this).attr("id");       /*получение id блока с числом*/
            var id_input_block_number = "input_"+id_block_number;        /*получения id инпута с числовым значением для блока*/

            $("#number_choose").val( $("#"+id_input_block_number+"").val() );
          
            var choose_number_f = "choose_number";
            var value_id_game = $("#id_game").val();
            var value_number_choose = $("#number_choose").val();

            /*Передать данные через cookie*/
            
            document.cookie = "choose_number_f="+choose_number_f;
            document.cookie = "id_game="+value_id_game;
            document.cookie = "number_choose="+value_number_choose;

            

            $.get("request/schulte_tab_request/schulte_tab_request_GET_data", function(data) {    //функция получает данные data с файла по директории
                data = $(data);
                $("#block_message").html( $("#message_get", data).html() );
                $("#block_next_number").html( $("#next_number_get", data).html() );
            });

            document.cookie = "choose_number_f="+choose_number_f+"; max-age=2";
            document.cookie = "id_game="+value_id_game+"; max-age=2";
            document.cookie = "number_choose="+value_number_choose+"; max-age=2";
        });                   
    </script>';
?>
<?php
echo '
    <div class="box_strokeafter_2_center_panel">
            <div class="centerer_e1s3_center_panel">    
                <div class="element_1_stroke_3_box_1_center_panel">
                    
                    <div class="box_elements_e1s3b1cp">
                        <a href="/schulte_tab_start"><div class="back"><img src="auth/games/events/img/schulte_tab/back.png"></div></a>

                        <div class="container_block_message"></div_table_e1s4b1c>
                            <div id="block_description">Задача: найти по порядку числа от 1 до 25 как можно быстрее</div>
                            <div id="block_timer"></div>
                            <div id="block_next_number">Найдите '.$next_number.'</div>
                            <div id="block_message"></div>
                        </div>
                    ';


echo '  
        				<div class="canvas">';


for ($a = 1; $a <= 90; $a++) {
    $key = $a - 1;
    echo '  
                            <input type="hidden" id="input_b'.$a.'" value="'.$number[$key].'">';
}
echo '  
                            <input type="hidden" id="id_game" value="'.$as_games_data['id'].'">
                            <input type="hidden" id="number_choose" value="0">

                            <div id="b1" class="block_1 param_blocks"><img class="param_img_1" src="auth/games/events/img/schulte_tab/'.$number[0].'.png"></div>
                            <div id="b2" class="block_2 param_blocks"><img class="param_img_1" src="auth/games/events/img/schulte_tab/'.$number[1].'.png"></div>
                            <div id="b3" class="block_3 param_blocks"><img class="param_img_1" src="auth/games/events/img/schulte_tab/'.$number[2].'.png"></div>
                            <div id="b4" class="block_4 param_blocks"><img class="param_img_1" src="auth/games/events/img/schulte_tab/'.$number[3].'.png"></div>
                            <div id="b5" class="block_5 param_blocks"><img class="param_img_1" src="auth/games/events/img/schulte_tab/'.$number[4].'.png"></div>
                            <div id="b6" class="block_6 param_blocks"><img class="param_img_1" src="auth/games/events/img/schulte_tab/'.$number[5].'.png"></div> 

                            <div id="b7" class="block_7 param_blocks"><img class="param_img_2" src="auth/games/events/img/schulte_tab/'.$number[6].'.png"></div> 
                            <div id="b8" class="block_8 param_blocks"><img class="param_img_2" src="auth/games/events/img/schulte_tab/'.$number[7].'.png"></div>

                            <div id="b9" class="block_9 param_blocks"><img class="param_img_3" src="auth/games/events/img/schulte_tab/'.$number[8].'.png"></div>
                            <div id="b10" class="block_10 param_blocks"><img class="param_img_3" src="auth/games/events/img/schulte_tab/'.$number[9].'.png"></div>
                            <div id="b11" class="block_11 param_blocks"><img class="param_img_3" src="auth/games/events/img/schulte_tab/'.$number[10].'.png"></div>
                            <div id="b12" class="block_12 param_blocks"><img class="param_img_3" src="auth/games/events/img/schulte_tab/'.$number[11].'.png"></div>
                            <div id="b13" class="block_13 param_blocks"><img class="param_img_3" src="auth/games/events/img/schulte_tab/'.$number[12].'.png"></div>
                            <div id="b14" class="block_14 param_blocks"><img class="param_img_3" src="auth/games/events/img/schulte_tab/'.$number[13].'.png"></div>


                            <div id="b15" class="block_15 param_blocks"><img class="param_img_4" src="auth/games/events/img/schulte_tab/'.$number[14].'.png"></div>
                            <div id="b16" class="block_16 param_blocks"><img class="param_img_4" src="auth/games/events/img/schulte_tab/'.$number[15].'.png"></div>
                            <div id="b17" class="block_17 param_blocks"><img class="param_img_4" src="auth/games/events/img/schulte_tab/'.$number[16].'.png"></div>
                            <div id="b18" class="block_18 param_blocks"><img class="param_img_4" src="auth/games/events/img/schulte_tab/'.$number[17].'.png"></div>
                            <div id="b19" class="block_19 param_blocks"><img class="param_img_4" src="auth/games/events/img/schulte_tab/'.$number[18].'.png"></div>
                            <div id="b20" class="block_20 param_blocks"><img class="param_img_4" src="auth/games/events/img/schulte_tab/'.$number[19].'.png"></div> 

                            <div id="b21" class="block_21 param_blocks"><img class="param_img_2_1" src="auth/games/events/img/schulte_tab/'.$number[20].'.png"></div> 
                            <div id="b22" class="block_22 param_blocks"><img class="param_img_2_1" src="auth/games/events/img/schulte_tab/'.$number[21].'.png"></div>

                            <div id="b23" class="block_23 param_blocks"><img class="param_img_3" src="auth/games/events/img/schulte_tab/'.$number[22].'.png"></div>
                            <div id="b24" class="block_24 param_blocks"><img class="param_img_3" src="auth/games/events/img/schulte_tab/'.$number[23].'.png"></div>
                            <div id="b25" class="block_25 param_blocks"><img class="param_img_3" src="auth/games/events/img/schulte_tab/'.$number[24].'.png"></div>
                            <div id="b26" class="block_26 param_blocks"><img class="param_img_11" src="auth/games/events/img/schulte_tab/'.$number[25].'.png"></div>
                            <div id="b27" class="block_27 param_blocks"><img class="param_img_3" src="auth/games/events/img/schulte_tab/'.$number[26].'.png"></div>
                            <div id="b28" class="block_28 param_blocks"><img class="param_img_3" src="auth/games/events/img/schulte_tab/'.$number[27].'.png"></div>

                            <div id="b29" class="block_29 param_blocks"><img class="param_img_5" src="auth/games/events/img/schulte_tab/'.$number[28].'.png"></div>
                            <div id="b30" class="block_30 param_blocks"><img class="param_img_5" src="auth/games/events/img/schulte_tab/'.$number[29].'.png"></div>

                            <div id="b31" class="block_31 param_blocks"><img class="param_img_7" src="auth/games/events/img/schulte_tab/'.$number[30].'.png"></div>
                            <div id="b32" class="block_32 param_blocks"><img class="param_img_6" src="auth/games/events/img/schulte_tab/'.$number[31].'.png"></div>
                            <div id="b33" class="block_33 param_blocks"><img class="param_img_6" src="auth/games/events/img/schulte_tab/'.$number[32].'.png"></div>
                            <div id="b34" class="block_34 param_blocks"><img class="param_img_6" src="auth/games/events/img/schulte_tab/'.$number[33].'.png"></div>

                            <div id="b35" class="block_35 param_blocks"><img class="param_img_6" src="auth/games/events/img/schulte_tab/'.$number[34].'.png"></div>
                            <div id="b36" class="block_36 param_blocks"><img class="param_img_7" src="auth/games/events/img/schulte_tab/'.$number[35].'.png"></div>
                            <div id="b37" class="block_37 param_blocks"><img class="param_img_6" src="auth/games/events/img/schulte_tab/'.$number[36].'.png"></div>
                            <div id="b38" class="block_38 param_blocks"><img class="param_img_7" src="auth/games/events/img/schulte_tab/'.$number[37].'.png"></div> 
                            <div id="b39" class="block_39 param_blocks"><img class="param_img_6" src="auth/games/events/img/schulte_tab/'.$number[38].'.png"></div>

                            <div id="b40" class="block_40 param_blocks"><img class="param_img_8" src="auth/games/events/img/schulte_tab/'.$number[39].'.png"></div>
                            <div id="b41" class="block_41 param_blocks"><img class="param_img_8" src="auth/games/events/img/schulte_tab/'.$number[40].'.png"></div>
                            <div id="b42" class="block_42 param_blocks"><img class="param_img_9" src="auth/games/events/img/schulte_tab/'.$number[41].'.png"></div>
                            <div id="b43" class="block_43 param_blocks"><img class="param_img_9" src="auth/games/events/img/schulte_tab/'.$number[42].'.png"></div>

                            <div id="b44" class="block_44 param_blocks"><img class="param_img_8" src="auth/games/events/img/schulte_tab/'.$number[43].'.png"></div>
                            <div id="b45" class="block_45 param_blocks"><img class="param_img_8" src="auth/games/events/img/schulte_tab/'.$number[44].'.png"></div>

                            <div id="b46" class="block_46 param_blocks"><img class="param_img_10" src="auth/games/events/img/schulte_tab/'.$number[45].'.png"></div>
                            <div id="b47" class="block_47 param_blocks"><img class="param_img_10" src="auth/games/events/img/schulte_tab/'.$number[46].'.png"></div>

                            <div id="b48" class="block_48 param_blocks"><img class="param_img_11" src="auth/games/events/img/schulte_tab/'.$number[47].'.png"></div>
                            <div id="b49" class="block_49 param_blocks"><img class="param_img_11" src="auth/games/events/img/schulte_tab/'.$number[48].'.png"></div>  
                            <div id="b50" class="block_50 param_blocks"><img class="param_img_11" src="auth/games/events/img/schulte_tab/'.$number[49].'.png"></div>

                            <div id="b51" class="block_51 param_blocks"><img class="param_img_12" src="auth/games/events/img/schulte_tab/'.$number[50].'.png"></div>
                            <div id="b52" class="block_52 param_blocks"><img class="param_img_13" src="auth/games/events/img/schulte_tab/'.$number[51].'.png"></div>
                            <div id="b53" class="block_53 param_blocks"><img class="param_img_12" src="auth/games/events/img/schulte_tab/'.$number[52].'.png"></div>
                            <div id="b54" class="block_54 param_blocks"><img class="param_img_12" src="auth/games/events/img/schulte_tab/'.$number[53].'.png"></div>
                            <div id="b55" class="block_55 param_blocks"><img class="param_img_155" src="auth/games/events/img/schulte_tab/'.$number[54].'.png"></div>
                            <div id="b56" class="block_56 param_blocks"><img class="param_img_13" src="auth/games/events/img/schulte_tab/'.$number[55].'.png"></div>
                            <div id="b57" class="block_57 param_blocks"><img class="param_img_12" src="auth/games/events/img/schulte_tab/'.$number[56].'.png"></div>
                            <div id="b58" class="block_58 param_blocks"><img class="param_img_12" src="auth/games/events/img/schulte_tab/'.$number[57].'.png"></div>

                            <div id="b59" class="block_59 param_blocks"><img class="param_img_12" src="auth/games/events/img/schulte_tab/'.$number[58].'.png"></div>
                            <div id="b60" class="block_60 param_blocks"><img class="param_img_12" src="auth/games/events/img/schulte_tab/'.$number[59].'.png"></div>
                            <div id="b61" class="block_61 param_blocks"><img class="param_img_12" src="auth/games/events/img/schulte_tab/'.$number[60].'.png"></div>
                            <div id="b62" class="block_62 param_blocks"><img class="param_img_12" src="auth/games/events/img/schulte_tab/'.$number[61].'.png"></div>
                            <div id="b63" class="block_63 param_blocks"><img class="param_img_14" src="auth/games/events/img/schulte_tab/'.$number[62].'.png"></div>
                            <div id="b64" class="block_64 param_blocks"><img class="param_img_14" src="auth/games/events/img/schulte_tab/'.$number[63].'.png"></div>

                            <div id="b65" class="block_65 param_blocks"><img class="param_img_13" src="auth/games/events/img/schulte_tab/'.$number[64].'.png"></div>
                            <div id="b66" class="block_66 param_blocks"><img class="param_img_15" src="auth/games/events/img/schulte_tab/'.$number[65].'.png"></div>
                            <div id="b67" class="block_67 param_blocks"><img class="param_img_16" src="auth/games/events/img/schulte_tab/'.$number[66].'.png"></div>
                            <div id="b68" class="block_68 param_blocks"><img class="param_img_16" src="auth/games/events/img/schulte_tab/'.$number[67].'.png"></div>
                            <div id="b69" class="block_69 param_blocks"><img class="param_img_16" src="auth/games/events/img/schulte_tab/'.$number[68].'.png"></div>
                            <div id="b70" class="block_70 param_blocks"><img class="param_img_16" src="auth/games/events/img/schulte_tab/'.$number[69].'.png"></div>
                            <div id="b71" class="block_71 param_blocks"><img class="param_img_16" src="auth/games/events/img/schulte_tab/'.$number[70].'.png"></div>
                            <div id="b72" class="block_72 param_blocks"><img class="param_img_16" src="auth/games/events/img/schulte_tab/'.$number[71].'.png"></div>
                            <div id="b73" class="block_73 param_blocks"><img class="param_img_17" src="auth/games/events/img/schulte_tab/'.$number[72].'.png"></div>
                            <div id="b74" class="block_74 param_blocks"><img class="param_img_17" src="auth/games/events/img/schulte_tab/'.$number[73].'.png"></div>

                            <div id="b75" class="block_75 param_blocks"><img class="param_img_18" src="auth/games/events/img/schulte_tab/'.$number[74].'.png"></div>
                            <div id="b76" class="block_76 param_blocks"><img class="param_img_18" src="auth/games/events/img/schulte_tab/'.$number[75].'.png"></div>
                            <div id="b77" class="block_77 param_blocks"><img class="param_img_18" src="auth/games/events/img/schulte_tab/'.$number[76].'.png"></div>
                            <div id="b78" class="block_78 param_blocks"><img class="param_img_19" src="auth/games/events/img/schulte_tab/'.$number[77].'.png"></div>
                            <div id="b79" class="block_79 param_blocks"><img class="param_img_19" src="auth/games/events/img/schulte_tab/'.$number[78].'.png"></div>
                            <div id="b80" class="block_80 param_blocks"><img class="param_img_20" src="auth/games/events/img/schulte_tab/'.$number[79].'.png"></div>
                            <div id="b81" class="block_81 param_blocks"><img class="param_img_18" src="auth/games/events/img/schulte_tab/'.$number[80].'.png"></div>
                            <div id="b82" class="block_82 param_blocks"><img class="param_img_18" src="auth/games/events/img/schulte_tab/'.$number[81].'.png"></div>
                            <div id="b83" class="block_83 param_blocks"><img class="param_img_21" src="auth/games/events/img/schulte_tab/'.$number[82].'.png"></div>
                            <div id="b84" class="block_84 param_blocks"><img class="param_img_19" src="auth/games/events/img/schulte_tab/'.$number[83].'.png"></div> 

                            <div id="b85" class="block_85 param_blocks"><img class="param_img_22" src="auth/games/events/img/schulte_tab/'.$number[84].'.png"></div> 
                            <div id="b86" class="block_86 param_blocks"><img class="param_img_21" src="auth/games/events/img/schulte_tab/'.$number[85].'.png"></div> 
                            <div id="b87" class="block_87 param_blocks"><img class="param_img_23" src="auth/games/events/img/schulte_tab/'.$number[86].'.png"></div> 
                            <div id="b88" class="block_88 param_blocks"><img class="param_img_23" src="auth/games/events/img/schulte_tab/'.$number[87].'.png"></div> 
                            <div id="b89" class="block_89 param_blocks"><img class="param_img_23" src="auth/games/events/img/schulte_tab/'.$number[88].'.png"></div> 
                            <div id="b90" class="block_90 param_blocks"><img class="param_img_23" src="auth/games/events/img/schulte_tab/'.$number[89].'.png"></div>   
                            ';


echo '
        				</div>
                    </div>
        		</div>
            </div>

            <div class="element_1_stroke_4_box_1_center_panel">
                
            </div>

        </div>
    </div>
    ';

    bottom_auth();

?>