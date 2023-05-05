<?php
	usleep(50000);
echo '
	<div>
		<div class="container_system_active_ad">	
			<div class="block_ajax_e1s6b1cp"> 
	            <p id="p1">
	                Системы активной рекламы славятся множеством способов размещения рекламы, тут Вы можете разместить<br>
	                баннерную, контекстную рекламу на самом сайте, заказть рассылку писем ну и конечно же купить посещения в<br>
	                сёрфинге.
	            </p>
                <ul class="ul_banners_bae1s6b1cp">';

                    $link = array(0, 'http://socpublic.com/', 'https://profitcentr.com/', 'http://www.wmmail.ru/', 'http://www.seosprint.net/', 'https://seo-fast.ru/', 'http://www.web-ip.ru/', 'http://wmrfast.com/', 'https://vipip.ru/', 'http://bizoninvest.com', 'http://wellclix.net/');
                    for ($a = 1; $a <= 10; $a++) {
		            	echo '
                        <a href="'.$link[$a].'" target="_blank">
			                <li>
			                    <img src="../img/auth/partneract/system_active_ad/'.$a.'.gif" width="468" height="60">
			                </li>
                        </a>';
		            }
echo	                                               
	            '</ul>
	        </div>
		</div>


		<div class="container_tiser_network">	
			<div class="block_ajax_e1s6b1cp"> 
	            <p id="p1">
	                Тизерная сеть — это выгодный способ привлечения рефералов за счёт выкупа больших объёмов трафика с<br>
	                нужных Вам площадок. Такая реклама, в отличие от других способов, позиционируется как информационное,<br>
	                а не продвигающее объявление, поэтому она привлекает взгляд людей.
	            </p>
                <ul class="ul_banners_bae1s6b1cp">';

                    $link = array(0, 'http://surfearner.com/', 'https://teaser.bz/', 'https://p2p.bz/', 'https://payad.me/', 'https://u-matrix.org/', 'https://multibux.org/?i=3767');
                    for ($a = 1; $a <= 6; $a++) {

                    	if ($a == 1 or $a == 6) $format = 'jpg';
                    	else $format = 'gif';

                        if ($a != 6) {
    		            	echo '
                            <a href="'.$link[$a].'" target="_blank">
    			                <li>
    			                    <img src="../img/auth/partneract/tiser_network/'.$a.'.'.$format.'" width="468" height="60">
    			                </li>
                            </a>';
                        }
                        else {
                            echo '
                            <a href="'.$link[$a].'" target="_blank">
                                <li>
                                    <img src="https://multibux.org/download/468v2.gif" width="468" height="60">
                                </li>
                            </a>';
                        }
		            } 
echo	                                               
	            '</ul>
	        </div>
		</div>


		<div class="container_baner_ad">
			<div class="block_ajax_e1s6b1cp"> 
	            <p id="p1">
		            	Баннерная реклама используется давно, но несмотря на это она не потеряла актуальность, и способна<br>
		            	решать рекламные задачи, которые не под силу текстовым объявлениям. Баннеры привлекают внимание многих,<br>
		            	благодаря картинке и небольшому информативному тексту на ней.
	            </p>
	            <ul class="ul_banners_bae1s6b1cp">';

                    $link = array(0, 'https://linkslot.ru/', 'http://www.rotaban.ru/', 'https://ad-slot.ru/', 'https://liink.ru/');
                    for ($a = 1; $a <= 4; $a++) {
                    	
		            	echo '
                        <a href="'.$link[$a].'" target="_blank">
			                <li>
			                    <img src="../img/auth/partneract/baner_ad/'.$a.'.gif" width="468" height="60">
			                </li>
                        </a>';
		            } 
echo	                                               
	            '</ul>
	        </div>
		</div>


		<div class="container_contex_ad">
			<div class="block_ajax_e1s6b1cp"> 
	            <p id="p1">
	                Контекстная реклама привлекает заинтересованных пользователей. В данных проектах Вы можете разместить<br>
	            	текстовые объявления, которые будут показываться пользователям, когда они сами проявят интерес к проекту,<br>
		            а именно, когда будут делать запрос подобной тематики.
	            </p>
	            <ul class="ul_banners_bae1s6b1cp">';

	            $link = array(0, 'http://tak.ru/', 'https://www.people-group.su/', 'http://wmlink.ru/', 'https://linkslot.ru/');
	            for ($a = 1; $a <= 4; $a++) {

	            	if ($a == 2) $format = 'jpg';
                    else $format = 'gif';

	            	echo '
	            	<a href="'.$link[$a].'" target="_blank">
		                <li>
		                    <img src="../img/auth/partneract/contex_ad/'.$a.'.'.$format.'" width="468" height="60">
		                </li>
		            </a>';
	            } 
echo	                                               
	            '</ul>
	        </div>
		</div>
	</div>
	';
?>