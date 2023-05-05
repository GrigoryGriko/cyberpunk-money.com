<?php
$number = 2;
session_start();
    header('Expires: Wed, 1 Jan 1997 00:00:00 GMT');
    header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
    header('Cache-Control: no-store, no-cache, must-revalidate');
    header('Pragma: no-cache');
    header('Content-type: image/gif');      //позволяет выводить изображение в браузере

    $Random = rand(10, 99);
    $Symbol_direct = substr("'/\'", 1, 2);

    $width_height = rand(30, 50);
    $font = rand(12, 18);
    $X = rand(0, 10);
    $Y = rand(10, 30);

    $X2 = rand(0, 10);
    $Y2 = rand(10, 30);
    $angle_rotation = array(0, 90, 180, 270);
    $key_a_r = array_rand($angle_rotation, 1);

    $RED_rgb_fill = rand(145, 255);
    $GREEN_rgb_fill = rand(170, 255);
    $BLUE_rgb_fill = rand(20, 117);

    $RED_rgb_symbol = rand(45, 125);
    $GREEN_rgb_symbol = rand(45, 125);
    $BLUE_rgb_symbol = rand(45, 125);

    $_SESSION['captcha'.$number.''] = hash('sha256', $angle_rotation[$key_a_r]);
    $im = imagecreatetruecolor($width_height, $width_height);        //Создание изображения - фона с размерам
    imagefilledrectangle($im, 0, 0, $width_height, $width_height, imagecolorallocate($im, $RED_rgb_fill, $GREEN_rgb_fill, $BLUE_rgb_fill));
    imagettftext($im, $font, 0, $X, $Y, imagecolorallocate($im, $RED_rgb_symbol, $GREEN_rgb_symbol, $BLUE_rgb_symbol), 'AstakhovSkin.ttf', $Symbol_direct);     //генерация картинки из текста, цвет шрифта-фрифт-выводимый текст
    imagettftext($im, $font, 0, $X2, $Y2, imagecolorallocate($im, 255, 255, 255), 'AstakhovSkin.ttf', '00'); 

    $rotate = imagerotate($im, $angle_rotation[$key_a_r], 0);
    imagepng($rotate);
    imagedestroy($rotate);

?>