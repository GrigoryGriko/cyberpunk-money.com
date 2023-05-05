<?php
usleep(50000);
    header("Content-Type: text/html; charset=utf-8");  // стандартная установка кодировки
?>
<?php
switch ($_POST['action_f']) {
    case "start_task":
        if (!$_POST['start_task_id']) {
            message('Критическая ошибка', false, false, 'error');
        }
        else {
            $db->Query("SELECT `enable`, `banned` FROM `user_addsurf_sites` WHERE `id` = '$_POST[start_task_id]' AND `uid` = '$_SESSION[id]'");
            $NumRows_u_a_s = $db->NumRows();
            if ( !empty($NumRows_u_a_s) ) {
                $assoc_u_a_s = $db->FetchAssoc();
                if ($assoc_u_a_s['banned'] == 0) {
                    if ($assoc_u_a_s['enable'] == 1) {
                        $change_enable = 0;
                    }
                    else {
                        $change_enable = 1;
                    }
                    $db->Query("UPDATE `user_addsurf_sites` SET `enable` = $change_enable WHERE `id` = '$_POST[start_task_id]' AND `uid` = '$_SESSION[id]'");
                }
                else {
                    message('Забаненное задание нельзя запустить', false, false, 'warning');
                }
            }
            else {
                message('Задания не существует', false, false, 'warning');    
            }
        }
        break;
    case "delete_task":
        if (!$_POST['delete_task_id']) {
            message('Критическая ошибка #dalatrro548', false, false, 'error');
        }
        else {
            $db->Query("SELECT `enable`, `banned` FROM `user_addsurf_sites` WHERE `id` = '$_POST[delete_task_id]' AND `uid` = '$_SESSION[id]'");
            $NumRows_u_a_s = $db->NumRows();
            if ( !empty($NumRows_u_a_s) ) {
                $assoc_u_a_s = $db->FetchAssoc();
                if ($assoc_u_a_s['banned'] == 0) {
                    $db->Query("DELETE FROM `user_addsurf_sites` WHERE `id` = '$_POST[delete_task_id]' AND `uid` = '$_SESSION[id]'");
                }
                else {
                    message('Забаненный сайт нельзя удалить', false, false, 'warning');
                }
            }
            else {
                message('Задания не существует', false, false, 'warning');    
            }
        }
        break;
    case "raise_task":
        if (!$_POST['raise_task_id']) {
            message('Критическая ошибка #rase77', false, false, 'error');
        }
        else {
            $cost_raise = 1;
            $db->Query("SELECT `enable`, `banned` FROM `user_addsurf_sites` WHERE `id` = '$_POST[raise_task_id]' AND `uid` = '$_SESSION[id]'");
            $NumRows_u_a_s = $db->NumRows();
            if ( !empty($NumRows_u_a_s) ) {
                $assoc_u_a_s = $db->FetchAssoc();
                if ($assoc_u_a_s['banned'] == 0) {
                    $db->Query("SELECT `balance_buy` FROM `users_data` WHERE `uid` = '$_SESSION[id]'");
                    $NumRows_u_d = $db->NumRows();
                    if ( !empty($NumRows_u_d) ) {
                        $assoc_u_d = $db->FetchAssoc();
                        if ($assoc_u_d['balance_buy'] >= 1) {
                            $db->Query("UPDATE `user_addsurf_sites` SET `time_add` = NOW() WHERE `id` = '$_POST[raise_task_id]'");
                            $db->Query("UPDATE `users_data` SET `balance_buy` = (`balance_buy` - $cost_raise) WHERE `uid` = '$_SESSION[id]'");
                            $db->Query("UPDATE `system_earn` SET `money_from_raise_task` = (`money_from_raise_task` + $cost_raise) WHERE `id` = 1");

                            message('Вы подняли задание в сёрфинге');
                        }
                        else {
                            message('У вас недостаточно средств', false, false, 'info');
                        }
                    }
                    else {
                        message('Вы хакер!', false, false, 'warning');    
                    }   
                }
                else {
                    message('Забаненный сайт нельзя поднять', false, false, 'warning');
                }
            }
            else {
                message('Задания не существует', false, false, 'warning');    
            }
        }
        break;

    default:
        message('Критическая ошибка#22', false, false, 'error');
        break;
}

?>