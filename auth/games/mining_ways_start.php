<?php
    header("Content-Type: text/html; charset=utf-8");  // стандартная установка кодировки
?>
<?php
top_auth('Пути добычи', 'auth/games/style/mining_waysstyle_start');
?>

<?php

echo '
    <div class="box_strokeafter_2_center_panel">
        	<div class="centerer_e1s3_center_panel">	
                <div class="element_1_stroke_3_box_1_center_panel">
                    <div class="box_elements_e1s3b1cp">
        				<div class="text_e1s3b1cp">
        		        '.MessageShow().' 

                        <a href="/schulte_tab_event">Таблица внимательности</a>
                            <form method="post" action="request/mining_waysrequest_start">
                                <input type="number" name="sum_bet" value="0">
                                <input type="hidden" name="user_id" value="'.$_SESSION['id'].'">

                                <button type="submit">Создать игру</button>
                            </form>';


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