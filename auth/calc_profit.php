<?php
    header("Content-Type: text/html; charset=utf-8");  // стандартная установка кодировки
?>
<?php
top_auth('Калькулятор дохода', 'auth/style/calc_profitstyle');
?>

<?php

/*--------------VVVVVVVVVVVVVVVVVVVVVVVVVVVVVV------------------количество рудников каждой категории каждого уровня------------------*/

for ($category = 1; $category <= 4; $category++) {
    for ($level = 1; $level <= 7; $level++) { 

        $db->Query("SELECT COUNT(`id`) AS `amount` FROM `users_amount_mine` WHERE `uid` = '$_SESSION[id]' AND `category` = $category AND `level` = $level");
        $row_count_am_mi = $db->NumRows();
        if ( !empty($row_count_am_mi) ) {
            while ( $row = $db->FetchAssoc() ) {
                $amount = $row['amount'];
                foreach ($row as $key => $value) {
                    $as_count_am_mi[$key][$category][$level] = $value; // $value = $row[$key]
                }                
            }
        }
        else {
            $as_count_am_mi['amount'][$category][$level] = 0;
        }
    }
}
/*--------------AAAAAAAAAAAAAAAAAAAAAAAAAAAAAA------------------количество рудников каждой категории каждого уровня-------------------------*/

$db->Query('SELECT * FROM `mine_in_shop`');
$NumRows_mine = $db->NumRows();
if ( !empty($NumRows_mine) ) {
    while ( $row = $db->FetchAssoc() ) {
        $category = $row['category'];
        $level = $row['level'];
        foreach ($row as $key => $value) {
            $assoc_mine[$key][$category][$level] = $value; // $value = $row[$key]
        }
    }  
}

$db->Query('SELECT * FROM `data_mineral_to_sell`');
$NumRows_data_mineral = $db->NumRows();
if ( !empty($NumRows_data_mineral) ) {
    while ( $row = $db->FetchAssoc() ) {
        $category = $row['category'];
        foreach ($row as $key => $value) {
            $assoc_data_mineral[$key][$category] = $value; // $value = $row[$key]
        }
    }  
}
    echo '
        <div class="box_strokeafter_2_center_panel">
            <div class="box_1_center_panel">
    			<div class="stroke_3_box_1_center_panel">

    		        <div class="element_2_stroke_3_box_1_center_panel">
                        <div class="content_e2s3b1cp">
                            <p>
    				           Калькулятор дохода служит для расчета Вашей прибыли от нанятых персонажей. <b>Калькулятор 
                               не учитывает возможные бонусы, ускорения заработка от <a href="/leadrace">Гонки лидеров</a> а так же реферальные 
                               вознаграждения и конкурсы</b>. Калькулятор считает только чистую прибыль за сутки, месяц и 
                               год согласно доходности каждого из представленных на проекте персонажей. Для использования
                               калькулятора введите необходимое для расчета количество персонажей разных уровней в 
                               соответствующие формы, а затем нажмите кнопку в нижней части страницы. По умолчанию в 
                               формах уже введено количество персонажей которое уже наняты в Найт-Сити на данный момент.
    				        </p>
                        </div>
    		        </div>

    		    </div>
    		    <div class="stroke_4_box_1_center_panel">';

/*--------------------------------------разделение блоков---------------------------------------------------------*/

    for ($category = 1; $category <= 4; $category++) {  //checkpoint неправильно устанавливается цена минерала
        echo '
    		        <div class="element_stroke_4_box_1_center_panel">
                        <div class="th_line_es4b1cp">
    		           		<div class="p_th_line_es4b1cp">
    		           			Персонаж "'.$assoc_mine['first_name'][$category][1].'"

                                <input id="price_mineral_'.$category.'" type="hidden" value="'.$assoc_data_mineral['price_mineral'][$category].'">  
                                <input id="for_count_'.$category.'" type="hidden" value="'.$assoc_data_mineral['for_count'][$category].'">
    		           		</div>
                        </div>
                        <div class="line_es4b1cp">
                            <img class="image_line_es4b1cp" src="../img/auth/my_field/'.$assoc_mine['image_name'][$category][1].'">';
        for ($level = 1; $level <= 7; $level++) {
            echo '
                            <p class="text_line_es4b1cp">Персонаж '.$level.' уровня:</p>
                            <input id="input_amount_'.$category.'_'.$level.'" onfocus="this.className=\'onfocus\'" onblur="this.className=\'onblur\'" type="number" value="'.$as_count_am_mi['amount'][$category][$level].'" placeholder="Введте любое количество">

                            <input id="rate_mining_'.$category.'_'.$level.'" type="hidden" value="'.$assoc_mine['rate_mining'][$category][$level].'">
                            <input id="bonus_'.$category.'_'.$level.'" type="hidden" value="'.$assoc_mine['bonus'][$category][$level].'">
                            <input id="rate_seconds_'.$category.'_'.$level.'" type="hidden" value="'.$assoc_mine['rate_seconds'][$category][$level].'">
                            ';
    }                    
        echo '                       
                        </div>
    		       </div>';
    } 
