<?php

/*if (!$_SESSION['USER_LOGIN_IN']) {*/
	$title = 'Главная';
	$style = 'style';

    $db->Query("SELECT COUNT(`id`) AS `amount_reg` FROM `users` WHERE `id` != 1"); //
    $numrows_date_reg = $db->NumRows();
    if ( !empty($numrows_date_reg) ) {
       $as_count_total = $db->FetchAssoc();
    }
    else {
        $as_count_total['amount_reg'] = 0;   //всего пользователей
    }

    
    $db->Query("SELECT SUM(`money_payin`) AS `sum_payin` FROM `history_money_payin` WHERE `uid` != 1"); //
    $numrows_sum_payin = $db->NumRows();
	$as_money_payin = $db->FetchAssoc();
    if ($as_money_payin['sum_payin'] == NULL) {
        $as_money_payin['sum_payin'] = 0;   //Сумма пополнений выплат
    }


    $db->Query("SELECT SUM(`money_withdrawn`) AS `sum_payout` FROM `history_money_payout` WHERE `uid` != 1"); //
    $numrows_sum_payout = $db->NumRows();
	$as_money_sum_payout = $db->FetchAssoc();
    if ($as_money_sum_payout['sum_payout'] == NULL) {
        $as_money_sum_payout['sum_payout'] = 0;   //Сумма выплат
    }

	echo '<!DOCTYPE html>
	<html>
	<head>
	<meta charset="UTF-8">
	<meta name="description" content="Экономический симулятор с выводом реальных денег!">
	<meta name="Keywords" content="geologymoney, игры с выводом денег, игры с выводом денег,, экономический симулятор, заработок, заработок в интернете, хайп, хайпы, well-money, mmgp.ru, долгосрочный хайп, инвестиции"> 
	
	<meta property="og:image" content="/img/bigfav.png" />
	<meta property="og:image:secure_url" content="/img/bigfav.png" />
	<meta property="og:image:type" content="image/png" />
	<meta property="og:image:width" content="100px" />
	<meta property="og:image:height" content="100px" />
	<meta property="og:image:alt" content="GeologyMoney.com - экономический симулятор" />
	

	<title>'.$title.'</title>
	<link rel="stylesheet" href="/'.$style.'.css">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
	<script data-ad-client="ca-pub-4624626706857484" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
	<script src="../javascript/jquery-1.12.4.js"</script>
    <script src="javascript/script.js"></script>';

/*<script src="https://code.jquery.com/jquery-1.12.4.js" integrity="sha256-Qw82+bXyGq6MydymqBxNPYTaUXXq7c8v3CwiYwLLNXU=" crossorigin="anonymous"></script>*/

echo "
    <script type='text/javascript'>

		function ajax_time_project() {
			$.get('ajax/ajax_home', function(data) {	//функция получает данные data с файла по директории
			data = $(data);
			$('#passed_time_ajax_container').html( $('#passed_time', data).html() );



			});
		}

        $(document).ready(function() {

	            var background_size = $('#container_1').css('background-size');
	            background_size = background_size.split('px');
	            var background_size_height = background_size[0] / 1280 * 710;	/*background_size[0] левая часть строки, обрезанной по символу функцией split()*/
	            background_size_height = background_size_height+'px';
				
	            $('#container_1').css({
	                'height': background_size_height
	            });

/*Из логов следует, что параметр height меняться не хочет [решено]*/

/*Код ниже нужно переписать*/


            window.onresize = function() {      /*узнать на сколько пикселей в высоту картинка фона и задатать эту высоту блоку*/
	
	            var background_size = $('#container_1').css('background-size');
	            background_size = background_size.split('px');
	            var background_size_height = background_size[0] / 1280 * 710;	/*background_size[0] левая часть строки, обрезанной по символу функцией split()*/
	            background_size_height = background_size_height+'px';
				
	            $('#container_1').css({
	                'height': background_size_height
	            });
            }

            setInterval(function() {
				ajax_time_project();
			}, 60000);
        });

        $(document).on('mouseover', '.element_1_2_3_4_stats_container_2', function () {
        	$(this).css('box-shadow', '0 0 20px -1px rgba(0, 0, 0, 0.18)');
        });
        $(document).on('mouseout', '.element_1_2_3_4_stats_container_2', function () {
        	$(this).css('box-shadow', '0 0 8px 0px rgba(0, 0, 0, 0.12)');
        });


       	$(document).on('mouseenter', '.ul_center_header_1 li', function () {
        	$(this).find('.img_round').css({
        		'opacity': '1',
        		'margin-top': '10px'
        	});
        });
        $(document).on('mouseleave', '.ul_center_header_1 li', function () {
        	var img_round = $(this).find('.img_round');
        	img_round.css('opacity', '0');
                img_round.css('margin-top', '18px');
        });


        $(document).on('mouseenter', '.ul_stats_container_2 li', function () {
        	$(this).find('.line').css({
        		'opacity': '1',
        		'left': '102px'
        	});
        });
        $(document).on('mouseleave', '.ul_stats_container_2 li', function () {
        	var line = $(this).find('.line');
        	line.css('opacity', '0');
            line.css('left', '0px');
        });
    </script>
