<?php
session_start();
header('Expires: Wed, 1 Jan 1997 00:00:00 GMT');
header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Pragma: no-cache');
header('Content-type: image/png');      //позволяет выводить изображение в браузере

$Symbol_direct = substr("'/\'", 1, 2);

$width_height = rand(30, 50);
$font1 = rand(12, 18);
$X1 = rand(15, 20);
$Y1 = rand(18, 38);

$font2 = rand(12, 18);
$X2 = rand(15, 20)+60;
$Y2 = rand(18, 38);

$font3 = rand(12, 18);
$X3 = rand(15, 20)+120;
$Y3 = rand(18, 38);

$font4 = rand(12, 18);
$X4 = rand(15, 20)+180;
$Y4 = rand(18, 38);

$font5 = rand(12, 18);
$X5 = rand(15, 20)+240;
$Y5 = rand(18, 38);

$angle_rotation = array(0, 90, 180, 270);
$key_a_r1 = array_rand($angle_rotation, 1);
$key_a_r2 = array_rand($angle_rotation, 1);
$key_a_r3 = array_rand($angle_rotation, 1);
$key_a_r4 = array_rand($angle_rotation, 1);
$key_a_r5 = array_rand($angle_rotation, 1);

$RED_rgb_fill = rand(145, 255);
$GREEN_rgb_fill = rand(170, 255);
$BLUE_rgb_fill = rand(20, 117);

$RED_rgb_symbol1 = rand(45, 125);
$GREEN_rgb_symbol1 = rand(45, 125);
$BLUE_rgb_symbol1 = rand(45, 125);

$RED_rgb_symbol2 = rand(45, 125);
$GREEN_rgb_symbol2 = rand(45, 125);
$BLUE_rgb_symbol2 = rand(45, 125);

$RED_rgb_symbol3 = rand(45, 125);
$GREEN_rgb_symbol3 = rand(45, 125);
$BLUE_rgb_symbol3 = rand(45, 125);

$RED_rgb_symbol4 = rand(45, 125);
$GREEN_rgb_symbol4 = rand(45, 125);
$BLUE_rgb_symbol4 = rand(45, 125);

$RED_rgb_symbol5 = rand(45, 125);
$GREEN_rgb_symbol5 = rand(45, 125);
$BLUE_rgb_symbol5 = rand(45, 125);



$count = array("up" => 0, "left" => 0, "down" => 0, "right" => 0);

function count_raise ($angle, $count_direct) {

    global $count, $angle_rotation, $key_a_r1, $key_a_r2, $key_a_r3, $key_a_r4, $key_a_r5;
    if ($angle_rotation[$key_a_r1] == $angle) { 
        $count[$count_direct] ++;
    }
    if ($angle_rotation[$key_a_r2] == $angle) { 
        $count[$count_direct] ++;
    }
    if ($angle_rotation[$key_a_r3] == $angle) {
        $count[$count_direct] ++;
    }
    if ($angle_rotation[$key_a_r4] == $angle) {  
        $count[$count_direct] ++;
    }
    if ($angle_rotation[$key_a_r5] == $angle) {
       $count[$count_direct] ++;
    }
    return $count[$count_direct];
}

count_raise (0, 'up');
count_raise (90, 'left');
count_raise (270, 'right');
count_raise (180, 'down');
    

$half_time_mix = ceil( time()/3600/12 );
$_SESSION['captcha1'] = hash('sha256', $count['up'].'g'.$half_time_mix);
$_SESSION['captcha2'] = hash('sha256', $count['left'].'g'.$half_time_mix);
$_SESSION['captcha3'] = hash('sha256', $count['down'].'g'.$half_time_mix);
$_SESSION['captcha4'] = hash('sha256', $count['right'].'g'.$half_time_mix);

$im = imagecreatetruecolor(280, 60);        //Создание изображения - фона с размерам
imagefilledrectangle($im, 0, 0, 280, 60, imagecolorallocate($im, $RED_rgb_fill, $GREEN_rgb_fill, $BLUE_rgb_fill));
imagettftext($im, $font1, $angle_rotation[$key_a_r1], $X1, $Y1, imagecolorallocate($im, $RED_rgb_symbol1, $GREEN_rgb_symbol1, $BLUE_rgb_symbol1), 'AstakhovSkin.ttf', $Symbol_direct);     //генерация картинки из текста, цвет шрифта-фрифт-выводимый текст
imagettftext($im, $font2, $angle_rotation[$key_a_r2], $X2, $Y2, imagecolorallocate($im, $RED_rgb_symbol2, $GREEN_rgb_symbol2, $BLUE_rgb_symbol2), 'AstakhovSkin.ttf', $Symbol_direct);
imagettftext($im, $font3, $angle_rotation[$key_a_r3], $X3, $Y3, imagecolorallocate($im, $RED_rgb_symbol3, $GREEN_rgb_symbol3, $BLUE_rgb_symbol3), 'AstakhovSkin.ttf', $Symbol_direct);
imagettftext($im, $font4, $angle_rotation[$key_a_r4], $X4, $Y4, imagecolorallocate($im, $RED_rgb_symbol4, $GREEN_rgb_symbol4, $BLUE_rgb_symbol4), 'AstakhovSkin.ttf', $Symbol_direct);
imagettftext($im, $font5, $angle_rotation[$key_a_r5], $X5, $Y5, imagecolorallocate($im, $RED_rgb_symbol5, $GREEN_rgb_symbol5, $BLUE_rgb_symbol5), 'AstakhovSkin.ttf', $Symbol_direct);

imagepng($im);
imagedestroy($im);

?>