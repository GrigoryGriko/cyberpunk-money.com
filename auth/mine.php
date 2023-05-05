<?php
    header("Content-Type: text/html; charset=utf-8");  // стандартная установка кодировки
?>
<?php
if ( isset($_GET['id']) ) {
	if ( is_numeric($_GET['id']) ) {
		$db->Query("SELECT * FROM `users_amount_mine` WHERE `uid` = '$_SESSION[id]' AND `id` = '$_GET[id]'");
		$NumRows = $db->NumRows();	
		if ( !empty($NumRows) ) {
			$assoc_u_a_m = $db->FetchAssoc();
			$_SESSION['GET_id'] = $_GET['id'];

			$db->Query("SELECT * FROM `mine_in_shop` WHERE `category` = '$assoc_u_a_m[category]' ");
			$NumRows2 = $db->NumRows();	
			if ( !empty($NumRows2) ) {
				while ( $row = $db->FetchAssoc() ) {
					$num_level = $row['level'];
					foreach ($row as $key => $value) {
						$assoc_m_i_s[$key][$num_level] = $value; // $value = $row[$key]	//данные рудников всех уровней одной категории
					}
				}

				$name_mine = 'Персонаж "'.$assoc_u_a_m['first_name'].'", Уровень '.$assoc_u_a_m['level']; //название рудника с уровнем
				
				$year_buy = substr($assoc_u_a_m['date_buy'], 0, 4);
				$month_buy = substr($assoc_u_a_m['date_buy'], 5, 2);
				$day_buy = substr($assoc_u_a_m['date_buy'], 8, 2);
				$date_buy = $day_buy.'.'.$month_buy.'.'.$year_buy;	//форматирование даты покупки

			}
			else {
				exit( header('Location: /my_field') );	
			}
		}
		else {
			exit( header('Location: /my_field') );
		}
	}
}
else {
	exit( header('Location: /my_field') );
}





top_auth('Мой кабинет', 'auth/style/minestyle', $name_mine);
?>

<script type='text/javascript'>
	function ajax_mine() {
		$.get("ajax/ajax_mine", function(data) {	//функция получает данные data с файла по директории /ajax_my_field
			data = $(data);
			$(".text2_element_1_stroke_3_box_2_center_panel").html( $(".container_ready_collect_minerals", data).html() );	//извлечение конкретных балансов из одного файла

		});
	};
	function ajax_mine2() {
		$.get("ajax/ajax_mine", function(data2) {	//функция получает данные data с файла по директории /ajax_my_field
			data2 = $(data2);
			$(".elementtext_2_stroke_1_center_panel").html( $(".container_name_mine", data2).html() );
			$(".text_boxgradient_sb1cp").html( $(".container_name_mine", data2).html() );
			$(".data_name_mine").html( $(".container_name_mine", data2).html() );

			$("#level_t2te1s4b1cp").html( $(".container_level_mine", data2).html() );
			$("#rate_mining_t2te1s4b1cp").html( $(".container_rate_mining", data2).html() );

			$("#level_up_block").html( $(".container_info_level_up", data2).html() );
		});
	};


	$(document).ready(function() {  //запускает код при загрузке страницы
		/*post_query('ajax/ajax_mine', 'mine', 'get_id');*/
		ajax_mine();
 		ajax_mine2();

		/*setInterval('post_query()', 1000);*/
		setInterval('ajax_mine()', 10000);
	});


    $(document).on("mouseenter", "#select_button", function() {
        setTimeout( function() {
            $("#select_button_01").hide();
            $("#select_button_02").show();
        }, 100);
    });
    $(document).on("mouseleave", "#select_button", function() {
        setTimeout( function() {
            $("#select_button_02").hide();
            $("#select_button_01").show();
        }, 100);
    });

    $(document).on("mouseenter", "#levelup_button", function() {
        setTimeout( function() {
            $("#levelup_button_01").hide();
            $("#levelup_button_02").show();
        }, 100);
    });
    $(document).on("mouseleave", "#levelup_button", function() {
        setTimeout( function() {
            $("#levelup_button_02").hide();
            $("#levelup_button_01").show();
        }, 100);
    });
</script>

<?php

