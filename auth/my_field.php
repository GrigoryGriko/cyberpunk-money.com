<?php
    header("Content-Type: text/html; charset=utf-8");  // стандартная установка кодировки
?>
<?php
top_auth('Мой Найт-Сити', 'auth/style/my_fieldstyle');
?>

<script type='text/javascript'>
	function ajax_my_field() {
		$.get("ajax/ajax_my_field", function(data) {	//функция получает данные data с файла по директории /ajax_my_field
			$('#ul_ajax-element_1_stroke_6_box_1_center_panel').html(data);

		});
	}

	$(document).ready(function start_height_css() {  //запускает код при загрузке страницы
		/*setInterval(function () {
			ajax_my_field();
		}, 1000);*/

		var start_height_css_container_e1s3b1cp = $(".container_e1s3b1cp").css("height");

		function drop_down(element, header_element, block_drop_down) {
			$(document).on("click", block_drop_down, function () {

				var height_css = $(element).css("height");
				
				if ( height_css == "0px") {
					$(this).find(".img_to_02").hide();
					$(this).find(".img_to_01").show();


					$(element).css("display", "block");
					$(element).css({"height": start_height_css_container_e1s3b1cp, "margin": "22px 0px 20px 0px"});

					$(header_element).css("border-bottom", "1px solid #E2E2E2");

				}
				else {
					$(this).find(".img_to_01").hide();
					$(this).find(".img_to_02").show();

					$(element).css({"height": "0px", "margin": "0px 0px 0px 0px"});

					
					$(header_element).css("border-bottom", "1px solid transparent");
					$(document).ready(function() {  //запускает код при загрузке страницы
						setTimeout(function () {
							$(element).css("display", "none");
						}, 300);
					});
				}
			});
		};
		drop_down(".container_e1s3b1cp", ".header_e1s3b1cp", ".drop_down");
		drop_down(".container_e1s4b1cp", ".header_e1s4b1cp", ".drop_down_2");
	});
</script>

<?php
/*[1]------Выводим с базы данных переменные объектов 1-го уровня, а также переменную income объекта 7-го уровня----------VVVVVVVVVV--------------------*/

/*mysql вывод конкретных данных*/

	$db->Query("SELECT * FROM `mine_in_shop` WHERE `level` = 1");
	$row_mine_in_shop_WHERE_level_1 = $db->NumRows(); //$NumRows подсчитывает число строк предыдущего запроса. Функция создана в файле _class.db.php

	if ( !empty($row_mine_in_shop_WHERE_level_1) ) {		
		while ( $row = $db->FetchAssoc() ) {
			$num = $row['category'];
			foreach ($row as $key => $value) {
				$assoc_mine_in_shop_WHERE_level_1[$key][$num] = $value; // $value = $row[$key]
			}
		}
	}

	$db->Query("SELECT `category`, `income` FROM `mine_in_shop` WHERE `level` = 7");
	$row_mine_in_shop_WHERE_level_7 = $db->NumRows();

	if ( !empty($row_mine_in_shop_WHERE_level_7) ) {
		while ($row = $db->FetchAssoc() ) {
			$num = $row['category'];
			$assoc_mine_in_shop_WHERE_level_7['income'][$num] = $row['income'];
		}
	}

/*mysql вывод конкретных данных*/
/*mysql подсчет строк*/

$db->Query("SELECT * FROM `mine_in_shop`");
	$row_mine_in_shop_ALL = $db->NumRows(); //$NumRows подсчитывает число строк предыдущего запроса. Функция создана в файле _class.db.php

	if ( !empty($row_mine_in_shop_ALL) ) {		
		while ( $row = $db->FetchAssoc() ) {
			$num = $row['category'];
			$count_level_mine_in_shop[$num] += 1; //подсчет количества уровней в каждой категории
			foreach ($row as $key => $value) {
				$assoc_mine_in_shop_ALL[$key][$num] = $value; // $value = $row[$key]
			}
		}
	}

