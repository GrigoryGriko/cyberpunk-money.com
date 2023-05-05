<?php
    header("Content-Type: text/html; charset=utf-8");  // стандартная установка кодировки
?>
<?php
    if ($_SESSION['ADMIN_LOGIN_IN']) {
        $db->Query("SELECT * FROM `users` WHERE `id` != 1 AND `isBOT` = 0 ORDER BY `date_reg` DESC LIMIT 250"); //
        $numrows_users_real = $db->NumRows();
        if ( !empty($numrows_users_real) ) {
            $n = 0;
            echo '<a href="/">На главную</a><br>Сверху самые новые. <a href="/page_a">Перейти в статистику пользователей</a><br>';
            while( $as_users_real = $db->FetchAssoc() ) {
                $n += 1;
                echo $n.') '.$as_users_real['id'].'-'.$as_users_real['login'].'-'.$as_users_real['email'].'-'.$as_users_real['date_reg'].' | РЕФЕР- id:'.$as_users_real['ref'].'<br>';
            }
        }
    }
    else {
        exit(header('Location: /login_a'));
    }
?>