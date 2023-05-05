<?php
function nickname_gen() {       //функция генерирует и возвращает слово на латинице, алгоритм: каждая вторая буква гласная, остальные согласные
/*$symbol_arr = array('aeiouy', 'bcdfgahjklmnpeqrstvwxz');
    $length = mt_rand(5, 8);
    $return = array();
    foreach ($symbol_arr as $k => $v)
        $symbol_arr[$k] = str_split($v);
    for ($i = 0; $i < $length; $i++) {
        while (true) {
            $symbol_x = mt_rand(0, sizeof($symbol_arr) - 1);
            $symbol_y = mt_rand(0, sizeof($symbol_arr[$symbol_x]) - 1);
            if ($i > 0 && in_array($return[$i - 1], $symbol_arr[$symbol_x]))
                continue;
            $return[] = $symbol_arr[$symbol_x][$symbol_y];
            break;
        }
    }
    $return = ucfirst(implode('', $return));*/

    $permitted_chars = "abcdefgohiajkelmenaopqerastuvewaxoyz";
    $return = substr(str_shuffle($permitted_chars), 4, 6);
    return $return;
}

$password_user_bot = '27011996';
$password_user_bot = hash('sha256', $password_user_bot);

$amount_users_bot = rand(80, 139);
for ($c = 1; $c <= $amount_users_bot; $c++) {
    $prefics_nick = ''; //присваиваем пустое значение
    $nick_number = rand(1, 4);  //генерирует случайное число от 1 до 4    
    if ($nick_number == 3) {       //если сгенерированное число равно 3, то...
        $prefics_nick = rand(1, 891);       //генерируем число от 1 до 891
    }
    $mail_domain_array = array('mail.ru', 'yandex.ru', 'rambler.ru', 'gmail.com'); //массив с доменами емэйла з которого случайно выбираем один
    $domain_mail = array_rand($mail_domain_array, 1); 


    $nickname = nickname_gen().$prefics_nick; //вызываем функцию выше и присоединяем к ее результату значение $prefics_nick    
    $email = $nickname.'@'.$mail_domain_array[$domain_mail];  //создаем емэйл(присоединяем к нику $nickname символ '@' и случайное доменное имя )
    echo $nickname.'-'.$email.'<br>';

    if ( !isset($_SESSION['origURL']) ) {
        $_SESSION['origURL']= 'нет данных';
    }

    $INSERT_INTO_QUERY0 = $db->Query_recordless('INSERT INTO `users` VALUES (NULL, "'.$email.'", "'.$password_user_bot.'", "", "'.$nickname.'", "Без имени", 1, 0, NOW(), 0, 0, 1, 0, 0, 7, 3, 0.005, 5, 0, 0, 0, 0, "", "'.$_SESSION['origURL'].'")'); //в каждом таком запросе есть переменная bot(isbot)которая равняется 1, если добавляемый пользователь бот

    $SELECT_QUERY = $db->Query_recordless("SELECT `id` FROM `users` WHERE `email` = '$email' AND `isBOT` = 1");
    $NumRows_SELECT_QUERY = mysqli_num_rows($SELECT_QUERY);
    if ( !empty($NumRows_SELECT_QUERY) ) {
        $assoc_user_bot_id = mysqli_fetch_assoc($SELECT_QUERY);

        $INSERT_INTO_QUERY1 = $db->Query_recordless('INSERT INTO `users_daily_bonus` VALUES (NULL, "'.$assoc_user_bot_id['id'].'", 0, 0, 0, 0, 0, 1 )');
        $INSERT_INTO_QUERY2 = $db->Query_recordless('INSERT INTO `users_data` VALUES (NULL, "'.$assoc_user_bot_id['id'].'", 0, 0, 0, 0, 0, 0, 0, 0, "", 0, 1, 0)');
        $INSERT_INTO_QUERY3 = $db->Query_recordless('INSERT INTO `users_stats` VALUES (NULL, "'.$assoc_user_bot_id['id'].'", 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1)');
        $INSERT_INTO_QUERY4 = $db->Query_recordless('INSERT INTO `users_amount_mine` VALUES (NULL, "'.$assoc_user_bot_id['id'].'", 1, "Магматический", "рудник", "image_category1_element_2_stroke_3_box_2_center_panel.png", 10.00, 20.00, 2.00, 0.00, 60, 1, 0, 0, NOW(), NOW(), NOW(), 1)');

        $INSERT_INTO_QUERY5 = $db->Query('INSERT INTO `parametres_free_chance` VALUES (NULL, "'.$assoc_users['id'].'", 0, 0, 0, 0, 0)');

        @mysqli_free_result($INSERT_INTO_QUERY1);
        @mysqli_free_result($INSERT_INTO_QUERY2);
        @mysqli_free_result($INSERT_INTO_QUERY3);
        @mysqli_free_result($INSERT_INTO_QUERY4);
        @mysqli_free_result($INSERT_INTO_QUERY5);
    }
    else {
        echo '0.';
    }
    @mysqli_free_result($SELECT_QUERY);
    @mysqli_free_result($INSERT_INTO_QUERY0);
}


/*
написать цикл на количество итерацией, регулируемое переменной (в нашем случае переменная будет рандомным количеством в промежутке)
1. Генерация ника с проверкой на уникальность в бд
    алгоритм генерации: 
2. Добавление юзера в бд с пометкой "бот" (bot = 1)

*/

?>