";

echo '
	<link rel="icon" href="/img/favicon.png" type="image/png">
	<link rel="shortcut icon" href="/img/favicon.png" type="image/png">

	</head>
		<body>
		<div class="wrapper">
			<div id="container_1">
				<div class="header_1">
					<div class="logo_header_1">
						<a href="/home">
							<div class="img_logo_header_1">
								<img src="../img/home/img_logo_header_1.png" width="151" height="44" alt="Geology money">
							</div>
						</a>
						<span class="text_logo_header_1">
							экономическая игра
						</span>
					</div>
						<ul class="ul_center_header_1">
							<li>
								<a class="a_menu" href="/home">Главная</a>
								<div class="img_round"></div>
							</li>
							<li>
								<a class="a_menu" href="/about">О проекте</a>
								<div class="img_round"></div>
							</li>
							<li>
								<a class="a_menu" href="/prostats">Статистика</a>
								<div class="img_round"></div>
							</li>
							<li>
								<a class="a_menu" href="/guaranteed">Гарантии</a>
								<div class="img_round"></div>
							</li>
							<li id="element_menu_contest">
								<a class="a_menu" href="/contest">Конкурсы</a>
								<div class="img_round"></div>
							</li>
							<li>
								<a class="a_menu" href="/feedback">Отзывы</a>
								<div class="img_round"></div>
							</li>		
							<li>
								<a class="a_menu" href="/help">Помощь</a>
								<div class="img_round"></div>
							</li>
						</ul>';
if (!$_SESSION['USER_LOGIN_IN']) {
	echo '
						<ul class="ul_right_header_1">
							<li>
								<a href="/register"><div id="register_button"><img src="../img/home/register_button.png" width="171" height="52">
								<span>Регистрация</span></a></div>
							</li>
							<li class="login_aa">
								<a href="/login"><div id="login_button"><img src="../img/home/login_button.png" width="187" height="52">
								<span>Вход в аккаунт</span></a></div>
							</li>
						</ul>';
}
else {
	echo '
						<ul class="ul_right_header_1_my_cabinet">
							<li>
								<a href="my_cabinet"><div id="account_button"><img src="../img/home/login_button.png" width="187" height="52">
								<span>мой кабинет</span></a></div>
							</li>
						</ul>';
}
echo '
				</div>

				<div class="jacket_container_1">
					<div class="img_stroke_1_container_1">
						<img src="../img/home/img_stroke_1_container_1.png" alt="Cyberpunk MONEY">
					</div>
					<!-- <div class="text_stroke_2_container_1">
						Экономический симулятор
					</div> -->
					<div class="text_stroke_3_container_1">
                        экономический симулятор
						<span>С выводом реальных денег</span>
					</div>
					<div class="text_stroke_4_container_1">';

if (!$_SESSION['USER_LOGIN_IN']) {
	echo '
						<a href="/register"><div id="create_account_button"><img src="../img/home/create_account_button.png" width="253" height="67">
						<span>Создать аккаунт</span></a></div>';
}
else {
	echo '
						<a href="/my_cabinet"><div id="go_account"><img src="../img/home/create_account_button.png" width="253" height="67">
						<span>Перейти в аккаунт</span></a></div>';
}

