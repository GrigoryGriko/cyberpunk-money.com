<?php
    header("Content-Type: text/html; charset=utf-8");  // стандартная установка кодировки
?>
<?php

for ($days = 0; $days <= 6; $days++) {
    $days_ago_in_seconds = time() - (60 * 60 * 24 * $days);
    $a_days_ago_1 = date("Y-m-d 00:00:00", $days_ago_in_seconds);
    $a_days_ago_2 = date("Y-m-d 23:59:59", $days_ago_in_seconds);

    $date[$days] = date("Y-m-d", $days_ago_in_seconds);;
    $db->Query("SELECT COUNT(`id`) AS `amount_reg` FROM `users` WHERE `date_reg` BETWEEN '$a_days_ago_1' AND '$a_days_ago_2' AND (`ref` = '$_SESSION[id]' OR `ref_lvl_2` = '$_SESSION[id]')"); //пытается натйи строки с точным посекундным временем
    $numrows_date_reg = $db->NumRows();
    if ( !empty($numrows_date_reg) ) {
       $as_count_ar[$days] = $db->FetchAssoc();
    }
    else {
        $as_count_ar[$days]['amount_reg'] = 0;
    }
}

top_auth('Список рефералов', 'auth/style/my_refsstyle', false, 'my_refs');
?>

<?php
echo '
    <div class="box_strokeafter_2_center_panel">
        	<div class="centerer_e1s3_center_panel">	
                <div class="element_1_stroke_3_box_1_center_panel">
                    <div class="box_elements_e1s3b1cp">
        				<div class="text_e1s3b1cp">
        					
                            <div class="canvas_graphic">

                                <div id="chart_div" style="width: 100%; height: 350px;"></div>
                            
                            </div>
        				</div>
                    </div>
        		</div>
            </div>

            <div class="element_1_stroke_4_box_1_center_panel">
                
            </div>

            <div class="box_stroke_5_box_1_center_panel">
                <h1>Список рефералов</h1>

                <div class="div_table_e1s4b1c">
                    <table class="table_e1s4b1c">
                        <tr>
                            <th>Логин реферала</th>
                            <th>Доход с реферала</th>
                            <th>Сумма пополнений</th>
                            <th>Уровень реферала</th>
                            <th>Дата регистрации</th>
                        </tr>';


                $db->Query("SELECT `percent_ref_level1`, `percent_ref_level2` FROM `users` WHERE `id` = '$_SESSION[id]'");
                $row_u_p_r = $db->NumRows();
                if ( !empty($row_u_p_r) ) { 
                    $assoc_u_p_r = $db->FetchAssoc();
                }
                else {
                    $assoc_u_p_r['percent_ref_level1'] = 0;
                    $assoc_u_p_r['percent_ref_level2'] = 0;
                }
                /*-------Список рефералов----VVVVVVVVVVVVV-----*/

                $db->Query("SELECT * FROM `users` WHERE `ref` = '$_SESSION[id]' OR `ref_lvl_2` = '$_SESSION[id]'");        
                $row_history_daily_bonus = $db->NumRows();
                if ( !empty($row_history_daily_bonus) ) {       
                    while ( $row = $db->FetchAssoc() ) {
                        if ($row['ref'] == $_SESSION['id']) $level_ref = 1; 
                        else if ($row['ref_lvl_2'] == $_SESSION['id']) $level_ref = '2-й';
                        else $level_ref = 0;

                        $sum_money_payin = 0;
                        $sum_money_earn = 0;
                        $query_sum = $db->query_recordless("SELECT SUM(`money_payin`) AS `sum_money_payin` FROM `history_money_payin` WHERE `uid` = '$row[id]'");
                        //$row_sum_payin = mysqli_num_rows($query_sum);
                        while ( $num = mysqli_fetch_assoc($query_sum) ) {
                            $sum_money_payin += $num['sum_money_payin'];
                        }
                        if ($row['ref'] == $_SESSION['id']) {
                            $sum_money_earn = $sum_money_payin * $assoc_u_p_r['percent_ref_level1']/100;
                        } 
                        else if ($row['ref_lvl_2'] == $_SESSION['id']) {
                            $sum_money_earn = $sum_money_payin * $assoc_u_p_r['percent_ref_level2']/100;
                        }
                        else {
                            $sum_money_earn = 0;
                        }
                        /*if ( !empty($row_sum_payin) ) {
                            $assoc_sum_payin = mysqli_fetch_assoc($query_sum);
                            echo '-'.$assoc_sum_payin['sum_money_payin'].'-';
                        }
                        else {
                            $assoc_sum_payin['sum_money_payin'] = 0;
                        }*/

                        echo
                            '<tr>
                                <td>
                                    '.$row['login'].'
                                </td>
                                <td>
                                    '.$sum_money_earn.' руб.
                                </td> 
                                <td>
                                    '.$sum_money_payin.' руб.  
                                </td>
                                <td>
                                    '.$level_ref.'   
                                </td>
                                <td>
                                    '.$row['date_reg'].'   
                                </td>
                            </tr>';                           
                    }
                }
                else {
                    echo 'У вас еще нет ни одного реферала';
                }

                @mysqli_free_result($query_sum);
                /*-------Список рефералов----AAAAAAAAAAAAAA-----*/

                echo
                    '</table>
                </div>

            </div>
        </div>
    </div>
    ';

    bottom_auth();

?>