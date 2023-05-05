<?php
    usleep(5000);
header("Content-Type: text/html; charset=utf-8");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    function translit($str) {   //функция транслит - переводит кириллицу в латиницу
        $rus = array('А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я', 'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я');
        $lat = array('A', 'B', 'V', 'G', 'D', 'E', 'E', 'Gh', 'Z', 'I', 'Y', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'H', 'C', 'Ch', 'Sh', 'Sch', 'Y', 'Y', 'Y', 'E', 'Yu', 'Ya', 'a', 'b', 'v', 'g', 'd', 'e', 'e', 'gh', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'c', 'ch', 'sh', 'sch', 'y', 'y', 'y', 'e', 'yu', 'ya');
        return str_replace($rus, $lat, $str);
    }

    if ( 0 < $_FILES['file']['error'] ) {
        message('Ошибка системы: @'.$_FILES['file']['error'].'', false, false, 'error');
    }
    if ( mb_strlen ($_FILES['file']['name']) > 50) {

        $name_format = explode( '.', translit($_FILES['file']['name']) );
        
        $name_format[0] = substr($name_format[0], 0, 47).'___';
        $format = array_pop($name_format);

        $_FILES['file']['name'] = $name_format[0].'.'.$format;

        
    }

    $_FILES['file']['name'] = $_FILES['file']['name'];

    $filename_dir = 'FILESTORE/img/avatar/'.$_SESSION['id'].'_'.$_SESSION['login'].'/'.$_FILES['file']['name'].'';
    $folder_name = 'FILESTORE/img/avatar/'.$_SESSION['id'].'_'.$_SESSION['login'].'';
    
    if ( file_exists(!$folder_name) ) {
        mkdir('FILESTORE/img/avatar/'.$_SESSION['id'].'_'.$_SESSION['login'].'/', 0700); //создаем директорию для пользователя
    }
    if ( empty($_FILES['file']['name']) ) {

        message('Не выбран файл', false, false, 'info');

    }
    else if ( file_exists($filename_dir) ) {
        message('Данный файл уже загружен', false, false, 'info');
    }
    else {
        $path = 'FILESTORE/img/avatar/'.$_SESSION['id'].'_'.$_SESSION['login'].'/';
        $tmp_path = 'FILESTORE/temp/';
        // Массив допустимых значений типа файла
        $types = array('image/gif', 'image/png', 'image/jpeg');
        // Максимальный размер файла
        $size = 2048000;

        // Проверяем тип файла
        if (!in_array($_FILES['file']['type'], $types)) {
            message('Неверный тип файла', false, false, 'warning');
        }
        // Проверяем размер файла
        else if ($_FILES['file']['size'] > $size) {
            message('Слишком большой размер файла', false, false, 'warning');
        }
        // Функция изменения размера
        // Изменяет размер изображения в зависимости от type:
        // type = 1 - эскиз
        //  type = 2 - большое изображение
        // rotate - поворот на количество градусов (желательно использовать значение 90, 180, 270)
        // quality - качество изображения (по умолчанию 75%)

        else {

            function resize($file, $type = 1, $rotate = null, $quality = null) {
                global $filename_dir;
                global $tmp_path;
                global $db;

                // Ограничение по ширине в пикселях
                $max_size = 600;

                // Качество изображения по умолчанию
                if ($quality == null) {
                    $quality = 100; //100%
                }

                // Cоздаём исходное изображение на основе исходного файла
                if ($file['type'] == 'image/jpeg') {
                    $source = imagecreatefromjpeg($file['tmp_name']);
                }
                else if ($file['type'] == 'image/png') {
                    $source = imagecreatefrompng($file['tmp_name']);
                }
                else if ($file['type'] == 'image/gif') {
                    $source = imagecreatefromgif($file['tmp_name']);
                }
                else {
                    return false;
                }

                $src = $source;

                // Определяем ширину и высоту изображения
                $w_src = imagesx($src); 
                $h_src = imagesy($src);

                $w = $max_size;

                // Если ширина больше заданной
                if ($w_src > $max_size) {
                // Вычисление пропорций
                    $ratio = $w_src/$w;
                    $w_dest = round($w_src/$ratio);
                    $h_dest = round($h_src/$ratio);

                    // Создаём пустую картинку
                    $dest = imagecreatetruecolor($w_dest, $h_dest);

                    // Копируем старое изображение в новое с изменением параметров
                    imagecopyresampled($dest, $src, 0, 0, 0, 0, $w_dest, $h_dest, $w_src, $h_src);

                    // Вывод картинки и очистка памяти
                    
                    $file['name'] = iconv('UTF-8', 'cp1251', $_FILES['file']['name']);

                    imagejpeg($dest, $tmp_path . $file['name'], $quality);
                    imagedestroy($dest);
                    imagedestroy($src);

                    return $file['name'];
                }
                else {
                    // Вывод картинки и очистка памяти

                    $file['name'] = iconv('UTF-8', 'cp1251', $_FILES['file']['name']);

                    imagejpeg($src, $tmp_path . $file['name'], $quality);
                    imagedestroy($src);

                    return $file['name'];
                }
            }

            $name = resize($_FILES['file'], $_POST['file_type']);


            // Загрузка файла и вывод сообщения
            if (!@copy($tmp_path . $name, $path . $name)) {
                unlink($tmp_path . $name);
                message('Что-то пошло не так', 'warning');
               /*message('Что-то пошло не так '.$_FILES['file']['name'].'+'.$_POST['file_type'].'+'.$tmp_path.'+'.$name.'+'.$path.'', false, false, 'warning');*/
            }
            else {
                $UPDATE_QUERY = $db->Query_recordless("UPDATE `users_data` SET `name_image_avatar` = '$filename_dir' WHERE `uid` = '$_SESSION[id]'");
                @mysqli_free_result($UPDATE_QUERY);

                $UPDATE_QUERY = $db->Query_recordless("UPDATE `users` SET `upload_avatar` = 1 WHERE `id` = '$_SESSION[id]'");
                @mysqli_free_result($UPDATE_QUERY);


                $old_files = glob($folder_name.'/*');
                foreach($old_files as $old_file){ // iterate files
                    if( is_file($old_file) and $old_file != $filename_dir) {
                        unlink($old_file); // delete file
                    }
                }

                unlink($tmp_path . $name);

                message('Загрузка прошла успешно');
            }
            // Удаляем временный файл
            
            $old_files = glob($folder_name.'/*');
            foreach($old_files as $old_file){ // iterate files
                if( is_file($old_file) and $old_file != $filename_dir) {
                    unlink($old_file); // delete file
                }
            }

            unlink($tmp_path . $name);
        }
    }    
}
else {
    message('критическая ошибка #avatarus00x327, обратитесь в тех. поддержку', false, false, error);
}      

?>
