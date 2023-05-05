<?php
    header("Content-Type: text/html; charset=utf-8");  // стандартная установка кодировки
?>
<?php
top_auth('Таблица уровней', 'auth/style/leveltabstyle');
?>

<?php
echo '
    <div class="box_strokeafter_2_center_panel">
        <div class="box_1_center_panel">
            ';
$db->Query("SELECT * FROM `data_mineral_to_sell`");
$NumRows = $db->NumRows(); 
    if ( !empty($NumRows) ) {
        while ( $row = $db->FetchAssoc() ) {
            $num_category = $row['category'];
            foreach ($row as $key => $value) {
                $assoc_d_m_t_s[$key][$num_category] = $value; // $value = $row[$key] //данные рудников всех уровней одной категории
            }
        }
    }
    else {
    	echo 'Error_empty';
    }

function table_levels_mine($category) {
    global $db;
    global $assoc_d_m_t_s;
    $db->Query("SELECT * FROM `mine_in_shop` WHERE `category` = $category");
    $NumRows = $db->NumRows(); 
    if ( !empty($NumRows) ) {
        while ( $row = $db->FetchAssoc() ) {
            $num_level = $row['level'];
            foreach ($row as $key => $value) {
                $assoc_m_i_s[$key][$num_level] = $value; // $value = $row[$key] //данные рудников всех уровней одной категории
            }
        }
    switch ($category) {
        case 1:
            $mineral_image = 'tourmaline.png';
            break;
        case 2:
            $mineral_image = 'topaz.png';
            break;
        case 3:
            $mineral_image = 'emerald.png';
            break;
        case 4:
            $mineral_image = 'diamond.png';
            break;

        default:
            $mineral_image = 'tourmaline.png';
            break;
    }

    echo '
            <div class="element_1_stroke_4_box_1_center_panel">
                <div class="box_e1s4b1cp">
                    <table class="table_element_1_stroke_4_box_2_center_panel">
                        <tr>
                            <th colspan="6" class="th_1">
                                <div class="image_text_into_td">
                                    <img class="th_1_img" src="../img/auth/leveltab/icon_market.png" width="15px" height="15px">
                                    Таблица уровней '.$assoc_m_i_s['second_name'][1].' "'.$assoc_m_i_s['first_name'][1].'"
                                </div>
                            </th>
                        </tr>   
                        <tr id="tr_fill">
                            <td rowspan="2">
                                Уровень
                            </td>
                            <td rowspan="2">
                               Доходность
                            </td>
                            <td colspan="2">
                                Добыча (в минуту)
                            </td> 
                            <td rowspan="2">
                                Стоимость
                            </td>
                            <td rowspan="2">
                                Затраты
                            </td>                       
                        </tr>
                        <tr id="tr_fill">
                            <td>Обычная</td> <td>Бонус</td>
                        </tr>
                        ';
            for ($level = 1; $level < 8; $level++)  {       //вывод всех записей по 7-ми уровням данной категории
                
                    $assoc_m_i_s_rate_mining_level = '<img class="image_mineral" src="../img/auth/my_field/'.$mineral_image.'"> '.round($assoc_m_i_s['rate_mining'][$level], 0);
                if ($assoc_m_i_s['bonus'][$level] == 0) {
                    $assoc_m_i_s_bonus_level = '--';
                }
                else {
                    $assoc_m_i_s_bonus_level = '<img class="image_mineral" src="../img/auth/my_field/'.$mineral_image.'"> '.round($assoc_m_i_s['bonus'][$level], 1);
                }

                echo' 
                        <tr class="contain_data">
                            <td class="lvl_num">
                                <div class="col_1_table_e1s3b1cp">
                                    '.$assoc_m_i_s['level'][$level].'
                                </div>
                            </td>
                            <td>
                                '.$assoc_m_i_s['income'][$level].' % <span>в мес.</span>
                            </td>
                            <td>
                                <div class="image_text_into_td">
                                    '.$assoc_m_i_s_rate_mining_level.'
                                <div class="image_text_into_td">
                            </td> 
                            <td>
                                <div class="image_text_into_td">
                                    '.$assoc_m_i_s_bonus_level.'</td> <td>'.round($assoc_m_i_s['price'][$level], 0).' <span>руб.</span>
                                </div>
                            </td>
                            <td>
                                '.round($assoc_m_i_s['total_cost'][$level], 0).' <span>руб.</span>
                            </td>
                        </tr>
                ';
            }

            echo'
                       	<tr>
    						<th colspan="6" class="th_2">
                                <div class="image_text_into_td">
                                    <img class="image_i" src="../img/auth/leveltab/i.png" width="9px" height="9px">
                                    Курс продажи 1000 образцов <span>"'.substr($assoc_m_i_s['first_name'][1], 0, -4).'ого '.$assoc_m_i_s['second_name'][1].'а"</span>: '.$assoc_d_m_t_s['price_mineral'][$category].'
                                </div>
                            </th>  <!--Изменение окончания слова-->
    					</tr>  
                    </table>    

                </div>   
            </div>';
    }
    else {
        echo 'Error_empty';
    }
}

echo '
    		<div class="element_1_stroke_3_box_1_center_panel">
                <div class="box_imt_text">
    				<img class="img_e1s3b1cp" src="../img/auth/leveltab/leveltab_icon.png">
    				<p class="text_e1s3b1cp">
    					У всех персонажей в нашем проекте есть 7 уровней, каждый уровень имеет свою цену и доходность, уровни
                        тщательно сбалансированы между собой, дабы не допустить неравномерное распределение дохода.
    					Повышайте уровень персонажей, чтобы получать больше артефактов, а также дополнительные бонусы, начиная со 2
                        уровня. В дополнение мы разработали для Вас специальный <a href="/calc_profit">калькулятор</a> для расчета собственного дохода.
    				</p>
                </div>
    		</div>';

table_levels_mine(1);
table_levels_mine(2);
table_levels_mine(3);
table_levels_mine(4);

echo '
        </div>
    </div>';

    bottom_auth();

?>