/*mysql подсчет строк*/
/*[1]---------------------AAAAAAAAAAAAAAAAAA--------------------*/
function mine_category_in_shop($category) {
	global $assoc_mine_in_shop_WHERE_level_1;
	global $assoc_mine_in_shop_WHERE_level_7;
	global $count_level_mine_in_shop;
	echo '
		<div class="element_2_stroke_3_box_2_center_panel">
			<div class="stroketext_1-e2s3b2cp">
				'.$assoc_mine_in_shop_WHERE_level_1['first_name'][$category].' '.$assoc_mine_in_shop_WHERE_level_1['second_name'][$category].' ур. '.$assoc_mine_in_shop_WHERE_level_1['level'][$category].'
			</div>
				<img class="strokeimage-e2s3b2cp" src="../img/auth/my_field/'.$assoc_mine_in_shop_WHERE_level_1['image_name'][$category].'">
			
			<table class="tabletext_2-e2s3b2cp">
				<tr>
					<td>
						<div class="elemtext_1-st2e2s3b2cp">Стоимость: </div>
						<div class="elemtext_2-st2e2s3b2cp">'.round($assoc_mine_in_shop_WHERE_level_1['price'][$category], 0).' руб.</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="elemtext_1-st3e2s3b2cp">Доходность: </div>
						<div class="elemtext_2-st3e2s3b2cp">'.round($assoc_mine_in_shop_WHERE_level_1['income'][$category], 0).'%-'.round($assoc_mine_in_shop_WHERE_level_7['income'][$category], 0).'% / мес.</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="elemtext_1-st4e2s3b2cp">Кол-во уровней: </div>	
						<div class="elemtext_2-st4e2s3b2cp">'.$count_level_mine_in_shop[$category].' <span><a href="/leveltab">(таблица уровней)</a></span></div>
					</td>
				</tr>
			</table>
	';

	$input_id = 'id'.$assoc_mine_in_shop_WHERE_level_1['id'][$category];	//для отправки данных определенной категории..
	$value_id = $assoc_mine_in_shop_WHERE_level_1['id'][$category];

	$input_category = 'category'.$assoc_mine_in_shop_WHERE_level_1['category'][$category];
	$value_category = $assoc_mine_in_shop_WHERE_level_1['category'][$category];

	$post_action = 'minebuy'.$category;	//..для отправки данных определенной категории

	echo '

			<input type="hidden" id="'.$input_id.'" value="'.$value_id.'">
			<input type="hidden" id="'.$input_category.'" value="'.$value_category.'">
			<button class="button_mineybuy-e2s6b2cp" onclick="
			setTimeout(() => { post_query(\'mine_shop/actions/minebuy\', \''.$post_action.'\', \''.$input_id.'*+*'.$input_category.'\'); }, 250); 
			setTimeout(() => { ajax_index_top_auth(); }, 250);
			setTimeout(() => { ajax_my_field(); }, 250);
			">Купить персонажа ('.round($assoc_mine_in_shop_WHERE_level_1['price'][$category], 0).' руб.)</button>
	
		</div>		
	';
}

/*$N = 290.30;
print rtrim(rtrim($N, 0), '.');*//*Функция удаления лишних нулей после запятой*/
echo '
	<div class="box_strokeafter_2_center_panel">
		<div class="box_1_center_panel">
			<div class="element_1_stroke_3_box_1_center_panel">
				<div class="header_e1s3b1cp">
					<div class="drop_down">
						<img class="img_to_01" src="../img/auth/my_field/arrow_todown.png" width="16px" height="16px">
						<img class="img_to_02" style="display: none" src="../img/auth/my_field/arrow_toright.png" width="16px" height="16px">
						Описание игрового процесса
					</div>
				</div>
				<div class="box_container_e1s3b1cp">
					<div class="container_e1s3b1cp">
						<div class="p_1">
							<img class="left_img" src="../img/auth/my_field/field_icon.png" width="100px" height="100px">
							<p>Искуственный интеллект. Цифровые ипмланты. Автоматическое оружие с подствольными гранатомётами. У нас есть всё. Это современный город - Найт-Сити. Ниже отображаются все нанятые Вами персонажи, не забывайте их посещать это место и собирать добытые артефакты.</p>
						</div>
						<p class="p_2">
							Нанимайте персонажей, чтобы увеличить добычу артефактов, а так же свой заработок. Повышайте уровень персонажей, получайте больше добычи, а также дополнительные бонусы (<a href="/leveltab">Таблица уровней</a>).
						</p>
					</div>
				</div>
			</div>
			<div class="element_1_stroke_4_box_1_center_panel">
				<div class="header_e1s4b1cp">';


echo '
					<div class="drop_down_2">
						<img class="img_to_01" style="display: none"src="../img/auth/my_field/arrow_todown.png" width="16px" height="16px">
						<img class="img_to_02" src="../img/auth/my_field/arrow_toright.png" width="16px" height="16px">
						<p class="how_collect">Как собирать и продавать добытые артефакты</p>';

/*echo '
					<div id="linkslot_287997"><script src="https://linkslot.ru/bancode.php?id=287997" async></script></div>
';*/
echo '
					</div>';
/*echo '
					<div class="drop_down_2_01">
						<a href="/contest_game">ВНИМАНИЕ! Стартовал конкурс, (Участие от 10 руб.) ПОДРОБНЕЕ</a>
					</div>';*/

echo '
				</div>
				<div class="box_container_e1s4b1cp">
					<div class="container_e1s4b1cp">
						<div class="p_1">
							<img class="left_img_2" src="../img/auth/my_field/field_icon_02.png" width="100px" height="123px">
							<p>Для сбора добытых артефактов со всех персонажей сразу нажмите кнопку "собрать добычу". Если Вы хотите собрать артефакты от конкретного персонажа, нажмите "проверить" и соберите артефакты уже в разделе выбранного. Для того чтобы продать артефакты и получить прибыль, Вам необходимо перейти в раздел <a href="/market">Системный рынок</a>.</p>
						</div>
					</div>
				</div>
			</div>
			<div class="element_1_stroke_5_box_1_center_panel">
			<img src="../img/auth/my_field/icon_field.png" width="16px" height="16px">
				<p>Персонажи в моём Найт-Сити:</p>
			</div>
';
/*контейнер ajax-----VVVVVVVVVVVV-----*/

$db->Query("SELECT * FROM `users_amount_mine` WHERE `uid` = '$_SESSION[id]' ORDER BY `date_level_update` DESC, `date_buy` DESC");
$row_users_amount_mine = $db->NumRows();
if ( !empty($row_users_amount_mine) ) {
echo '
			<nav class="nav_element_1_stroke_6_box_1_center_panel">
				<ul id="ul_ajax-element_1_stroke_6_box_1_center_panel">';

	/*$as_u_a_m = $assoc_users_amount_mine--------обозначения*/
	while ( $as_u_a_m = $db->FetchAssoc() ) {	/*Отображение рудников, купленных пользователем*/

//система накопления минералов---VVVV---
		if ( ( time() - strtotime($as_u_a_m['date_collection']) ) >= 1) {	/*если текущее время минус время последнего сбора больше либо равно 1 секунде, то..*/
			$as_u_a_m['keep_minerals'] = ( (time() - strtotime($as_u_a_m['date_collection']) ) * ($as_u_a_m['rate_mining'] + $as_u_a_m['bonus']) ) / $as_u_a_m['rate_seconds']; 
			$UPDATE_QUERY = $db->Query_recordless("UPDATE `users_amount_mine` SET `keep_minerals` = '$as_u_a_m[keep_minerals]' WHERE `id` = '$as_u_a_m[id]' AND `uid` = '$_SESSION[id]'");
			/*..то текущее время минус время сбора умножаем на доходность в секунду (доходность в минуту делим на 60), получаем накопленное количество минералов*/
			@mysqli_free_result($UPDATE_QUERY); //очистка пямяти от запроса
		}
//система накопления минералов---AAAA---
		switch ($as_u_a_m['category']) {
			case 1:
				$image_mineral = 'tourmaline.png';
				break;
			case 2:
				$image_mineral = 'topaz.png';
				break;
			case 3:
				$image_mineral = 'emerald.png';
				break;
			case 4:
				$image_mineral = 'diamond.png';
				break;
			default:
				$image_mineral = '';
				break;

		}

		echo '
					<li>
						<div class="fromsql_element-e1s6b1cp">

							<div class="fromsql_stroketext_1-e1s6b1cp">
								<p><span>'.$as_u_a_m['second_name'].'</span> "'.$as_u_a_m['first_name'].'"<br><span>уровень '.$as_u_a_m['level'].'</span></p>
							</div>
								<img class="fromsql_strokeimage-e1s6b1cp" src="../img/auth/my_field/'.$as_u_a_m['image_name'].'">
							<button class="fromsql_stroketext_2-e1s6b1cp">
								<img src="../img/auth/my_field/'.$image_mineral.'" width="20px" height="20px">
								'.round( ($as_u_a_m['keep_minerals'] + $as_u_a_m['archive_keep_minerals']) , 0 ).'
							</button>
							<a href="/mine?id='.$as_u_a_m['id'].'">
								<button class="fromsql_stroketext_3-e1s6b1cp">
									<img src="../img/auth/my_field/check.png" width="16px" height="16px">
									Проверить
								</button>
							</a>';
		if ($as_u_a_m['level'] < 7) {
			echo '
						<a href="/mine?id='.$as_u_a_m['id'].'">
							<button class="fromsql_stroketext_4-e1s6b1cp_1">
								<img src="../img/auth/my_field/lvl_up.png" width="16px" height="16px">
								LVL UP
							</button>
						</a>';
		}
		else {
			echo '
						<button class="fromsql_stroketext_4-e1s6b1cp_2">
							LVL MAX
						</button>';
		}

		echo '
						</div>						
					</li>';
	}
	echo '	
				</ul>								
			</nav>

			<input type="hidden" id="confirm" value="1">
			<button class="button_stroke_7_box_1_center_panel" onclick="post_query(\'mine_shop/actions/mineralscollect\', \'mineralscollect\', \'confirm\'); ajax_my_field();">
				собрать всю добычу
			</button>';
}
else {
	echo '
			<nav class="nav_element_1_stroke_6_box_1_center_panel empty">
				В Вашем Найт-Сити нет персонажей
			</nav>';
}

/*контейнер ajax-----AAAAAAAAAAAA-----*/
echo '

		</div>
		<div class="box_2_center_panel">
';

		$post_num = 0;

		mine_category_in_shop(1);
		mine_category_in_shop(2);
		mine_category_in_shop(3);
		mine_category_in_shop(4);

		echo '						
		</div>
	</div>
		';

bottom_auth();
?>