echo '
	<div class="boxgradient_strokebefore_1_center_panel">
		<div class="text_boxgradient_sb1cp">
			'.$name_mine.'
		</div>
	</div>
	<div class="box_strokeafter_1_center_panel">
		<div class="box_1_center_panel">
			<div class="element_2_stroke_3_box_1_center_panel">	

				<table class="tabletext_2-e2s3b2cp">
					<tr>
						<td>
							<img class="elementimage_into_td_tabletext_2-e2s3b2cp"src="../img/auth/mine/'.$assoc_u_a_m['image_name'].'">
							<div class="elementtext_into_td_tabletext_2-e2s3b2cp">
								информация:
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<div class="text1_table_element_1_stroke_4_box_1_center_panel">
								Уровень: </div>
							<div class="text2_table_element_1_stroke_4_box_1_center_panel" id="level_t2te1s4b1cp">';
		
				/*контейнер ajax-----VVVVVVVVVVVV-----*/
		
				echo			''.$assoc_u_a_m['level'].'';

				/*контейнер ajax-----AAAAAAAAAAAA-----*/

				echo
							'</div>
						</td>
					</tr>
					<tr>
						<td>
							<div class="text1_table_element_1_stroke_4_box_1_center_panel">
								Добыча: </div>
							<div class="text2_table_element_1_stroke_4_box_1_center_panel" id="rate_mining_t2te1s4b1cp">';
				
				/*контейнер ajax-----VVVVVVVVVVVV-----*/

				echo			''.round( ($assoc_m_i_s['rate_mining'][$assoc_u_a_m['level']] + $assoc_m_i_s['bonus'][$assoc_u_a_m['level']]), 0 ).' / мин';

				/*контейнер ajax-----AAAAAAAAAAAA-----*/

				echo
							'</div>
						</td>
					</tr>
					<tr>
						<td>
							<div class="text1_table_element_1_stroke_4_box_1_center_panel">
								Дата покупки: </div>
							<div class="text2_table_element_1_stroke_4_box_1_center_panel" id="date_buy_t2te1s4b1cp">
								'.$date_buy.'
							</div>
						</td>
					</tr>
				</table>

			</div>								
		</div>	
		<div class="box_2_center_panel">
			<div class="element_1_stroke_3_box_2_center_panel">
                <div class="box_contain_e1s3b2cp">
    				<div class="text1_element_1_stroke_3_box_2_center_panel">
    					Добыча, готовая к сбору:
    				</div>
    				<div class="text2_element_1_stroke_3_box_2_center_panel">';

/*контейнер ajax-----VVVVVVVVVVVV-----*/

