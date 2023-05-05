<?php
    header("Content-Type: text/html; charset=utf-8");  // стандартная установка кодировки
?>
<?php
top_auth('Партнёрская программа', 'auth/style/partneractstyle');
?>

<script type='text/javascript'>
    function change_content(id) {
        if (id == 1) {
            $.get("ajax/ajax_partneract", function(data) {
                data = $(data);
                $("#ajax_content_e1s6b1cp").html( $(".container_system_active_ad", data).html() );
            });
        }    
        else if (id == 2) {
            $.get("ajax/ajax_partneract", function(data) {
                data = $(data);
                $("#ajax_content_e1s6b1cp").html( $(".container_tiser_network", data).html() );
            });
        }
         else if (id == 3) {
            $.get("ajax/ajax_partneract", function(data) {
                data = $(data);
                $("#ajax_content_e1s6b1cp").html( $(".container_baner_ad", data).html() );
            });
        }
         else if (id == 4) {
            $.get("ajax/ajax_partneract", function(data) {
                data = $(data);
                $("#ajax_content_e1s6b1cp").html( $(".container_contex_ad", data).html() );  
            });
        }
        else {
            $("#ajax_content_e1s6b1cp").html('FatalScriptsError');
        }
    };
</script>

<?php

$link_http_host = stristr($_SERVER['HTTP_REFERER'], '/', true ).'//'.$_SERVER['HTTP_HOST'];


$db->Query("SELECT COUNT(`id`) AS `amount` FROM `users` WHERE `ref` = $_SESSION[id]");
$row_users = $db->NumRows();
if ( !empty($row_users) ) {
    $as_count_ref = $db->FetchAssoc();
}

$db->Query("SELECT COUNT(`id`) AS `amount` FROM `users` WHERE `ref_lvl_2` = $_SESSION[id]");
$row_users = $db->NumRows();
if ( !empty($row_users) ) {
    $as_count_ref_lvl_2 = $db->FetchAssoc();
}

