<?php
usleep(250000);
/*
выбираем ботов, у которых реферал 1-го уровня по id - 1, ставить каждому другой бота, но не собственный.
прогоняем циклом while всех ботов, в цикле прогоняем остальных ботов, извлекая id, изменяем ref у бота на id случайного...
*/

$db->Query('SELECT `id` FROM `users` WHERE `ref` = 1 AND `isBOT` = 1');
$numrows_id_bot = $db->NumRows();
if ( !empty($numrows_id_bot) ) {
    while ( $assoc_id_bot = $db->FetchAssoc() ) {
        $SELECT_QUERY = $db->Query_recordless('SELECT `id` FROM `users` WHERE `id` != "'.$assoc_id_bot['id'].'" AND `isBOT` = 1');
        $numrows_id_bots_another = $db->NumRows();
        if ( !empty($numrows_id_bots_another) ) {
            while ( $row = $db->FetchAssoc() ) {
                $num = $row['id'];
                foreach ($row as $key => $value) {
                    $assoc_user_bot[$key][$num] = $value; // $value = $row[$key]
                }
            }
            $array_rand_keys = array_rand($assoc_user_bot['id'], 1);

            $UPDATE_QUERY = $db->Query_recordless('UPDATE `users` SET `ref` = "'.$assoc_user_bot['id'][$array_rand_keys].'" WHERE `id` = "'.$assoc_id_bot['id'].'"');

            @mysqli_free_result($UPDATE_QUERY);
        }
        @mysqli_free_result($SELECT_QUERY);
    }
}

?>