<?php
    header("Content-Type: text/html; charset=utf-8");  // стандартная установка кодировки
?>
<?php
top_auth('Гонка лидеров', 'auth/style/leadracestyle');
?>

<?php
function query_money_payin($date1, $date2, $time) {
    global $db;
    global $assoc_leadrace;

    $db->Query("SELECT SUM(`money_payin`) AS `sum_money_payin`, `uid` FROM `history_money_payin` WHERE `date_payin` BETWEEN '$date1' AND '$date2' GROUP BY `uid` ORDER BY `sum_money_payin` DESC LIMIT 10");
    $NumRows_h_m_p = $db->NumRows();
    if ( !empty($NumRows_h_m_p) ) {
        $num_class = 1;

        $num_user_place = 1;

        while ( $row_h_m_p = $db->FetchAssoc() ) {
            $SELECT_QUERY = $db->Query_recordless("SELECT `id`, `login` FROM `users` WHERE `id` = '$row_h_m_p[uid]'");        //чекпоинт //делать запросы по старинке

            $NumRows_u = mysqli_num_rows($SELECT_QUERY);
                if ( !empty($NumRows_u) ) {
                    $assoc_u = mysqli_fetch_assoc($SELECT_QUERY);
                    

                    if ($assoc_u['id'] == $_SESSION['id'] ) {
                        echo '
                           <div class="line_3_es4b1cp">';
                    }
                    else {
                        echo '
                           <div class="line_'.$num_class.'_es4b1cp">';
                    }
                          
                    echo '
                            <p id="p1_es4b1cp">
                                '.$assoc_u['login'].'
                            </p>
                            <p id="p2_es4b1cp">
                            	'.floor($row_h_m_p['sum_money_payin']).'<small>₽</small> ';

                    if ($num_user_place <= 5) {
                    	switch ($num_user_place) {
                    		case 1:
		                    	$percent_user_income = 10;
		                    	break;
		                    case 2:
		                    	$percent_user_income = 7;
		                    	break;
		                    case 3:
		                    	$percent_user_income = 5;
		                    	break;
		                    case 4:
		                    	$percent_user_income = 3;
		                    	break;
		                    case 5:
		                    	$percent_user_income = 1;
		                    	break;
		                    default:
		                    	$percent_user_income = 0;
		                    	break;
                    	}
                    	echo '<sup style="color: #4a89dc">+'.$percent_user_income.'%</sup>';
                    }

                    echo '
                            </p>
                       </div>';

                    if ($num_class % 2 == 0) $num_class = 1;
                    else $num_class = 2;

                    @mysqli_free_result($SELECT_QUERY); //очистка пямяти от запроса
                   
                }
                else {
                    echo 'Error_empty0x01';
                }
            $num_user_place++;
        }
        switch ($time) {
            case 24:
                $time_period = $time * 3600;
                break;
            default:
                $time_period = $time * 24 * 3600;
                break;
        }

        $start_new_period = strtotime($date1) + $time_period;
        $start_new_period = date('d.m.Y в G ч.', $start_new_period);  //к дате начала периода прибавляем длительность периода, получаем дату окончания периода и начала нового    
        echo '
            <div class="line_'.$num_class.'_es4b1cp last">
                <div class="next_period">
                    Следующий период: '.$start_new_period.'
                </div>
            </div>
        ';

    }
    else {
        echo '
            <div class="box_3_es4b1cp">
            	<p id="p3_es4b1cp">
                     Лидеров нет<br>
                     <span>(пополни счет и попадешь сюда)</span>
                </p>
            </div>';
    }
}

$db->Query("SELECT `leader_bonus_percent_income1`, `leader_bonus_percent_income2`, `leader_bonus_percent_income3`, `leader_bonus_percent_income4` FROM `users` WHERE `id` = '$_SESSION[id]'");
$NumRows_user = $db->NumRows();
if ( !empty($NumRows_user) ) {
    $assoc_user = $db->FetchAssoc();

    $sum_leader_bonus_percent_income = $assoc_user['leader_bonus_percent_income1'] + $assoc_user['leader_bonus_percent_income2'] + $assoc_user['leader_bonus_percent_income3'] + $assoc_user['leader_bonus_percent_income4'];
}
else {
    echo 'Error_Nonexen--User';
}

$db->Query("SELECT * FROM `leadrace_date`");
$numrows_leadrace = $db->NumRows();
if ( !empty($numrows_leadrace) ) {
    while ( $row = $db->FetchAssoc() ) {
        $num = $row['time_period'];
        foreach ($row as $key => $value) {
            $assoc_leadrace[$num][$key] = $value; // $value = $row[$key]
        }
    }

    echo '
        <div class="box_strokeafter_2_center_panel">
            <div class="box_1_center_panel">
    			<div class="stroke_3_box_1_center_panel">
    		        <div class="element_1_stroke_3_box_1_center_panel">
    		        	<div class="text_img_e1s3b1cp">
    			        	<p id="p1">Обновление данных происходит раз в час</p>
    			        	<div class="p2_img_e1s3b1cp">
    				        	<img src="../img/auth/leadrace/icon_gift.png" class="img_e1s3b1cp">
    				        	<p id="p2">Ваш бонус: '.$sum_leader_bonus_percent_income.'%</p>
    				        </div>
    		        	</div>

    		        </div>
    		        <div class="element_2_stroke_3_box_1_center_panel">
                        <div class="content_e2s3b1cp">
                            <p>
    				           Гонка лидеров - это дополнительный способ увеличить доход до 40%. При условии, что Вы
    				           являетесь лидером по пополнениям в проекте за 24 часа, неделю, месяц или год. Все бонусы
    				           суммируются, Вы можете одновременно быть лидером за 24 часа, неделю, месяц, год и
    				           получать постоянный бонус к урожаю 40%.
    				        </p>
                        </div>
    		        </div>
    		    </div>
    		    <div class="stroke_4_box_1_center_panel">
    		        <div class="element_stroke_4_box_1_center_panel">
    		           <div class="th_line_es4b1cp">
    		           		<div class="p_th_line_es4b1cp">
    		           			Лидеры за 24 часа
    		           		</div>
    		           </div>';

    $section_date2 = date('y/m/j').' 23:59:59.999';     //Определение лидера за период времени

    query_money_payin($assoc_leadrace[24]['date_start'], $section_date2, 24);

    echo '
    		       </div>
    		        
    		        <div class="element_stroke_4_box_1_center_panel">
                       <div class="th_line_es4b1cp">
                            <div class="p_th_line_es4b1cp">
    		           			Лидеры за 7 дней
    		           		</div>
                       </div>';
                             
    query_money_payin($assoc_leadrace[7]['date_start'], $section_date2, 7);

    echo '        
    		        </div>

    		        <div class="element_stroke_4_box_1_center_panel">
                       <div class="th_line_es4b1cp">
                            <div class="p_th_line_es4b1cp">
    		           			Лидеры за 30 дней
    		           		</div>
                       </div>';
              
    query_money_payin($assoc_leadrace[30]['date_start'], $section_date2, 30);

    echo '       
    		        </div>

    		        <div class="element_stroke_4_box_1_center_panel">
                       <div class="th_line_es4b1cp">
                            <div class="p_th_line_es4b1cp">
    		           			Лидеры за 365 дней
    		           		</div>
                       </div>';
                
    query_money_payin($assoc_leadrace[365]['date_start'], $section_date2, 365);

    echo '  
    		        </div>		        		        		        
    		    </div>
        </div>';
}

bottom_auth();

?>