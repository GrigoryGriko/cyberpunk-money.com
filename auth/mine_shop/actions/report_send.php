<?php
    usleep(50000);
    header("Content-Type: text/html; charset=utf-8");  // стандартная установка кодировки
?>
<?php
    if (!$_POST['id_surfing']) {
        message('Вы не выбрали задание для жалобы', false, false, 'info');
    }
    else {
        if (!$_POST['input_value']) {
            message('Опишите причину жалобы', false, false, 'info');
        }
        else {
            $_POST['input_value'] = (string) $_POST['input_value'];
            if (strlen($_POST['input_value']) > 180) {
                message('Опишите причину вкратце не более 180 символов', false, false, 'info');
            }
            else {
                $db->Query('INSERT INTO `report_user_addsurf_sites` VALUES (NULL, "'.$_POST['id_surfing'].'", "'.$_SESSION['id'].'", "'.$_POST['input_value'].'" , NOW())');

                mail("geologymoney@gmail.com", 'СЁРФИНГ жалоба', "".$_POST['input_value'].". ID задания: ".$_POST['id_surfing']." | ID юзера: ".$_SESSION['id']." - ".$_SESSION['email']."");

                message('Жалоба отправлена. После прочтения, мы учтём ваше обращение');
            }
        }
    }
?>