/*контейнер ajax-----AAAAAAAAAAAA-----*/
		
		echo'
    				</div>

                    <div class="flexbox_stretch"></div>

    				<input type="hidden" id="id" value="'.$assoc_u_a_m['id'].'">
    				<input type="hidden" id="uid" value="'.$assoc_u_a_m['uid'].'">
    				<button id="select_button" class="button_1_element_1_stroke_4_box_2_center_panel" onclick="post_query(\'mine_shop/actions/mineralscollect\', \'collect_minerals\', \'id*+*uid\'); ajax_mine();">
                        <div class="square_left_img">
                            <img id="select_button_01" class="img_button_1e1s4b2cp" src="../img/auth/mine/icon_button_collect.png" width="16px" height="16px">
                            <img id="select_button_02" style="display: none" class="img_button_1e1s4b2cp" src="../img/auth/mine/icon_button_collect_02.png" width="16px" height="16px">
                        </div>
    					<p>собрать добычу</p>
    				</button>
                </div>
			</div>
			<div class="element_1_stroke_4_box_2_center_panel">
                <div id="level_up_block" class="box_contain_e1s3b2cp">';

		/*контейнер ajax-----VVVVVVVVVVVV-----*/

		/*контейнер ajax-----AAAAAAAAAAAA-----*/

		echo '
                </div>
			</div>
			<div class="element_1_stroke_5_box_2_center_panel">
				<table class="table_element_1_stroke_4_box_2_center_panel">
					<tr>
						<th class="th_1" colspan="4">
                            <div class="image_text_into_td">
                                <img class="th_1_img" src="../img/auth/leveltab/icon_market.png" width="15px" height="15px">
                                Таблица уровней '.$assoc_u_a_m['second_name'].' "'.$assoc_u_a_m['first_name'].'"
                            </div>
                        </th>
				    </tr>	
					<tr id="tr_fill">
						<td rowspan="2">
							Уровень
						</td>
						<td colspan="2">
							Добыча (в минуту)
						</td> 
						<td rowspan="2">
							Стоимость
						</td>
				    </tr>
				    <tr id="tr_fill">
				        <td>Обычная</td> <td>Бонус</td>
				    </tr>';
			for ($level = 1; $level < 8; $level++)	{		//вывод всех записей по 7-ми уровням данной категории
				if ($assoc_m_i_s['bonus'][$level] == 0) {
					$assoc_m_i_s_bonus_level = '--';
				}
				else {
					$assoc_m_i_s_bonus_level = round($assoc_m_i_s['bonus'][$level], 1);
				}

 				echo' 
 					<tr class="contain_data">
				        <td>
				        	<div class="col_1_table_e1s4b2cp">
				        		'.$assoc_m_i_s['level'][$level].'
				        	</div>
				        </td>
				        <td>
				        	'.round($assoc_m_i_s['rate_mining'][$level], 0).'
				        </td> 
				        <td>
				        	'.$assoc_m_i_s_bonus_level.'</td> <td>'.round($assoc_m_i_s['price'][$level], 0).' руб.
				        </td>
				    </tr>
				';
			}

			echo'  
				    <tr>
						<th class="th_2" colspan="4">
                            <div class="image_text_into_td">
                                <a href="/leveltab">
                                    <img class="image_i" src="../img/auth/mine/img_link_leveltab.png" width="14px" height="11px">
                                    Перейти в полную таблицу уровней
                                </a>
                            </div>
                        </th>
				    </tr>
				</table>

			</div>';
			
           
           /* echo '<div class="element_1_stroke_6_box_2_center_panel">
                <div class="box_img_text_e1s6b2cp">';
			switch($assoc_u_a_m['category']) {
				case 1:  
					echo '
                        <img src="../img/auth/mine/tourmaline_decription.png">
						<p>Турмалин – один из любимейших минералов коллекционеров. Кристаллы ювелирного качества встречаются достаточно редко и высоко ценятся не только за редкость, но и за уникальность расцветки. В 1703 г. голландские моряки впервые привезли в Европу удлиненные кристаллы лилово-розового цвета. Вслед за жителями Цейлона они называли эти камни «туремали», что по-синегалезски означает «минерал», «драгоценный камень». Так возник современный термин «турмалин», которым называют разновидность силиката, включающего соединения алюминия, бора, марганца и магния.</p>
					';
				break;
				case 2:
					echo '
                        <img src="../img/auth/mine/topaz_decription.png">
						<p>Топаз — это один из самых популярных драгоценных камней. Откуда происходит название этого камня — до сих пор неясно. Одна из версий гласит, что топаз — это санскритское слово, которое обозначает тепло и огонь. По другой же версии топаз получил свое название в честь острова в Греции. История этого минерала уходит в глубину веков. Топаз высоко ценился на Руси и в Европе в эпоху Ренессанса. Археологами были найдены предметы ритуального назначения, сделанные из этого камня. В Древней Индии особую популярность завоевал топаз желтого цвета, став культовым атрибутом.</p>
					';
				break;
				case 3:
					echo '
                        <img src="../img/auth/mine/emerald_decription.png">
						<p>Изумруд - драгоценный камень 1-го класса, крупные бездефектные камни густого холодного тона массой свыше 5 карат ценятся дороже алмаза. Старинное название изумрудов – «смарагд», произошло от греческого «smaragdus», что в переводе означает «зеленый камень». Изумруд был широко известен еще в древности. Египтяне почитали этот драгоценный камень, как дар Бога Тота и символ Богини Исиды. В окрестностях египетского Асуана находились знаменитые изумрудные копи царицы Клеопатры.</p>
					';
				break;
				case 4:
					echo '
                        <img src="../img/auth/mine/diamond_decription.png">
						<p>Алмаз - это король всех минералов. Самый твёрдый, самый дорогой... каких только эпитетов не удостаивался этот минерал. «Несокрушимый» так переводится с древнегреческого название самого твердого минерала встречающегося на земле. Пять тысяч лет назад, людям стал известен завораживающий своей красотой, очаровывающий души и умы многих, прекраснейший камень — алмаз. Тысячи романов и рассказов, сотни фильмов и миллионы человеческих судеб, связанны с этим обворожительным камнем. Своей природой, он полностью оправдывает своё гордое имя, данное ему ещё древними Греками. ОН упорно не поддаётся рукам шлифовальщика и прозорливому разуму учёного, химическим реактивам и могущественному времени.</p> 
					';
				break;
			}
		echo '
                </div>
			</div>';*/

        echo '
            </div>
            </div>
		';

bottom_auth();
?>