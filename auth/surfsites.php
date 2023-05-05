<?php
    header("Content-Type: text/html; charset=utf-8");  // стандартная установка кодировки
?>
<?php
top_auth('Сёрфинг сайтов', 'auth/style/surfsitesstyle', false, 'surfsites');
?>

<?php
echo '
    <div class="box_strokeafter_2_center_panel">
    		<div class="strokeblock1_cp">
                <div class="container1_sb1cp">
                    <p class="text_container1_sb1cp">
                        Нажмите по заголовку любой из доступных ссылок, дождитесь окончания таймера и получайте
                        деньги на свой <b>баланс для вывода</b>! Средства заработанные в сёрфинге сайтов Вы можете
                        <a href="/exchange_money">обменять</a> и потратить на расширение своей бригады в
                        <a href="/my_field">Найт-Сити</a>, потратить на рекламную
                        компанию или просто вывести их из проекта любым удобным для Вас способом!
                    </p>
                </div>
                <div class="container2_sb1cp">
                    <p>Вам необходимы посетители или рефералы?</p>
                    <a href="/addsurf">
                        <button id="container2_sb1cp_button" class="container2_sb1cp_button">
                            <img id="img_icon_button_01" class="img_style_icon_button_01" src="../img/auth/surfsites/icon_button_01.png">
                            <img style="display: none;" id="img_icon_button_02" class="img_style_icon_button_01" src="../img/auth/surfsites/icon_button_02.png">
                            
                            <div class="b">Разместить сайт в сёрфинге</div>
                        </button>
                    </a>
                </div>
            </div>

            <div class="ad_block">
                <div class="title_s">Спонсор сёрфинга:</div>

                <div id="linkslot_321368"><script src="https://linkslot.ru/bancode.php?id=321368" async></script></div>

                <div class="line_bord"></div>
            </div>

            <div id="strokeblock2_cp">';

                surfing_ajax_content();

echo '
            </div>
        </div>
    </div>';

    bottom_auth();

?>