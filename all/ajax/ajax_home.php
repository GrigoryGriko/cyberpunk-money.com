<?php
    usleep(50000);
/*__________счетчик прошедшего со старта времени______VVVVVVVVV---*/

$date_start = strtotime("2021-06-05 12:00:00"); //условно старт проекта (еще переменная в home)
$passed_time = time() - $date_start;

$count_days = $passed_time / 60 / 60 / 24;
$count_hours = ( $count_days - floor($count_days) ) * 24;
$count_minutes = ( $count_hours - floor($count_hours) ) * 60;
$count_seconds = ( $count_minutes - floor($count_minutes) ) * 60;

/*__________счетчик прошедшего со старта времени______AAAAAAAAA---*/

echo '  
    <div>
        <div id="passed_time"> 
            <li>
                <div id="element_1_counter_container_1">'.floor($count_days).'
                    <span>Дней</span>
                </div>
            </li>
            <li>
                <div id="element_2_counter_container_1">'.floor($count_hours).'
                    <span>Часов</span>
                </div>
            </li>
            <li>
                <div id="element_3_counter_container_1">'.floor($count_minutes).'
                    <span>Минут</span>
                </div>
            </li>
            <li>';
echo '
                                <div id="element_4_counter_container_1">';
echo '
                                    <img src="/img/home/clock.png" width="54px" height="53px">';
/*echo '                              
                                    '.round($count_seconds).'
                                    <span>Секунд</span>';*/
echo ' 
                                </div>
            </li>
        </div>
    </div>
';
?>
