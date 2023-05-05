<?php
usleep(50000);
/*
1)выбираем рандомное количество рандомных ботов
2)создаем строку с рандомной суммой пополнения в диапазоне от 42 до 731 руб., платежной системой из данных, переменной bot = 1 
3) 
*/

$amount_bot_payin = rand(22, 37); //количество рандомных ботов, запускать скрипт 4 раза в день
$db->Query('SELECT `id` FROM `users` WHERE `isBOT` = 1');
$NumRows = $db->NumRows();
if ( !empty($NumRows) ) {
    while ( $row = $db->FetchAssoc() ) {
        $num = $row['id'];
        foreach ($row as $key => $value) {
            $assoc_user_bot[$key][$num] = $value; // $value = $row[$key]
        }
    }
    $array_rand_keys = array_rand($assoc_user_bot['id'], $amount_bot_payin);
}
$smp = 0;
for ($n = 0; $n < $amount_bot_payin; $n++) {    //обрабатываем каждый элемент в массиве

    $random_ruling_randomsum = rand(1, 30);
    $r_r_r = $random_ruling_randomsum;
    if ($r_r_r == 5) {
        $sum_money_payin = rand(31, 1110);
    }
    else if ($r_r_r == 3 or $r_r_r == 7 or $r_r_r == 6) {
         $sum_money_payin = rand(10, 321);
    }
    else {
        $sum_money_payin = rand(7, 131);   //диапазон суммы пополнения
    }
    

    $payment_system = array('payeer', 'payeer', 'payeer', 'payeer', 'payeer', 'СБЕРБАНК ОНЛАЙН', 'payeer', 'payeer', 'freekassa', 'freekassa', 'QIWI', 'БИЛАЙН', 'МТС', 'МЕГАФОН', 'Яндекс.Деньги'); //платежные системы
    $key_p_s = array_rand($payment_system, 1); 

    $INSERT_QUERY = $db->Query_recordless('INSERT INTO `history_money_payin` VALUES (NULL, "'.$array_rand_keys[$n].'", "'.$sum_money_payin.'", "balance_buy", "'.$payment_system[$key_p_s].'", 777, "RUB", NOW(), NOW(), "success", 1)');

    echo $array_rand_keys[$n].'-'.$sum_money_payin.'+'.$payment_system[$key_p_s];

    $smp += $sum_money_payin;

    @mysqli_free_result($INSERT_QUERY);
}
echo '/'.$smp.'*<u>'.$amount_bot_payin.'</u>'; /*определение средней суммы пополнения на всех*/
?>