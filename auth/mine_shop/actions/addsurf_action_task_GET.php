<?php
usleep(50000);
if ($_COOKIE['action_f'] == 'edit_task') {
    if (!$_COOKIE['edit_task_id']) {
        message('Критическая ошибка cook#33', false, false, 'error');
    }
    else {
        $db->Query("SELECT * FROM `user_addsurf_sites` WHERE `id` = '$_COOKIE[edit_task_id]' AND `uid` = '$_SESSION[id]'");
        $NumRows_u_a_s = $db->NumRows();
        if ( !empty($NumRows_u_a_s) ) {
            $assoc_u_a_s = $db->FetchAssoc();
            if ($assoc_u_a_s['banned'] == 0) {
                if ($assoc_u_a_s['max_count_views'] >= 9999999999999) {
                    $assoc_u_a_s['max_count_views'] = 'Неограничено просмотров';
                    $max_count_views_value = 1;
                    $max_count_views_attribute_readonly = 'readonly';
                    $max_count_views_attribute_checked = 'checked';
                }
                else {
                    $max_count_views_value = 0;
                    $max_count_views_attribute_readonly = '';
                    $max_count_views_attribute_checked = '';
                }

                if ($assoc_u_a_s['enable'] == 0) {
                    $checkbox_enable_value = 0;
                    $checkbox_enable_attribute_checked = '';
                }
                else {
                    $checkbox_enable_value = 1;
                    $checkbox_enable_attribute_checked = 'checked';
                }
                echo '
                    <div>
                        <div id="container_edit_task">
                            <div class="stroke1_c1_sb1cp">
                                Редактировать задание в сёрфинге
                            </div>
                            <div class="strokebox2_c1_sb1cp">
                                <p class="p_strokebox2_c1_sb1cp">
                                    Заголовок ссылки
                                </p>
                                <input type="text" class="input_field" id="name_link" value="'.$assoc_u_a_s['name_link'].'">    
                                <p class="p_strokebox2_c1_sb1cp">
                                    URL сайта (включая http://)
                                </p>
                                <input type="text" class="input_field" id="url_site" value="'.$assoc_u_a_s['url_site'].'">
                                <p class="p_strokebox2_c1_sb1cp">
                                    Время просмотра ссылки
                                </p>';
                switch($assoc_u_a_s['time_watch']) {
                    case 20:
                        $selected_or_not1 = 'selected';
                        break;
                    case 30:
                        $selected_or_not2 = 'selected';
                        break;
                    case 40:
                        $selected_or_not3 = 'selected';
                        break;
                    case 50:
                        $selected_or_not4 = 'selected';
                        break;
                    case 60:
                        $selected_or_not5 = 'selected';
                        break;

                    default:
                        $selected_or_not1 = 'selected';
                        break;
                }               
                echo
                                '<select class="input_field" id="time_watch">
                                  <option '.$selected_or_not1.' value="20">20 секунд</option>
                                  <option '.$selected_or_not2.' value="30">30 секунд (+ 0.01 руб.)</option>
                                  <option '.$selected_or_not3.' value="40">40 секунд (+ 0.02 руб.)</option>
                                  <option '.$selected_or_not4.' value="50">50 секунд (+ 0.03 руб.)</option>
                                  <option '.$selected_or_not5.' value="60">60 секунд (+ 0.04 руб.)</option> 
                                </select>
                                <p class="p_strokebox2_c1_sb1cp">
                                    Стоимость одного просмотра
                                </p>
                                    <input type="text" class="input_field unactive" id="cost_watch" value="'.$assoc_u_a_s['cost_watch'].' руб." readonly>
                                    <input type="hidden" id="value_cost_watch" value="'.$assoc_u_a_s['cost_watch'].'">

                                    <input type="hidden" id="id_task" value="'.$assoc_u_a_s['id'].'">

                                    <div class="box_switch-and-text">
                                        <p class="switch_enable p_strokebox2_c1_sb1cp">Запустить сразу</p>

                                        <label class="switch">
                                          <input type="checkbox" id="checkbox_enable" value="'.$checkbox_enable_value.'" '.$checkbox_enable_attribute_checked.'>
                                          <span class="slider round"></span>
                                        </label>                                
                                    </div>

                                    <div class="box_switch-and-text b_s-a-t_2">
                                        <input type="text" class="switch_enable input_field count_views" id="max_count_views" value="'.$assoc_u_a_s['max_count_views'].'" '.$max_count_views_attribute.'>

                                        <label class="switch">
                                          <input type="checkbox" id="checkbox_count_views" value="'.$max_count_views_value.'" '.$max_count_views_attribute_checked.'>
                                          <span class="slider round"></span>
                                        </label>                                
                                    </div>
                                <div class="container_checkbox">
                                    <input type="checkbox" id="checkbox_ad" value="0"><p class="p_checkbox_text">Я согласен с <a href="/">правилами размещения рекламы на сайте</a></p>
                                </div>

                                <div class="centerer_button">
                                    <button class="button_add_surf" id="button_save_change" onclick="post_query(\'mine_shop/actions/save_edit_site_surf\', \'save_edit_site_surf\', \'id_task*+*name_link*+*url_site*+*time_watch*+*value_cost_watch*+*checkbox_enable*+*max_count_views*+*checkbox_ad\')">Сохранить изменения</button>
                                </div>
                                <p class="warning-edit">
                                    РЕДАКТИРОВАНИЕ
                                </p>
                            </div>            
                        </div>
                    </div>';
            }
            else {
                message('Забаненное задание нельзя редактировать', false, false, 'warning');
            }
        }
    }
}
else {
    message('Критическая ошибка#22edi', false, false, 'error');
}
?>