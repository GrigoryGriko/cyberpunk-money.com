<?php
    header("Content-Type: text/html; charset=utf-8");  // стандартная установка кодировки
?>
<?php
top_auth('Конкурсы', 'auth/style/contest_gamestyle');
?>

<?php


/*$date1 = '2020-03-16 00:00:00';
$date2 = '2020-03-23 23:59:59';

$join = 'Пока не участвуете';
$color_style = '#ffbe27';

$count_participant = 0;
$db->Query("SELECT SUM(`money_payin`) AS `sum_money_payin`, `uid` FROM `history_money_payin` WHERE `date_payin` BETWEEN '$date1' AND '$date2' GROUP BY `uid`");
$NumRows_h_m_p = $db->NumRows();
if ( !empty($NumRows_h_m_p) ) {

    while ( $row_h_m_p = $db->FetchAssoc() ) {
        if ($row_h_m_p['sum_money_payin'] == null) $row_h_m_p['sum_money_payin'] = 0;

        $SELECT_QUERY = $db->Query_recordless("SELECT `id`, `login` FROM `users` WHERE `id` = '$row_h_m_p[uid]'");        //чекпоинт //делать запросы по старинке

        $count_participant++;

        $NumRows_u = mysqli_num_rows($SELECT_QUERY);
        if ( !empty($NumRows_u) ) {
            $assoc_u = mysqli_fetch_assoc($SELECT_QUERY);

            /*echo '
                '.$assoc_u['id'].'-'.$assoc_u['login'].'+'.floor($row_h_m_p['sum_money_payin']).'<small>₽</small> <br>';*/


          /*  @mysqli_free_result($SELECT_QUERY); //очистка пямяти от запроса
           
        }
        else {
            echo 'Error_empty0x01';
        }  */              
        /*}
        else {
            $join = 'Пока не участвую';
            $color_style = 'yellow';
        } */ 
        /*if ($assoc_u['id'] == $_SESSION['id'] and $row_h_m_p['sum_money_payin'] >= 10) {
            $join = 'Участвуете';
            $color_style = '#4eb53c';
        }
    }
}*/

echo '
    <div class="box_strokeafter_2_center_panel">
        <div class="box_1_center_panel">
			<div class="stroke_3_box_1_center_panel">
		        <div class="element_1_stroke_3_box_1_center_panel">
                <div class="p2_img_e1s3b1cp">
                    <img src="../img/auth/leadrace/icon_gift.png" class="img_e1s3b1cp">
                    <p style="color: '.$color_style.'" id="p2">В ожидании</p>
                </div>
		        	<!-- <div class="text_img_e1s3b1cp">
			        	<p id="p1">Участие от 10 руб.</p>
			        	<div class="p2_img_e1s3b1cp">
				        	<img src="../img/auth/leadrace/icon_gift.png" class="img_e1s3b1cp">
				        	<p style="color: '.$color_style.'" id="p2">Конкурс завершен</p>
				        </div>

                        <div class="text_bottom">
                            Победители:
                            <br>1-е место alina
                            <br>2-е место IREN72
                            <br>3-е место skynnyman_61
                            <br>
                            <br>degtyarenko
                            <br>egor722910
                            <br>kate73
                            <br>dany9345
                            <br>ehhm27
                        </div>
		        	</div> -->

		        </div>
		        <div class="element_2_stroke_3_box_1_center_panel">
                <div class="content_e2s3b1cp"><h2>Конкурсов пока еще не проводилось.</h2></div>
                    <!-- <div class="content_e2s3b1cp">
                        
				            <h1>16.03.2020 стартовал конкурс (участие от 10 руб.)!</h1>
                            <h2>Разыгрываются "Кимберлитовые рудники" стоимостью по 100 руб! Победители будут выбраны случайным образов.</h2>
                            <p>Призы:
                            <br>1-е место - 7 кимберлитовых рудников
                            <br>2-е место - 3 кимберлитовых рудников
                            <br>3-е место - 1 кимберлитовый рудников
                            <br><br>Победители будут определены 24 марта в 19:30.

                            <br><b>Для желающих участвовать нужно пополнить баланс 
                            на сумму всего лишь от 10 руб. в период конкурса - с <u>16.03.2020</u> до <u>23.03.2020</u></b>
				        </p>
                    </div> -->
		        </div>
		    </div>

		    <div class="stroke_4_box_1_center_panel">
        		        		        
		    </div>
    </div>';

bottom_auth();

?>