<?php
    usleep(50000);
    header("Content-Type: text/html; charset=utf-8");  // стандартная установка кодировки
?>
<?php
echo '
    <div>
        <div id="button_change_password">';
        if ($_SESSION['fail_change_password'] >= 20) {    //если пользователь неудачно авторизовался 10 раз и более, то...
            echo '
                <button class="set_button_blue input_button_style" onclick="ajax_button_change_password_failsuccess(); field_captcha_show();">
                    <img id="setting_icon_button_2" class="icon_button" src="../img/auth/setting_account/setting_icon_button.png" style="display: block" width="15px" height="15px">
                    <img id="setting_icon_button_2_02" class="icon_button" src="../img/auth/setting_account/setting_icon_button_02.png" style="display: none" width="15px" height="15px">
                    Изменить пароль
                </button>';
        }
        else {
            echo '
                <button id="set_parametr_1" class="set_button_blue input_button_style" type="submit" onclick="post_query(\'mine_shop/actions/request_setting_account\', \'change_password\', \'current_password*+*new_password*+*confirm_password\');">
                    <img id="setting_icon_button_2" class="icon_button" src="../img/auth/setting_account/setting_icon_button.png" style="display: block" width="15px" height="15px">
                    <img id="setting_icon_button_2_02" class="icon_button" src="../img/auth/setting_account/setting_icon_button_02.png" style="display: none" width="15px" height="15px">
                    Изменить пароль
                </button>';
        }
    echo '
    </div>
    ';
?>