/*--------------------------------------разделение блоков----------------------------------------------------------*/
    
    echo '                  		        		        		        
    		    </div>
                <div class="stroke_5_box_1_center_panel">
                    <div class="element_stroke_5_box_1_center_panel">
                        <div class="element_stroke_5_box_1_center_panel">
                            <div class="stroke_total_sum"><p>Доход за сутки:</p> <input id="income_for_day" class="total_sum" type="text" value="0 руб." readonly="readonly"></div>  
                            <div class="stroke_total_sum"><p>Доход за неделю:</p> <input id="income_for_week" class="total_sum" type="text" value="0 руб." readonly="readonly"></div>  
                            <div class="stroke_total_sum"><p>Доход за месяц:</p> <input id="income_for_month" class="total_sum" type="text" value="0 руб." readonly="readonly"></div>  
                            <div class="stroke_total_sum"><p>Доход за год:</p> <input id="income_for_year" class="total_sum" type="text" value="0 руб." readonly="readonly"></div>
                        </div>
                    </div>
                </div>
        </div>

        <script>
            $(document).ready(function() {
                income_for_day = 0;
                income_for_week = 0; 
                income_for_month = 0;
                income_for_year = 0;                

                function calcul() {     /*создаем функцию подсчета*/

                    income_for_day = 0;
                    income_for_week = 0; 
                    income_for_month = 0;
                    income_for_year = 0; 

                    for (category = 1; category <= 4; category++) {
                        for (level = 1; level <= 7; level++) {
                            input_amount = +document.getElementById("input_amount_" + category + "_" + level).value; 
                            
                            rate_mining = +document.getElementById("rate_mining_" + category + "_" + level).value;
                            bonus = +document.getElementById("bonus_" + category + "_" + level).value;
                            rate_seconds = +document.getElementById("rate_seconds_" + category + "_" + level).value;
                            price_mineral = +document.getElementById("price_mineral_" + category).value;     /*цена минералов*/
                            for_count = +document.getElementById("for_count_" + category).value;     /*за количество*/
                            
                            income_blank = +( input_amount * (rate_mining + bonus) * rate_seconds / for_count * price_mineral );     /*добыча за час*/ /*не происходило вычисления, потому что тип переменной был строковым*/
                            
                            income_for_day += +( income_blank * 24 );    /*доход за сутки*/
                            income_for_week += +( income_blank * 24 * 7 );    /*доход за неделю*/
                            income_for_month += +( income_blank * 24 * 30 );    /*доход за месяц*/
                            income_for_year += +( income_blank * 24 * 365 );    /*доход за год  */

                        }
                    }

                    document.getElementById("income_for_day").value = income_for_day.toFixed(2) + " руб.";      /*прибыль за день*/
                    document.getElementById("income_for_week").value = income_for_week.toFixed(2) + " руб.";    /*прибыль за неделю*/            
                    document.getElementById("income_for_month").value = income_for_month.toFixed(2) + " руб.";    /*прибыль за месяц*/
                    document.getElementById("income_for_year").value = income_for_year.toFixed(2) + " руб.";        /*прибыль за год*/                               
                };


                calcul();   /*вызываем функцию подсчета сразу, чтобы посчитать прибыль с уже имеющимися персонажами*/
                      
                for (category = 1; category <= 4; category++) {
                    for (level = 1; level <= 7; level++) {                
                        
                        a = document.getElementById("input_amount_" + category + "_" + level);
                        a.onchange = a.onkeyup = function() {   
                            calcul();   /*когда меняем значение в каком-либо поле, вызываем функцию подсчета*/        
                        };
                    }                
                } 
            });
        </script>
        ';

bottom_auth();

?>