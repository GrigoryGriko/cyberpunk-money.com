<?php
session_start();
    header('Expires: Wed, 1 Jan 1997 00:00:00 GMT');
    header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
    header('Cache-Control: no-store, no-cache, must-revalidate');
    header('Pragma: no-cache');
    header('Content-type: image/png');      //позволяет выводить изображение в браузере


    /*$yesnot = array('yes', 'not');    /*Если раскомментировать, то можно сделать проверку на количество с отрицанием количества направлений в конкретную сторону сторону (сколько НЕ ВПРАВО)*/
    /*$key_yn = array_rand($yesnot, 1);*/

    $key_yn = 'yes';
   /* if ($yesnot[$key_yn] == 'yes') {*/    
        $prefics_ru = '';
        $prefics_en = '';/*
    }
    else {
        $prefics_ru = 'НЕ';
        $prefics_en = 'NOT';
    }*/

    $direction = array(0, 90, 180, 270);
    $key_d = array_rand($direction, 1);
   
    switch ($direction[$key_d]) {
        case 0:
            $text_direct_ru = 'ВВЕРХ';
            $text_direct_en = 'numbers OF ARROWS TO UP';
            break;
        case 90:
            $text_direct_ru = 'ВЛЕВО';
            $text_direct_en = 'numbers OF ARROWS TO LEFT';
            break;
        case 180:
            $text_direct_ru = 'ВНИЗ';
            $text_direct_en = 'numbers OF ARROWS TO DOWN';
            break;
        case 270:
            $text_direct_ru = 'ВПРАВО';
            $text_direct_en = 'numbers OF ARROWS TO RIGHT';
            break;
        default:
            $text_direct_ru = 'ОШИБКА';
            $text_direct_en = 'СООБЩИТЕ В ТЕХПОДДЕРЖКУ';
            break;
    }

    $Symbol_text1 = 'Сколько  стрелок  направлено ';
    $Symbol_text2 = ''.$prefics_ru.' '.$text_direct_ru.' ('.$prefics_en.' '.$text_direct_en.')?';

    $RED_rgb_fill = rand(145, 255);
    $GREEN_rgb_fill = rand(170, 255);
    $BLUE_rgb_fill = rand(20, 117);

    $RED_rgb_symbol = rand(20, 70);
    $GREEN_rgb_symbol = rand(20, 70);
    $BLUE_rgb_symbol = rand(20, 70);


    $half_time_mix = ceil( time()/3600/12 );
    $_SESSION['captcha_tonse'] = hash('sha256', $key_yn.'g'.$half_time_mix); /* вместо $key_yn - $yesnot[$key_yn]*/
    $_SESSION['captcha_rid'] = hash('sha256', $direction[$key_d].'g'.$half_time_mix);

    $im = imagecreatetruecolor(280, 65);        //Создание изображения - фона с размерам
    imagefilledrectangle($im, 0, 0, 280, 65, imagecolorallocate($im, $RED_rgb_fill, $GREEN_rgb_fill, $BLUE_rgb_fill));
    imagettftext($im, 12, 0, 15, 25, imagecolorallocate($im, $RED_rgb_symbol, $GREEN_rgb_symbol, $BLUE_rgb_symbol), 'HFF Low Sun.ttf', $Symbol_text1);     //генерация картинки из текста, цвет шрифта-фрифт-выводимый текст
    imagettftext($im, 8, 0, 10, 40, imagecolorallocate($im, $RED_rgb_symbol, $GREEN_rgb_symbol, $BLUE_rgb_symbol), 'HFF Low Sun.ttf', $Symbol_text2);

    imagepng($im);
    imagedestroy($im);

?>



