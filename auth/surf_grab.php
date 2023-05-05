<?php
    header("Content-Type: text/html; charset=utf-8");  // стандартная установка кодировки
?>
<?php
$db->Query("SELECT `id` FROM `user_seen_surf_list` WHERE `uid` = '$_SESSION[id]' AND `uas_id` = '$_SESSION[it]'");
$NumRows_seen_surf = $db->NumRows();
if ( empty($NumRows_seen_surf) ) {   //если  в бд задания нет в просмотренных, то выводим его

    $db->Query("SELECT * FROM `user_addsurf_sites` WHERE `banned` = 0 AND `enable` = 1 AND `watch_stats` < `max_count_views` AND `id` = '$_SESSION[it]'");
    $NumRows_list = $db->NumRows();
    if ( !empty($NumRows_list) ) {
        $assoc_list = $db->FetchAssoc();

        $db->Query("SELECT `balance_advertising` FROM `users_data` WHERE `uid` = '$assoc_list[uid]'");
        $NumRows_balance_ad = $db->NumRows();
        if ( !empty($NumRows_balance_ad) ) {    
            $assoc_balance_ad = $db->FetchAssoc();     //делать запросом без записи
            if ($assoc_balance_ad['balance_advertising'] >= $assoc_list['cost_watch']) {            
                echo '
                    <!DOCTYPE html>
                    <html>
                    <head>
                    <meta charset="UTF-8">
                    <title>'.$assoc_list['name_link'].'</title>
                    <link rel="stylesheet" href="auth/style/surf_grabstyle.css">
                    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
                    <script src="javascript/jquery-1.12.4.js"></script>
                    <script src="javascript/script.js"></script>

                    <link rel="stylesheet" type"text/css" href="all/sweetalert2.css">
                    <script type="text/javascript" src="javascript/sweetalert2.js"></script>

                    <script type="text/javascript">
                   
                    $(document).ready(function() {
                        var window_status = "active";
                        var seconds = '.$assoc_list['time_watch'].';
                        
                        function timer() {
    
                            $("#timer").show();

                            $("#timer").html(
                                seconds
                            );
                            seconds -= 1;  


                            if (seconds <= 0) {
                                clearInterval(timerID);

                                $("#timer").hide();
                                $.get("ajax/ajax_surf_grab", function(data) {
                                    data = $(data);
                                    $("#footer").html( $("#container_captcha", data).html() );
                                });

                            }
                        }

                        var timerID = setInterval(timer, 1000);

                        window.onfocus = function () {
                            window_status = "active";

                            if (seconds > 0) {
                                timerID = setInterval(timer, 1000);

                                
                            }
                        };

                        window.onblur = function () {
                            clearInterval(timerID);
                            window_status = "inactive";
                        };
                    });
                    </script>
                    </head>
                    <body>';
                    ?>

                    <?php
                    echo '
                        <div class="wrapper">
                            <div class="container">
                                <div class="advertisingsite_container">';

                                /*require_once 'simple_html_dom.php';
                                $html = new simple_html_dom();
                                $html->load_file('http://stalker-x.ru');
                                $html->find('link', 0)->href = 'http://stalker-x.ru/css/my.css';
                                foreach($html->find('img') as $k=>$img) {
                                    $html->find('img',$k)->src = 'http://stalker-x.ru'.$img->src;
                                }
                                $html->save('stalker.html'); //Работает, осталось научиться извлекать ссылки файлов со стилями и использовать несколько файлов css, если он не один, также выводить контент в блок*/

                                

                                //chudo_parsing($assoc_list['url_site']);
                    echo '
                    <iFrame style="padding-bottom: 105px;" src="'.$assoc_list['url_site'].'" width="100%" height="100%" id="framesite" frameborder="0" scrolling="yes"></iFrame>
                                    <div id="footer">
                                        <div style="margin-right: 20px;" id="timer">

                                        </div>';


                    echo '                    
                                    </div>                    
                                </div>            
                    ';
                        

                    bottom_auth('not_footer');
            }
            else {
                exit('У создателя задания закончились деньги на балансе <a href="/surfsites">вернуться назад</a>');
            }
        }
        else {
            exit('Пользователь, который создал задание, не существует <a href="/surfsites">вернуться назад</a>');
        }
    }
    else {
        exit('Данного задания нет <a href="/surfsites">вернуться назад</a>');
    }
}
else {
    exit('Вы уже просмотрели данное задание <a href="/surfsites">вернуться назад</a>');
}
?>