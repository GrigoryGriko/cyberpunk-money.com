<?php
    header("Content-Type: text/html; charset=utf-8");  // стандартная установка кодировки
?>
<?php
top_auth('Игры', 'auth/style/gamesstyle');
?>
<?php
echo '
    <div class="box_strokeafter_2_center_panel">
            <div class="centerer_e1s3_center_panel">    
                <div class="element_1_stroke_3_box_1_center_panel">
                    <div class="box_elements_e1s3b1cp">
                        В этом разделе Вы можете сыграть в игры с реальными сопперниками на деньги. На данный момент доступна игра, в которой победит самый внимательный. Играйте, тренируйтесь, развивайте качества и выигрывайте!
                        <div class="banner_linkslot">
                            <!-- Спонсор игр:
                            <div id="linkslot_285611"><script src="https://linkslot.ru/bancode.php?id=285611" async></script></div> -->';


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
                <a class="flex_a" href="/schulte_tab_start">
                    <img src="/img/auth/games/schulte_tab_icon.jpg">
                    Таблица внимательности
                </a>';                     

                echo
                    '
            </div>
        </div>
    </div>
    ';

    bottom_auth();

?>