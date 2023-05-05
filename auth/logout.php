<?php
    header("Content-Type: text/html; charset=utf-8");  // стандартная установка кодировки
?>
<?php

if ($_COOKIE['user'] or $_COOKIE['userm']) {
    setcookie('userm', '', strtotime('-30 days'), '/');
    setcookie('user', '', strtotime('-30 days'), '/');
    unset($_COOKIE['userm']);
    unset($_COOKIE['user']);
}

session_destroy();
header('location: /');

?>