$db->Query("SELECT * FROM `users_stats` WHERE `uid` = $_SESSION[id]");
$row_users = $db->NumRows();
if ( !empty($row_users) ) {
	$as_us = $db->FetchAssoc();

	$time_in_project = intval( (time() - strtotime($as_u['date_reg'])) / 3600 / 24 );		//intval - возвращает целое число (без округления)

	echo '

		<div class="box_strokeafter_2_center_panel">
			<div class="box_1_center_panel">
				<nav class="nav_stroke_3_box_1_center_panel">
					<ul class="ul_elementgradient_1_stroke_3_box_1_center_panel">
						<li>
							<div class="elementgradient_1_stroke_3_box_1_center_panel">
								<div class="text-1-elementgradient_1_stroke_3_box_1_center_panel">
									кол-во рефералов
								</div>
								<div class="text-2-elementgradient_1_stroke_3_box_1_center_panel">
									'.($as_count_ref['amount'] + $as_count_ref_lvl_2['amount']).' <span>чел.</span>
								</div>
							</div>
						</li>
						<li>
							<div class="elementgradient_2_stroke_3_box_1_center_panel">
								<div class="text-1-elementgradient_2_stroke_3_box_1_center_panel">
									доход с рефералов
								</div>
								<div class="text-2-elementgradient_2_stroke_3_box_1_center_panel">
									'.($as_us['money_earn_refs_1'] + $as_us['money_earn_refs_2']).' <span>₽</span>
								</div>
							</div>
						</li>
						<li>
							<div class="elementgradient_3_stroke_3_box_1_center_panel">
								<div class="text-1-elementgradient_3_stroke_3_box_1_center_panel">
								</div>
								<div class="text-2-elementgradient_3_stroke_3_box_1_center_panel">
									Статистика <span></span>
								</div>
							</div>
						</li>								
					</ul>
				</nav>

				<div class="element_1_stroke_4_box_1_center_panel">
					<div class="general_left_right_block_e1s4b1cp">
						<div class="left_block_e1s4b1cp">
							<h3>Реферальные ссылки:</h3>
							<p>
								Реф. ссылка 1: <span>'.$link_http_host.'/?g='.$_SESSION['login'].'</span><br>
								Реф. ссылка 1: <span>'.$link_http_host.'/?g='.$_SESSION['id'].'</span>
							</p>
						</div>
						<div class="right_block_e1s4b1cp">
							<h3>Реф. статистика:</h3>
							<p>
								- Кол-во рефералов (1 ур.): <span>'.$as_count_ref['amount'].' чел.</span><br>
								- Кол-во рефералов (2 ур.): <span>'.$as_count_ref_lvl_2['amount'].' чел.</span><br>
								- Общий доход с рефералов: <span>'.($as_us['money_earn_refs_1'] + $as_us['money_earn_refs_1']).' ₽</span><br>
								- Доход c рефералов (1 ур.): <span>'.$as_us['money_earn_refs_1'].'₽</span><br>
								- Доход c рефералов (2 ур.): <span>'.$as_us['money_earn_refs_2'].'₽</span>
							</p>
						</div>
					</div>
				</div>

				<div class="element_1_stroke_5_box_1_center_panel">
                    <div class="block_e1s5b1cp"> 
                        <h3>Описание партнёрской системы</h3>
                        <p id="p1">
                            В партнёрской пограмме может учавствовать любой пользователь проекта. Программа предусматривает ряд<br>
                            вознаграждений за определенные действия Ваших рефералов, а также рефералов 2 уровня. Рефералы - пользователи,<br>
                            которые зарегистрировались на проекте после перехода по Вашей реферальной ссылке. Рефералами 2 уровня считаются<br>
                            пользователи, которых пригласили в проект Ваши рефералы 1 уровня.
                        </p>

                        <h3>Вознаграждения</h3>
                        <p id="p2">
                            - 7% от суммы пополнения реферала 1 уровня<br>
                            - 3% от суммы пополнения реферала 2 уровня<br>
                            - 0.005 руб. с каждого просмотра в "Сёрфинге сайтов"<br>
                            - 5% от суммы пополнения рекламного баланса
                        </p>
                        <h4>Вознаграждения выплачиваются на баланс для вывода!</h4>
                    </div>
				</div>

                <div class="element_1_stroke_6_box_1_center_panel">
                    <div class="block_e1s6b1cp"> 
                        <h3>Где приглашать рефералов?</h3>
                        <p id="p1">
                            Каждый рефовод сам выбирает как и где ему брать рефералов. Кто-то использует баннерные сети для приглашения
                            рефералов, а кто-то закупает просмотры на Системах Активной Рекламы. Даже банальное размещение своей реф. ссылки
                            на странице в социальной сети, или в подписи на форуме может принести Вам активных рефералов, благодаря которым
                            Вы сможете получить вознаграждение от системы.
                        </p>

                        <table class="table_e1s6b1cp">
                            <tr>
                                <td id="1" class="current_tab" onclick="change_style(id); change_content(id);">Система Активной Рекламы</td>
                                <td id="2" class="other_tab" onclick="change_style(id); change_content(id);">Тизерные сети</td>
                                <td id="3" class="other_tab" onclick="change_style(id); change_content(id);">Баннерная реклама</td>
                                <td id="4" class="other_tab" onclick="change_style(id); change_content(id);">Контестная реклама</td>
                                <td id="5" style="cursor: default;"></td>
                            </tr>
                        </table>
                    </div>

                    <div id="ajax_content_e1s6b1cp">';

                        echo '
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
                               '
                                    </ul>
                            </div>
                        ';
echo
                    '</div>  

                </div>
				
			</div>
		</div>

        <script>    
            function change_style(id) {
                for (var i = 1; i <= 4; i++) {
                    if (id == i) {
                        document.getElementById(i).className = "current_tab";
                    } else {
                        document.getElementById(i).className = "other_tab";
                    }
                }
            } 

        </script>
	';

	bottom_auth();
}
else {
	exit(header('Location: /'));
}
?>