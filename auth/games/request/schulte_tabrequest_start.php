<?php
    header("Content-Type: text/html; charset=utf-8");  // стандартная установка кодировки
?>
<?php
$number = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 58, 59, 60, 61, 62, 63, 64, 65, 66, 67, 68, 69, 70, 71, 72, 73, 74, 75, 76, 77, 78, 79, 80, 81, 82, 83, 84, 85, 86, 87, 88, 89, 90);
shuffle($number);

$array_number = base64_encode( serialize($number) );

$encoding_str = time().'g2'.$_SESSION['id'].'g2'.rand(1000000000, 9999999999);
$hash_link = hash('sha256', ''.$encoding_str.'');
$secret_link = "/schulte_tab_event?i=".$hash_link."";

if ($_POST['user_id'] == $_SESSION['id'] ) {
    $_POST['sum_bet'] += 0;
    if ($_POST['sum_bet'] >= 0.01) {
        $db->Query("SELECT * FROM `users_data` WHERE `uid` = '$_SESSION[id]'");
        $NumRows = $db->NumRows();
        if ( !empty($NumRows) ) {
            $assoc_u_d = $db->FetchAssoc();
            if ($assoc_u_d['balance_game'] >= $_POST['sum_bet']) {
                $db->Query("UPDATE `users_data` SET `balance_game` = (`balance_game` - '$_POST[sum_bet]') WHERE `uid` = '$_SESSION[id]'");
                $db->Query('INSERT INTO `games_schulte_tab` VALUES(NULL, "'.$_SESSION['id'].'", 0, "0000-00-00 00:00:00", "0000-00-00 00:00:00", "0000-00-00 00:00:00", "0000-00-00 00:00:00", NOW(), 0, 0, "'.$_POST['sum_bet'].'", "'.$array_number.'", 0, 0, 0, 0, "'.$hash_link.'")');

                header('location: '.$secret_link.'');
            }
            else {
                MessageSend('Недостаточно средств', '/schulte_tab_start');    
            }
        }
        else {
            MessageSend('Вас не существует', '/schulte_tab_start');
        }
    }
    else {
        MessageSend('Минимальная сумма ставки 0.01 руб.', '/schulte_tab_start');
    }
}
else {
    MessageSend('Доступ запрещён', '/schulte_tab_start');
}

?>