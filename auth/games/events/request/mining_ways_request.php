<?php
    header("Content-Type: text/html; charset=utf-8");  // стандартная установка кодировки
?>
<?php
if ($_POST['set_tile_f']) {
    $_POST['tile_choose_to_set'];
    $_POST['X'];
    $_POST['Y'];

    message($_POST['tile_choose_to_set'].'_'.$_POST['X'].'-'.$_POST['Y']);
}
?>