/*__________счетчик прошедшего со старта времени______VVVVVVVVV---*/

$date_start = strtotime("2021-06-05 12:00:00");	//условно старт проекта (еще переменная в ajax_home)
$passed_time = time() - $date_start;

$count_days = $passed_time / 60 / 60 / 24;
$count_hours = ( $count_days - floor($count_days) ) * 24;
$count_minutes = ( $count_hours - floor($count_hours) ) * 60;
$count_seconds = ( $count_minutes - floor($count_minutes) ) * 60;

/*__________счетчик прошедшего со старта времени______AAAAAAAAA---*/

echo '
					</div>';

echo 
					'<div class="text_stroke_5_container_1">
						Непрерывно работаем:
					</div>
					<nav class="nav_counter_container_1">
						<ul id="passed_time_ajax_container" class="ul_counter_container_1">
							<li>
								<div id="element_1_counter_container_1">'.floor($count_days).'
									<span>Дней</span>
								</div>
							</li>
							<li>
								<div id="element_2_counter_container_1">'.floor($count_hours).'
									<span>Часов</span>
								</div>
							</li>
							<li>
								<div id="element_3_counter_container_1">'.floor($count_minutes).'
									<span>Минут</span>
								</div>
							</li>
							<li>';
echo '
								<div id="element_4_counter_container_1">';
echo '
                                    <img src="/img/home/clock.png" width="54px" height="53px">';


/*echo '                              
                                    '.round($count_seconds).'
									<span>Секунд</span>';*/
echo ' 
								</div>
                              
							</li>				
						</ul>
					</nav>';
/*echo '
			<div class="text_stroke_5_container_1">
				на данный момент:
			</div>

			<nav class="nav_counter_container_1">
				<ul id="passed_time_ajax_container" class="ul_counter_container_1">
					<li>
						<div id="element_1_counter_container_test">
							Тестовый период проекта
						</div>
					</li>
				</ul>
			</nav>
';*/

