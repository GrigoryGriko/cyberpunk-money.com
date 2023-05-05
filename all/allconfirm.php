<?php
    header("Content-Type: text/html; charset=utf-8");  // стандартная установка кодировки
?>
<?php

if ($_GET['code']) {
    $db->Query("SELECT `id`, `uid` FROM `confirm_email_account` WHERE `code` = '$_GET[code]'");
    $numrows = $db->NumRows();
    if ( !empty($numrows) ) {
        $fetch_assoc = $db->FetchAssoc();

        $db->Query("UPDATE `users` SET `isCONFIRM_email` = 1 WHERE `id` = '$fetch_assoc[uid]'");

        $db->Query("DELETE FROM `confirm_email_account` WHERE `code` = '$_GET[code]'");

        echo '<p>Ваш аккаунт успешно подтвержден. <a href="/my_cabinet">Перейти в личный кабинет</a>';

    }          
    else {
        echo 'Ссылка недействительна/ <a href="/my_cabinet">Перейти в личный кабинет</a>';
    } 
}
if ($_GET['codereset']) {
    $db->Query("SELECT `id`, `uid` FROM `confirm_reset_payment_password` WHERE `code` = '$_GET[codereset]'");
    $numrows = $db->NumRows();
    if ( !empty($numrows) ) {
        $fetch_assoc = $db->FetchAssoc();

        $db->Query("UPDATE `users` SET `payment_password` = '' WHERE `id` = '$fetch_assoc[uid]'");

        $db->Query("DELETE FROM `confirm_reset_payment_password` WHERE `code` = '$_GET[codereset]'");

        echo '<p>Ваш платежный пароль успешно сброшен. <a href="/my_cabinet">Перейти в личный кабинет</a>';

    }          
    else {
        echo 'Ссылка недействительна/ <a href="/my_cabinet">Перейти в личный кабинет</a>';
    }
}
else {
    header('location: /setting_account'); 
}