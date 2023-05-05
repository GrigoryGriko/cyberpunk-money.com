<?php

if (!$_SESSION['USER_LOGIN_IN']) {
	$title = 'Главная';
	$style = 'style';

	echo '<!DOCTYPE html>
	<html>
	<head>
	<meta charset="UTF-8">
	<title>'.$title.'</title>
	<link rel="stylesheet" href="/'.$style.'.css">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
	<script src="https://code.jquery.com/jquery-1.12.4.js" integrity="sha256-Qw82+bXyGq6MydymqBxNPYTaUXXq7c8v3CwiYwLLNXU=" crossorigin="anonymous"></script>
    <script src="javascript/script.js"></script>';

echo "
    <script type='text/javascript'>
        $(document).ready(function() {

            var background_size = $('#container_1').css('background-size');
            console.log(background_size);	//лог
            background_size = background_size.split('px');
            console.log(background_size[0]);	//лог
            var background_size_height = background_size[0] / 1280 * 710;
            console.log(background_size_height);	//лог
            background_size_height = background_size_height+'px';
            console.log(background_size_height);	//лог
			
			var h1 = $('#container_1').css('height');
			console.log(h1);	//лог
            $('#container_1').css({
                'height': background_size_height
            });
			var h2 = $('#container_1').css('height');
			console.log(h2);	//лог

/*Из логов следует, что параметр height меняться не хочет [решено]*/

/*Код ниже нужно переписать*/


            window.onresize = function() {      /*узнать на сколько пикселей в высоту картинка фона и задатать эту высоту блоку*/
	
				var background_size = $('#container_1').css('background-size');
	            background_size = background_size.split('px');
	            var background_size_height = (background_size[0] / 1280 * 710);

                $('#container_1').css({
                    '-webkit-transition': 'all 1s ease',
                    'background-size': background_size_height,
                    'height': background_size_height        
                });
            				
				var h = $('#container_1').css('height');
                console.log(background_size_height+'-'+background_size+'-'+h);
            }
            
            console.log(background_size_height+'-'+background_size[0]);
        });
    </script>
";

echo '
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
					<nav class="nav_header_1">
						<ul class="ul_center_header_1">
							<li><a href="/home">Главная</a></li>
							<li><a href="/about">О проекте</a></li>
							<li><a href="/stats">Статистика</a></li>
							<li><a href="/guaranteed">Гарантии</a></li>
							<li><a href="/contest">Конкурсы</a></li>
							<li><a href="/feedback">Отзывы</a></li>		
							<li><a href="/help">Помощь</a></li>
						</ul>
						<ul class="ul_right_header_1">
							<li>
								<a href="/register"><div id="register_button"><img src="../img/home/register_button.png" width="171" height="52">
								<span>Регистрация</span></a></div>
							</li>
							<li>
								<a href="/login"><div id="login_button"><img src="../img/home/login_button.png" width="187" height="52">
								<span>Вход в аккаунт</span></a></div>
							</li>
						</ul>
					</nav>
				</div>
				<div class="img_stroke_1_container_1">
					<img src="../img/home/img_stroke_1_container_1.png" width="702" height="115" alt="GEOLOGY MONEY">
				</div>
				<div class="text_stroke_2_container_1">
					Экономический симулятор
				</div>
				<div class="text_stroke_3_container_1">
					<span>с выводом реальных денег</span>
				</div>
				<div class="text_stroke_4_container_1">
					<a href="/register"><div id="create_account_button"><img src="../img/home/create_account_button.png" width="253" height="67">
					<span>Создать аккаунт</span></a></div>
				</div>
				<div class="text_stroke_5_container_1">
					Непрерывно работаем:
				</div>
				<nav class="nav_counter_container_1">
					<ul class="ul_counter_container_1">
						<li>
							<div id="element_1_counter_container_1">123
								<span>Дней</span>
							</div>
						</li>
						<li>
							<div id="element_2_counter_container_1">456
								<span>Часов</span>
							</div>
						</li>
						<li>
							<div id="element_3_counter_container_1">789
								<span>Минут</span>
							</div>
						</li>
						<li>
							<div id="element_4_counter_container_1">099
								<span>Секунд</span>
							</div>
						</li>				
					</ul>
				</nav>
				<div class="division_stroke_6_container_1">
					<img src="../img/home/division_container_1.png" width="1232" height="48">	
				</div>
			</div>
			<div id="container_2">
				<nav class="nav_stats_container_2">
					<ul class="ul_stats_container_2">
						<li>
							<div id="element_1_stats_container_2">
								<div class="icon_element_1_stats_container_2">
									<img src="../img/home/icon_element_1_stats_container_2.png" width="140" height="100">
								</div>							
								<div class="division_element_stats_container_2">
									<img src="../img/home/division_element_stats_container_2.png" width="264" height="58">
								</div>
								<div class="part_element_stats_container_2">
								</div>
								<div class="text_1_element_1_stats_container_2">
									214 758 <span>чел.</span>
								</div>
								<div class="text_2_element_1_stats_container_2">
									Всего участников
								</div>
							</div>
						</li>
						<li>
							<div id="element_2_stats_container_2">
								<div class="icon_element_2_stats_container_2">
									<img src="../img/home/icon_element_2_stats_container_2.png" width="140" height="100">
								</div>							
								<div class="division_element_stats_container_2">
									<img src="../img/home/division_element_stats_container_2.png" width="264" height="58">
								</div>							
								<div class="part_element_stats_container_2">
								</div>
								<div class="text_1_element_2_stats_container_2">
									3014750 <span>руб.</span>
								</div>
								<div class="text_2_element_2_stats_container_2">
									Сумма пополнений
								</div>						
							</div>
						</li>
						<li>
							<div id="element_3_stats_container_2">
								<div class="icon_element_3_stats_container_2">
									<img src="../img/home/icon_element_3_stats_container_2.png" width="140" height="100">
								</div>								
								<div class="division_element_stats_container_2">
									<img src="../img/home/division_element_stats_container_2.png" width="264" height="58">
								</div>							
								<div class="part_element_stats_container_2">
								</div>
								<div class="text_1_element_3_stats_container_2">
									800423 <span>руб.</span>
								</div>
								<div class="text_2_element_3_stats_container_2">
									Заработано участниками
								</div>								
							</div>
						</li>
						<li>
							<div id="element_3_stats_container_2">
								<div class="icon_element_4_stats_container_2">
									<img src="../img/home/icon_element_4_stats_container_2.png" width="140" height="100">
								</div>															
								<div class="division_element_stats_container_2">
									<img src="../img/home/division_element_stats_container_2.png" width="264" height="58">
								</div>							
								<div class="part_element_stats_container_2">
								</div>
								<div class="text_1_element_4_stats_container_2">
									67 <span>день</span>
								</div>
								<div class="text_2_element_4_stats_container_2">
									Время работы
								</div>							
							</div>
						</li>				
					</ul>
				</nav>

				<div class="description_stroke_2_container_2">
					<div class="element_1_stroke_2_container_2">
						Наша команда презентует для вас новый обворожительный проект
						<br>GeologyMoney. Покупайте рудники, собирайте минералы и
						<br>получайте реальные деньги от продажи образцов!
					</div>
					<div class="element_2_stroke_2_container_2">
						<img src="../img/home/element_2_stroke_2_container_2.png" width="16" height="28">
					</div>
					<div class="element_3_stroke_2_container_2">
						geologymoney.com
					</div>
					<br>
					<div class="element_4_stroke_2_container_2">
						описание нашего проекта
					</div>					
				</div>
				<div class="image_sideleft_container_2">
					<img src="../img/home/image_sideleft_container_2.png" width="171" height="304">
				</div>
				<nav class="nav_stroke_3_container_2">
					<ul class="ul_stroke_3_container_2">
						<li>
							<div id="element_1_stroke_3_container_2">
								<div class="image_element_1_stroke_3_container_2">
									<img src="../img/home/icon_element_1_stroke_3_container_2.png" width="124" height="122">
								</div>
								<div class="text_1_element_1_stroke_3_container_2">
										Минеральные рудники
								</div>
								<div class="text_2_element_1_stroke_3_container_2">
										На выбор в проекте есть 4
										<br>вида рудников для освоения
										<br>на Вашем месторождении.
								</div>
								<a href="/register" id="register_element_1_button">
									Купить рудник
								</a>
							<div>
						</li>
						<li>
							<div id="element_2_stroke_3_container_2">
								<div class="image_element_2_stroke_3_container_2">
									<img src="../img/home/icon_element_2_stroke_3_container_2.png" width="125" height="122">
								</div>
								<div class="text_1_element_2_stroke_3_container_2">
										Развитие месторождения
								</div>
								<div class="text_2_element_2_stroke_3_container_2">
										Повышайте уровень своих
										<br>рудников на месторождении и
										<br>увеличивайте их добычу.
								</div>
								<a href="/register" id="register_element_2_button">
									Повысить уровень
								</a>
							<div>
						</li>
						<li>
							<div id="element_3_stroke_3_container_2">
								<div class="image_element_3_stroke_3_container_2">
									<img src="../img/home/icon_element_3_stroke_3_container_2.png" width="124" height="122">
								</div>
								<div class="text_1_element_3_stroke_3_container_2">
										Добыча минералов
								</div>
								<div class="text_2_element_3_stroke_3_container_2">
										Добывайте минералы и продавайте
										<br>их на системном рынке за
										<br>реальные деньги!
								</div>
								<a href="/register" id="register_element_3_button">
									Продать минералы
								</a>
							<div>
						</li>
						<li>
							<div id="element_4_stroke_3_container_2">
								<div class="image_element_4_stroke_3_container_2">
									<img src="../img/home/icon_element_4_stroke_3_container_2.png" width="125" height="122">
								</div>
								<div class="text_1_element_4_stroke_3_container_2">
										Вывод заработка
								</div>
								<div class="text_2_element_4_stroke_3_container_2">
										Заработок с продажи образцов
										<br>выводите любым удобным для
										<br>Вас способом!
								</div>
								<a href="/register" id="register_element_4_button">
									Вывести заработок
								</a>
							<div>
						</li>
					</ul>
				</nav>
			</div>
			<div id="container_3">
				<div class="image_sideleft_container_3">
					<img src="../img/home/image_sideleft_container_3.png" width="146" height="138">
				</div>
				<div class="image_sideright_container_3">
					<img src="../img/home/image_sideright_container_3.png" width="103" height="153">
				</div>
					<div class="element_3_container_3">
						
						<div class="wrapping_element_12_container_3">
							<div class="box_element_2_container_3">
								<div class="element_1_container_3">
									рудник «магматический» в подарок
								</div>
								<div class="element_2_container_3">
									получите <span>подарок</span>
								</div>
							</div>
						</div>

						<img src="../img/home/gift_element_3_container_3.png" width="136" height="125">

						<div class="wrapping_element_3_container_3">
							<div class="element_4_container_3">
								Для максимально комфортного старта в geologymoney мы
								<br>дарим всем новым участникам Рудник «МАГМАТИЧЕСКИЙ» в 
								<br>подарок. Начните знакомство с проектом в полном обьеме!
							</div>
						</div>
					</div>
				
				<div class="division_stroke_2_container_3">
					<img src="../img/home/division_container_1.png" width="1232" height="48">	
				</div>				
			</div>
			<div id="container_4">
				<div class="box_stroke_1_container_4">
					<div class="element_1_stroke_1_container_4">
						<img src="../img/home/element_1_stroke_1_container_4.png" width="21" height="38">
					</div>
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
											Уникальный скрипт
										</div>
										<div class="elementborder_3_ul_1_li_1_container_4">
											<img src="../img/home/elementborder_3_ul_1_container_4.png" width="201" height="2">
										</div>
										<div class="elementtext_3_ul_1_li_1_container_4">
											Мы используем уникальный скрипт<br>
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

					<img src="../img/home/elementimage_2_stroke_2_container_4.png" width="331" height="525">

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
											маркетингу geologymoney обещает стать<br>
											настоящим долгожителем
										</div> 
									</div>			
								</li>
							</ul>	
						</div>
					</div>
				</div>
			</div>
			<div id="container_5">
				<div class="element_2_container_5">
					
					<div class="wrapping_element_1_container_5">
						<div class="element_1_container_5">
							<div class="strokelogo_1_element_1_container_5"> 
								<img src="../img/home/img_logo_container_5.png" width="226" height="62">
							</div>

							<div class="strokeimage_2_element_1_container_5"> 
								экономическая игра
							</div>

							<div class="stroketext_3_element_1_container_5">
								<img src="../img/home/strokeimage_3_element_1_container_5.png" width="30" height="2">
							</div>
							
							<div class="stroketext_4_element_1_container_5">
								Экономический симулятор минеральных<br>
								рудников с возможностью вывода денег.
							</div>

							<div class="strokeimagetext_5_element_1_container_5">
								<img src="../img/home/strokeimagetext_5_element_1_container_5.png" width="9" height="13">
								<span><b>47 Rue Basse,</b> Paris, 5v 9000 Lille,<br> 
								France</span>
							</div>
							<div class="strokeimagetext_6_element_1_container_5">
								<img src="../img/home/strokeimagetext_6_element_1_container_5.png" width="15" height="12">
								<span>support@geologymoney.com</span>
							</div>
						</div>
					</div>

						<div class="sroketext_1_element_2_container_5">
							новости <b>проекта</b>
						</div>

						<div class="srokeimage_2_element_2_container_5">
							<img src="../img/home/strokeimage_3_element_1_container_5.png" width="30" height="2">
						</div>

						<div class="sroke_3_element_2_container_5">
						
						</div>

					<div class="wrapping_element_2_container_5">
						<div class="element_3_container_5">
							<div class="sroketext_1_element_3_container_5">
								наши <b>адреса</b>
							</div>

							<div class="srokeimage_2_element_3_container_5">
								<img src="../img/home/strokeimage_3_element_1_container_5.png" width="30" height="2">
							</div>
							<div class="srokeimagetext_4_element_3_container_5">
								<img src="../img/home/strokeimagetext_4_element_3_container_5.png" width="12" height="12">
								<span>geologymoney.com</span>
							</div>
						</div>
					</div>

				</div>
				<div class="division_stroke_5_container_5">
					<img src="../img/home/division_container_1.png" width="1232" height="48">	
				</div>
			</div>
			<footer id="footer_1">
				<div class="element_12_footer_1">
				
					<div class="elementtext_1_footer_1">
						Copyrights ©2018 <b>geologymoney.com</b> All rights reserved.
					</div>

					<nav class="elementnav_2_footer_1">
						<ul class="ul_elementnav_2_footer_1">
							<li><a href="/home">Главная</a></li>
							<li><a href="/about">О проекте</a></li>
							<li><a href="/stats">Статистика</a></li>	
							<li><a href="/help">Помощь</a></li>
							<li><a href="/rules">Правила</a></li>
						</ul>
					</nav>

				</div>
			</footer>
		</div>
		</body>
	</html>
	';
}
else if($_SESSION['USER_LOGIN_IN']) {
	
	top_auth('Главная', 'style');
	bottom_auth();
}

else {
	exit('Непредвиденная ошибка');
}

?>


		
	


