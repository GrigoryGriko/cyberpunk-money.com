<?php
    header("Content-Type: text/html; charset=utf-8");  // стандартная установка кодировки
?>
<?php
top_auth('Добавить сайт в сёрфинг', 'auth/style/addsurfstyle', false, 'addsurf');
?>

<?php
echo '
    <div class="box_strokeafter_2_center_panel">
    		<div class="strokeblock1_cp">
                <div id="container1_sb1cp">
                    <div class="stroke1_c1_sb1cp">
                        Добавить сайт в сёрфинг
                    </div>
                    <div class="strokebox2_c1_sb1cp">
                        <p class="p_strokebox2_c1_sb1cp">
                            Заголовок ссылки
                        </p>
                        <input class="input_field" type="text" id="name_link">    
                        <p class="p_strokebox2_c1_sb1cp">
                            URL сайта (включая http://)
                        </p>
                        <input class="input_field" type="text" id="url_site" value="http://">
                        <p class="p_strokebox2_c1_sb1cp">
                            Время просмотра ссылки
                        </p>
                        <select class="input_field" id="time_watch">
                          <option selected value="20">20 секунд</option>
                          <option value="30">30 секунд (+ 0.01 руб.)</option>
                          <option value="40">40 секунд (+ 0.02 руб.)</option>
                          <option value="50">50 секунд (+ 0.03 руб.)</option>
                          <option value="60">60 секунд (+ 0.04 руб.)</option> 
                        </select>
                        <p class="p_strokebox2_c1_sb1cp">
                            Стоимость одного просмотра
                        </p>

                        <input class="input_field unactive" type="text" id="cost_watch" value="0.03 руб." readonly>
                        <input type="hidden" id="value_cost_watch" value="0.03">

                        <div class="box_switch-and-text b_s-a-t_1">
                            <p class="p_strokebox2_c1_sb1cp">Запустить сразу</p>

                            <label class="switch">
                              <input type="checkbox" id="checkbox_enable" value="1" checked>
                              <span class="slider round"></span>
                            </label>                                
                        </div>

                        <div class="box_switch-and-text b_s-a-t_2">
                            <input class="switch_enable input_field count_views" type="text" id="max_count_views" value="Неограничено просмотров" readonly>

                            <label class="switch">
                              <input type="checkbox" id="checkbox_count_views" value="1" checked>
                              <span class="slider round"></span>
                            </label>                                
                        </div>

                        <div class="container_checkbox">
                            <input type="checkbox" id="checkbox_ad" value="0"><p class="p_checkbox_text">Я согласен с <a href="/rules">правилами размещения рекламы на сайте</a></p>
                        </div>

                        <div class="centerer_button">
                            <button class="button_add_surf" id="button_add_site_surfing" onclick="post_query(\'mine_shop/actions/add_site_surf\', \'add_site_surf\', \'name_link*+*url_site*+*time_watch*+*value_cost_watch*+*checkbox_enable*+*max_count_views*+*checkbox_ad\')">Добавить сайт в сёрфинг</button>
                        </div>
                    </div>
                </div>
                <div class="container2_sb1cp">
                    <div class="stroke1_c2_sb1cp">
                        Мои сайты в сёрфинге
                    </div>

                    <div id="stroke2_c2_sb1cp">';

                        addsurf_ajax_content();

echo '
                    </div>

                </div>
            </div>
            <div class="strokeblock2_cp">

            </div>
        </div>
    </div>';

    bottom_auth();
?>