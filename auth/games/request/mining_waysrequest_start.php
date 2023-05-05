<?php
    header("Content-Type: text/html; charset=utf-8");  // стандартная установка кодировки
?>
<?php
$db->Query("SELECT * FROM `games_mining_creator_data_files` WHERE `name_array` = 'map'");
$NumRows = $db->NumRows();
if ( !empty($NumRows) ) {
    $assoc_game_data = $db->FetchAssoc();
}

$array_tiles_field = $assoc_game_data['array_data'];

$encoding_str = time().'g'.$_SESSION['id'].'g'.rand(1000000000, 9999999999);
$hash_link = hash('sha256', ''.$encoding_str.'');
$secret_link = "/mining_ways_(event)?i=".$hash_link."";

if ($_POST['user_id'] == $_SESSION['id'] ) {
    if ($_POST['sum_bet'] >= 0.01) {

        $array_field_tiles_str = base64_encode( serialize($array_tiles_field) );
        //$array2_field_tiles = unserialize( base64_decode( gzuncompress($str) ) );

        $db->Query('INSERT INTO `games_mining_creator` VALUES(NULL, "'.$_SESSION['id'].'", NOW(), "0000-00-00 00:00:00", "'.$_POST['sum_bet'].'", 0, 0, "'.$array_field_tiles_str.'", "", "", "", "", "", "", "", "", "'.$hash_link.'")');

        header('location: '.$secret_link.'');
    }
    else {
        MessageSend('Минимальная сумма ставки 0.01 руб.', '/mining_ways(start)');
    }
}
else {
    MessageSend('Доступ запрещён', '/mining_ways(start)');
}

?>