echo '
				</div>

				<div class="centerer_for_division_stroke_6_container_1">
					<div class="division_stroke_6_container_1">
						<img src="../img/home/division_container_1.png" width="264" height="27">	
					</div>
				</div>
			</div>
			<div id="container_2">
				<nav class="nav_stats_container_2">
					<ul class="ul_stats_container_2">
						<li>
							<div class="element_1_2_3_4_stats_container_2">
								<div class="icon_element_1_stats_container_2">
									<img src="../img/home/icon_element_1_stats_container_2.png" width="140" height="100">
								</div>							
								<div class="division_element_stats_container_2">
									<img src="../img/home/division_element_stats_container_2.png" width="264" height="58">
								</div>
								<div class="part_element_stats_container_2">
								</div>
								<div class="text_1_element_1_stats_container_2">
									'.$as_count_total['amount_reg'].' <span>чел.</span>
								</div>
								<div class="text_2_element_1_stats_container_2">
									Всего участников
								</div>

								<div class="line"></div>
							</div>
						</li>
						<li>
							<div class="element_1_2_3_4_stats_container_2">
								<div class="icon_element_2_stats_container_2">
									<img src="../img/home/icon_element_2_stats_container_2.png" width="140" height="100">
								</div>							
								<div class="division_element_stats_container_2">
									<img src="../img/home/division_element_stats_container_2.png" width="264" height="58">
								</div>							
								<div class="part_element_stats_container_2">
								</div>
								<div class="text_1_element_2_stats_container_2">
									'.$as_money_payin['sum_payin'].' <span>руб.</span>
								</div>
								<div class="text_2_element_2_stats_container_2">
									Сумма пополнений
								</div>

								<div class="line"></div>						
							</div>
						</li>
						<li>
							<div class="element_1_2_3_4_stats_container_2">
								<div class="icon_element_3_stats_container_2">
									<img src="../img/home/icon_element_3_stats_container_2.png" width="140" height="100">
								</div>								
								<div class="division_element_stats_container_2">
									<img src="../img/home/division_element_stats_container_2.png" width="264" height="58">
								</div>							
								<div class="part_element_stats_container_2">
								</div>
								<div class="text_1_element_3_stats_container_2">
									'.$as_money_sum_payout['sum_payout'].' <span>руб.</span>
								</div>
								<div class="text_2_element_3_stats_container_2">
									Заработано участниками
								</div>

								<div class="line"></div>								
							</div>
						</li>
						<li>
							<div class="element_1_2_3_4_stats_container_2">
								<div class="icon_element_4_stats_container_2">
									<img src="../img/home/icon_element_4_stats_container_2.png" width="140" height="100">
								</div>															
								<div class="division_element_stats_container_2">
									<img src="../img/home/division_element_stats_container_2.png" width="264" height="58">
								</div>							
								<div class="part_element_stats_container_2">
								</div>
								<div class="text_1_element_4_stats_container_2">
									'.floor($count_days).' <span>день</span>
								
								</div>
								<div class="text_2_element_4_stats_container_2">
									Время работы
								</div>

								<div class="line"></div>							
							</div>
						</li>				
					</ul>
				</nav>

				<div class="description_stroke_2_container_2">
                    <!--<img src="https://geologymoney.com/img/GMWell-468x60.gif">-->
					<div class="element_1_stroke_2_container_2">
						Наша команда презентует для вас новый обворожительный проект
						<br>Cyberpunk-Money. Покупайте персонажей, собирайте артефакты и
						<br>получайте реальные деньги от продажи!
					</div>
					<div class="element_2_stroke_2_container_2">
						<!-- <img src="../img/home/element_2_stroke_2_container_2.png" width="16" height="28"> -->
					</div>
					<div class="element_3_stroke_2_container_2">
						cyberpunk-money.com
					</div>
					<br>
					<div class="element_4_stroke_2_container_2">
						описание нашего проекта
					</div>					
				</div>

				<!-- <div class="container_native_a">
					<p class="decript">Мы сотрудничаем с двумя проектами</p>
                    <div class="banners">
    	                <div class="container_datatable_part2">
    	                    <a href="https://castlecrashers.ru"><img src="https://castlecrashers.ru/assets/img/468x60.gif"></a>
    	                </div>
                        <div class="container_datatable_part2">
                            <a href="https://covid-profit.com"><img src="https://covid-profit.com/b/468.gif"></a>
                        </div>
                    </div>
	            </div> -->

				<nav class="nav_stroke_3_container_2">
					<ul class="ul_stroke_3_container_2">
						<li>
							<div id="element_1_stroke_3_container_2">
								<div class="image_element_1_stroke_3_container_2">
									<img src="../img/home/icon_element_1_stroke_3_container_2.png" width="124">
								</div>
								<div class="text_1_element_1_stroke_3_container_2">
										Персонажи
								</div>
								<div class="text_2_element_1_stroke_3_container_2">
										На выбор в проекте есть 
										<br>4 персонажа для прокачки
										<br>в Вашем Найт-Сити.
								</div>';
if (!$_SESSION['USER_LOGIN_IN']) {
	echo '
								<a href="/register" id="register_element_1_button">
									Нанять персонажа
								</a>';
}
else {
	echo '
								<a href="/my_field" id="register_element_1_button">
									Нанять персонажа
								</a>';
}
echo '
							<div>
						</li>
						<li>
							<div id="element_2_stroke_3_container_2">
								<div class="image_element_2_stroke_3_container_2">
									<img src="../img/home/icon_element_2_stroke_3_container_2.png" width="124">
								</div>
								<div class="text_1_element_2_stroke_3_container_2">
										Прокачка персонажей
								</div>
								<div class="text_2_element_2_stroke_3_container_2">
										Повышайте уровень своих
										<br>персонажей в Найт-Сити и
										<br>увеличивайте их эффективность.
								</div>';

if (!$_SESSION['USER_LOGIN_IN']) {
	echo '								
								<a href="/register" id="register_element_2_button">
									Повысить уровень
								</a>';
}
else {
	echo '								
								<a href="/my_field" id="register_element_2_button">
									Повысить уровень
								</a>';
}

echo '			
							<div>
						</li>
						<li>
							<div id="element_3_stroke_3_container_2">
								<div class="image_element_3_stroke_3_container_2">
									<img src="../img/home/icon_element_3_stroke_3_container_2.png" width="124">
								</div>
								<div class="text_1_element_3_stroke_3_container_2">
										Добыча артефактов
								</div>
								<div class="text_2_element_3_stroke_3_container_2">
										Добывайте артефакты и продавайте
										<br>их на системном рынке за
										<br>реальные деньги!
								</div>';

