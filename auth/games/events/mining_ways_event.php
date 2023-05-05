<?php
    header("Content-Type: text/html; charset=utf-8");  // стандартная установка кодировки
?>
<?php
$db->Query("SELECT * FROM `games_mining_creator` WHERE `hash_link_game` = '$_GET[i]'");
$NumRows = $db->NumRows();
if ( !empty($NumRows) ) {
    $as_games_data = $db->FetchAssoc();

    $field = unserialize( base64_decode( $as_games_data['array_tiles_field']) );        //декодирование строки в массив
}
else {
    MessageSend('Доступ запрещен', '/mining_ways(start)');
}
?>
<?php
top_auth('Пути добычи', 'auth/games/events/style/mining_ways_(event)style');
?>
<?php
echo '
    <script type="text/javascript">
        $(document).on("click", ".tile_choose_to_set", function() {

            $(".tile_choose_to_set").css({"box-shadow": "none"});
            $(this).css({"box-shadow": "0 0 0 6px rgba(111, 207, 255, 0.52)"});     /*установка обводки на выбранный тайл для размещения*/

            var current_way_id = $(this).attr("id");
            $("#tile_choose_to_set").val(current_way_id);        /*установка призанка что тайл выбран для размещения*/  
        
        });

        $(document).on("click", ".tile", function() { 
            var id_tile = $(this).attr("id");       /*получение id блока тайла на карте*/
            var id_Input_tile = "Input"+id_tile;        /*получения id инпута со значением координат тайла на карте*/
            var arr_x_y = $("#"+id_Input_tile+"").val().split("*+*");   /*разделение строки на массив со значениями координат тайла на карте*/

            $("#X").val(arr_x_y[0]);
            $("#Y").val(arr_x_y[1]);

            post_query(\'mining_ways_request\', \'set_tile\', \'tile_choose_to_set*+*X*+*Y\');

            
        });                   
    </script>';
?>
<?php
echo '
    <div class="box_strokeafter_2_center_panel">
            <div class="centerer_e1s3_center_panel">    
                <div class="element_1_stroke_3_box_1_center_panel">
                    <div class="box_elements_e1s3b1cp">';

if ($as_games_data['current_step'] == 0) {
    $time_step = 5; //время на ход в мин
    $seconds_start = time(); //секунды начала
    $date_start = date("Y-m-d H:i:s", $seconds_start ); //дата начала хода
    $date_end = date("Y-m-d H:i:s", $seconds_start + ($time_step * 60) ); //дата окончания хода



    $db->Query("SELECT * FROM `games_mining_creator_data_files` WHERE `name_array` = 'all_tiles_to_set'");
    $NumRows = $db->NumRows();
    if ( !empty($NumRows) ) {
        $assoc_game_data = $db->FetchAssoc();
    }
    $all_tiles = unserialize( base64_decode($assoc_game_data['array_data']) );

    $rand_tile_to_set_1 = array(1, 2, 3, 4, 6, 7);      //определение 4- тайлов в первом ходу (бросание 4 кубиков)
    $rand_keys1 = array_rand($rand_tile_to_set_1, 1);

    $rand_tile_to_set_2 = array(5, 5, 6, 6, 9, 9);
    $rand_keys2 = array_rand($rand_tile_to_set_2, 1);

    $rand_tile_to_set_3 = array(1, 2, 3, 4, 7, 8);
    $rand_keys3 = array_rand($rand_tile_to_set_3, 1);

    $rand_tile_to_set_4 = array(1, 2, 3, 4, 7, 8);
    $rand_keys4 = array_rand($rand_tile_to_set_4, 1);


   /* $key_tile_1 = $rand_tile_to_set_1[$rand_keys1];
    $key_tile_2 = $rand_tile_to_set_2[$rand_keys2];
    $key_tile_3 = $rand_tile_to_set_3[$rand_keys3];
    $key_tile_4 = $rand_tile_to_set_4[$rand_keys4];*/


    $array_tiles_to_set = array(        //создаем массив из 4-х первых тайлов
        $rand_tile_to_set_1[$rand_keys1],      
        $rand_tile_to_set_2[$rand_keys2],
        $rand_tile_to_set_3[$rand_keys3],
        $rand_tile_to_set_4[$rand_keys4],
        'data_time' => $all_tiles['data_time']
    );

    $encode_array_tiles_to_set = base64_encode( serialize($array_tiles_to_set) );
    $db->Query("UPDATE `games_mining_creator` SET `array_tile_set_step1` = '$encode_array_tiles_to_set', `current_step` = 1 WHERE `id` = '$as_games_data[id]'");  //занесение массива тайлов для первого хода, занесение признака первого хода
}
else if ($as_games_data['current_step'] == 1) {
    //извлекать тайлы из базы
    $array_tiles_to_set = unserialize( base64_decode($as_games_data['array_tile_set_step1']) );
}

echo '  
        				<div class="canvas">';
                            var_dump($array_tiles_to_set);
                        echo '
                            <div class="box_choose">
                                <div id="'.$array_tiles_to_set[0].'" class="tile_choose_to_set"><img src="auth/games/events/img/tile_'.$array_tiles_to_set[0].'.png" width="60px" height="60px"></div>
                                <div id="'.$array_tiles_to_set[1].'" class="tile_choose_to_set"><img src="auth/games/events/img/tile_'.$array_tiles_to_set[1].'.png" width="60px" height="60px"></div>
                                <div id="'.$array_tiles_to_set[2].'" class="tile_choose_to_set"><img src="auth/games/events/img/tile_'.$array_tiles_to_set[2].'.png" width="60px" height="60px"></div>
                                <div id="'.$array_tiles_to_set[3].'" class="tile_choose_to_set"><img src="auth/games/events/img/tile_'.$array_tiles_to_set[3].'.png" width="60px" height="60px"></div>
                            </div>';
$set_step = 0;
echo '
                            <div class="box_tiles">

                                <input type="hidden" id="tile_choose_to_set" value="0">';   /*какой тайл выбран для размещения*/
echo '                                
                                <input type="hidden" id="X" value="0">  
                                <input type="hidden" id="Y" value="0">';


for ($num_tile_x = 0; $num_tile_x <= 8; $num_tile_x ++) {
    echo '
                                <div class="row">';
    for ($num_tile_y = 0; $num_tile_y <= 8; $num_tile_y ++) {
        if ($num_tile_y == 0 or $num_tile_y == 8 or $num_tile_x == 0 or $num_tile_x == 8) {
            $type_way = -1;
            $color_style='background: #ffe1cc;';
        }
        else {
            $type_way = 0;
            $color_style = '';
        }
        echo '
                                   
                                    <input type="hidden" id="Input'.$num_tile_x.'-'.$num_tile_y.'" value="'.$num_tile_x.'*+*'.$num_tile_y.'">';   /*инпут со значение координат тайла*/
        echo '                            
                                    <div id="'.$num_tile_x.'-'.$num_tile_y.'" class="tile" style="'.$color_style.'">X: '.$num_tile_x.'<br> Y: '.$num_tile_y.'<br> Way: '.$type_way.'<br> Step: '.$set_step.'</div>';    /*тайл на карте*/
    }
    echo '    
                                </div>';
}
echo '
                            </div>';
echo '
        				</div>
                    </div>
        		</div>
            </div>

            <div class="element_1_stroke_4_box_1_center_panel">
                
            </div>

            <div class="box_stroke_5_box_1_center_panel">

                <div class="div_table_e1s4b1c">';
                    

                echo
                    '
                </div>

            </div>
        </div>
    </div>
    ';

    bottom_auth();

?>