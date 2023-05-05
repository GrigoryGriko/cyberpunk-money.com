<?php
    header("Content-Type: text/html; charset=utf-8");  // стандартная установка кодировки
?>
<?php
top_auth('Рекламные материалы', 'auth/style/admatstyle');
?>
<?php

$link_http_host = stristr($_SERVER['HTTP_REFERER'], '/', true ).'//'.$_SERVER['HTTP_HOST'];

echo '
    <div class="box_strokeafter_2_center_panel">
        <div class="box_1_center_panel">

            <div class="block_1">
                <div class="box">  
                    <div class="side_block">
                        <div class="part_1">
                            Реф. ссылка 1:&nbsp;
                        </div>
                        <div class="part_2">
                            '.$link_http_host.'/?g='.$_SESSION['login'].'
                        </div>
                    </div>

                    <div class="side_block">
                        <div class="part_1">
                            Реф. ссылка 2:&nbsp;
                        </div>
                        <div class="part_2">
                            '.$link_http_host.'/?g='.$_SESSION['id'].'
                        </div>
                    </div>
                </div>   
            </div>

    		<div class="block_2">
				<div class="table">
                    <div class="header_r">
                        ДИНАМИЧЕСКИЕ БАННЕРЫ, РАЗМЕР: 728X90
                    </div>
					<div class="middle_e">
                        <img src="../img/GMWell-728x90.gif" width="728" height="90">
                    </div>


                    <div class="bottom_m">
                        <div class="bottom_2">
                             <div class="element">
                                Ссылка на баннер:
                             </div>
                             <div class="element">
                                Размер баннера: '.round( (filesize('img/GMWell-728x90.gif') / 1024), 2).' кб
                             </div>
                        </div>

                        <div class="bottom_22">
                             <div class="element">
                                '.$link_http_host.'/img/GMWell-728x90.gif
                             </div>
                             <a href="/img/GMWell-728x90.gif" class="element_2" download>
                                <img src="../img/auth/admat/down.png" width="14" height="11">
                                скачать баннер
                             </a>
                        </div>
                    </div>


                    <div class="middle_e">
                        <img src="../img/GM-728x90.gif" width="728" height="90">
                    </div>

                    <div class="bottom_m">
                        <div class="bottom_2">
                             <div class="element">
                                Ссылка на баннер:
                             </div>
                             <div class="element">
                                Размер баннера: '.round( (filesize('img/GM-728x90.gif') / 1024), 2).' кб
                             </div>
                        </div>

                        <div class="bottom_22">
                             <div class="element">
                                '.$link_http_host.'/img/GM-728x90.gif
                             </div>
                             <a href="/img/GM-728x90.gif" class="element_2" download>
                                <img src="../img/auth/admat/down.png" width="14" height="11">
                                скачать баннер
                             </a>
                        </div>
                    </div>
                </div>

                <div class="table">
                    <div class="header_r">
                        ДИНАМИЧЕСКИЕ БАННЕРЫ, РАЗМЕР: 468X60
                    </div>

                    <div class="middle_e">
                        <img src="../img/GMWell-468x60.gif" width="468" height="60">
                    </div>

                    <div class="bottom_m">
                        <div class="bottom_2">
                             <div class="element">
                                Ссылка на баннер:
                             </div>
                             <div class="element">
                                Размер баннера: '.round( (filesize('img/GMWell-468x60.gif') / 1024), 2).' кб
                             </div>
                        </div>

                        <div class="bottom_22">
                             <div class="element">
                                '.$link_http_host.'/img/GMWell-468x60.gif
                             </div>
                             <a href="/img/GMWell-468x60.gif" class="element_2" download>
                                <img src="../img/auth/admat/down.png" width="14" height="11">
                                скачать баннер
                             </a>
                        </div>
                    </div>


                    <div class="middle_e">
                        <img src="../img/GM-468x60.gif" width="468" height="60">
                    </div>

                    <div class="bottom_m">
                        <div class="bottom_2">
                             <div class="element">
                                Ссылка на баннер:
                             </div>
                             <div class="element">
                                Размер баннера: '.round( (filesize('img/GM-468x60.gif') / 1024), 2).' кб
                             </div>
                        </div>

                        <div class="bottom_22">
                             <div class="element">
                                '.$link_http_host.'/img/GM-468x60.gif
                             </div>
                             <a href="/img/GM-468x60.gif" class="element_2" download>
                                <img src="../img/auth/admat/down.png" width="14" height="11">
                                скачать баннер
                             </a>
                        </div>
                    </div>
                </div>

                <div class="table">
                    <div class="header_r">
                        ДИНАМИЧЕСКИЕ БАННЕРЫ, РАЗМЕР: 200X300
                    </div>

                    <div class="middle_e">
                        <img src="../img/GMWell-200x300.gif" width="200" height="300">
                    </div>

                    <div class="bottom_m">
                        <div class="bottom_2">
                             <div class="element">
                                Ссылка на баннер:
                             </div>
                             <div class="element">
                                Размер баннера: '.round( (filesize('img/GMWell-200x300.gif') / 1024), 2).' кб
                             </div>
                        </div>

                        <div class="bottom_22">
                             <div class="element">
                                '.$link_http_host.'/img/GMWell-200x300.gif
                             </div>
                             <a href="/img/GMWell-200x300.gif" class="element_2" download>
                                <img src="../img/auth/admat/down.png" width="14" height="11">
                                скачать баннер
                             </a>
                        </div>
                    </div>


                    <div class="middle_e">
                        <img src="../img/GM-200x300.gif" width="200" height="300">
                    </div>

                    <div class="bottom_m">
                        <div class="bottom_2">
                             <div class="element">
                                Ссылка на баннер:
                             </div>
                             <div class="element">
                                Размер баннера: '.round( (filesize('img/GM-200x300.gif') / 1024), 2).' кб
                             </div>
                        </div>

                        <div class="bottom_22">
                             <div class="element">
                                '.$link_http_host.'/img/GM-200x300.gif
                             </div>
                             <a href="/img/GM-200x300.gif" class="element_2" download>
                                <img src="../img/auth/admat/down.png" width="14" height="11">
                                скачать баннер
                             </a>
                        </div>
                    </div>
                </div>
    		</div>
        </div>
    </div>';

    bottom_auth();

?>