if (!$_SESSION['USER_LOGIN_IN']) {
	echo '	
								<a href="/register" id="register_element_3_button">
									Продать артефакты
								</a>';
}
else {
	echo '	
								<a href="/market" id="register_element_3_button">
									Продать артефакты
								</a>';
}

echo '					
							<div>
						</li>
						<li>
							<div id="element_4_stroke_3_container_2">
								<div class="image_element_4_stroke_3_container_2">
									<img src="../img/home/icon_element_4_stroke_3_container_2.png" width="124">
								</div>
								<div class="text_1_element_4_stroke_3_container_2">
										Вывод заработка
								</div>
								<div class="text_2_element_4_stroke_3_container_2">
										Заработок с продажи образцов
										<br>выводите любым удобным для
										<br>Вас способом!
								</div>';

if (!$_SESSION['USER_LOGIN_IN']) {
	echo '
								<a href="/register" id="register_element_4_button">
									Вывести заработок
								</a>';
}
else {
	echo '
								<a href="/pay/payout_money" id="register_element_4_button">
									Вывести заработок
								</a>';
}

echo '								
							<div>
						</li>
					</ul>
				</nav>					
			</div>
			<div id="container_3">
				<div class="image_sideleft_container_3">
					<img src="../img/home/image_sideleft_container_3.png" height="150">
				</div>
				<div class="image_sideright_container_3">
					<img src="../img/home/image_sideright_container_3.png" height="175">
				</div>
				<div class="element_3_container_3">
					
					<div class="wrapping_element_12_container_3">
						<div class="box_element_2_container_3">
							<div class="element_1_container_3">
								персонажа «Джэки Уэллес» в подарок
							</div>
							<div class="element_2_container_3">
								получите <span>подарок</span>
							</div>
						</div>
					</div>

					<img src="../img/home/gift_element_3_container_3.png" width="136">

					<div class="wrapping_element_3_container_3">
						<div class="element_4_container_3">
							Для максимально комфортного старта в cyberpunk-money мы
							<br>дарим всем новым участникам персонаж "ДЖЭКИ УЭЛЛЕС" в 
							<br>подарок. Начните знакомство с проектом в полном обьеме!
						</div>
					</div>
				</div>
				<div class="centerer_for_division_stroke_6_container_1">
					<div class="division_stroke_2_container_3">
						<img src="../img/home/division_container_1.png" width="264" height="27">	
					</div>
				</div>			
			</div>
			<div id="container_4">
				<div class="box_stroke_1_container_4">
					<!-- <div class="element_1_stroke_1_container_4">
						<img src="../img/home/element_1_stroke_1_container_4.png" width="21" height="38">
					</div> -->
					<div class="text_1_stroke_1_container_4">
						наши <span>преимущества</span>
					</div>
					<div class="text_2_stroke_1_container_4">
						основные качества
					</div>				
				</div>


				<div class="element_2_container_4">
					
					<div class="wrapping_element_1_container_4">
						<div class="element_1_container_4">
							<ul class="ul_1_container_4">
								<li>
									<div class="box_element_123_ul_1_li_1_container_4">
										<div class="elementtext_1_ul_1_li_1_container_4">
											Собственный скрипт
										</div>
										<div class="elementborder_3_ul_1_li_1_container_4">
											<img src="../img/home/elementborder_3_ul_1_container_4.png" width="201" height="2">
										</div>
										<div class="elementtext_3_ul_1_li_1_container_4">
											Мы используем скрипт,<br>
											собственной разработки, сайт создавался<br>
											с нуля лично нами
										</div> 
									</div>
									<div class="elementimage_4_ul_1_li_1_container_4">
										<img src="../img/home/elementimage_4_ul_1_li_1_container_4.png" width="60" height="60">
									</div>			
								</li>

								<li>
									<div class="box_element_123_ul_1_li_1_container_4">
										<div class="elementtext_1_ul_1_li_1_container_4">
											Открытая статистика
										</div>
										<div class="elementborder_3_ul_1_li_1_container_4">
											<img src="../img/home/elementborder_3_ul_1_container_4.png" width="201" height="2">
										</div>
										<div class="elementtext_3_ul_1_li_1_container_4">
											Статистика проекта всегда открыта и<br>
											доступна для просмотра любому<br>
											пользователю проекта
										</div> 
									</div>
									<div class="elementimage_4_ul_1_li_1_container_4">
										<img src="../img/home/elementimage_4_ul_1_li_2_container_4.png" width="60" height="60">
									</div>			
								</li>

								<li>
									<div class="box_element_123_ul_1_li_1_container_4">
										<div class="elementtext_1_ul_1_li_1_container_4">
											Регулярные конкурсы
										</div>
										<div class="elementborder_3_ul_1_li_1_container_4">
											<img src="../img/home/elementborder_3_ul_1_container_4.png" width="201" height="2">
										</div>
										<div class="elementtext_3_ul_1_li_1_container_4">
											Каждый месяц мы проводим конкурсы<br>
											активности, участие в которых может<br>
											принять любой участник проекта
										</div> 
									</div>
									<div class="elementimage_4_ul_1_li_1_container_4">
										<img src="../img/home/elementimage_4_ul_1_li_3_container_4.png" width="60" height="60">
									</div>			
								</li>
							</ul>	
						</div>
					</div>

					<img src="../img/home/elementimage_2_stroke_2_container_4.png" width="331">

					<div class="wrapping_element_2_container_4">
						<div class="element_3_container_4">
							<ul class="ul_2_container_4">
								<li>
									<div class="elementimage_4_ul_2_li_1_container_4">
										<img src="../img/home/elementimage_4_ul_2_li_1_container_4.png" width="60" height="60">
									</div>									
									<div class="box_element_123_ul_2_li_1_container_4">				
										<div class="elementtext_1_ul_2_li_1_container_4">
											Выделенный сервер
										</div>
										<div class="elementborder_3_ul_2_li_1_container_4">
											<img src="../img/home/elementborder_3_ul_2_container_4.png" width="201" height="2">
										</div>
										<div class="elementtext_3_ul_2_li_1_container_4">
											Проект размещен на выделенном,<br>
											защищенном сервере, что обеспечит<br>
											максимальную доступность
										</div> 
									</div>		
								</li>

								<li>
									<div class="elementimage_4_ul_2_li_1_container_4">
										<img src="../img/home/elementimage_4_ul_2_li_2_container_4.png" width="60" height="60">
									</div>								
									<div class="box_element_123_ul_2_li_1_container_4">					
										<div class="elementtext_1_ul_2_li_1_container_4">
											Мобильная версия
										</div>
										<div class="elementborder_3_ul_2_li_1_container_4">
											<img src="../img/home/elementborder_3_ul_2_container_4.png" width="201" height="2">
										</div>
										<div class="elementtext_3_ul_2_li_1_container_4">
											Сайт доступен с любых мобильных<br>
											устройств, и будет масштабироваться под<br>
											Ваше разрешение экрана
										</div> 
									</div>												
								</li>

								<li>
									<div class="elementimage_4_ul_2_li_1_container_4">
										<img src="../img/home/elementimage_4_ul_2_li_3_container_4.png" width="60" height="60">
									</div>									
									<div class="box_element_123_ul_2_li_1_container_4">					
										<div class="elementtext_1_ul_2_li_1_container_4">
											Плавный маркетинг
										</div>
										<div class="elementborder_3_ul_2_li_1_container_4">
											<img src="../img/home/elementborder_3_ul_2_container_4.png" width="201" height="2">
										</div>
										<div class="elementtext_3_ul_2_li_1_container_4">
											Благодаря плавному и продуманному<br>
											маркетингу cyberpunk-money обещает стать<br>
											настоящим долгожителем
										</div> 
									</div>			
								</li>
							</ul>	
						</div>
					</div>
				</div>
			</div>';

    bottom_guest_second();

    echo '
		</div>
		</body>
	</html>
	';
/*}
else if($_SESSION['USER_LOGIN_IN']) {
	
	top_auth('Главная', 'style');
	bottom_guest_second();
}

else {
	exit('Непредвиденная ошибка');
}*/

?>


		
	


