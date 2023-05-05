<?php
top_auth('Системный рынок', 'auth/style/marketstyle');
?>

<script type="text/javascript">
	function ajax_market() {
		$.get("ajax/ajax_market", function(data) {	//функция получает данные data с файла по директории
			data = $(data);
			$(".stroketext_2-element_1234-e1s5b1cp_1").html( $(".container_amount_tourmaline", data).html() );	//извлечение конкретных балансов из одного файла
			$(".stroketext_2-element_1234-e1s5b1cp_2").html( $(".container_amount_topaz", data).html() );
			$(".stroketext_2-element_1234-e1s5b1cp_3").html( $(".container_amount_emerald", data).html() );
			$(".stroketext_2-element_1234-e1s5b1cp_4").html( $(".container_amount_diamond", data).html() );
		});
	}

	$(document).ready(function() {  //запускает код при загрузке страницы
		ajax_market();
	});
</script>

<?php
echo '
	<div class="box_strokeafter_2_center_panel">
		<div class="box_1_center_panel">
			<div class="element_1_stroke_3_box_1_center_panel">
				<div class="container_e1s3b1cp">
					<div class="p_1">
						<img class="left_img" src="../img/auth/market/market_icon.png" width="100px" height="100px">
						<p>Системный рынок позволяет продавать собранную Вами добычу и обменивать ее на баланс для вывода.
						Минимальное кол-во артефактов одного типа для продажи - 1000, курс продажи <b>фиксирован</b>, и не будет
						меняться. Средства вырученные с продажи артефактов Вы можете вывести из проекта любым способом, либо обменять
						и купить больше персонажей, поднять уровень уже купленных рудников или же потратить на рекламу.</p>
					</div>
				</div>
			</div>
			<div class="element_1_stroke_4_box_1_center_panel">
				<img src="../img/auth/market/icon_market.png" width="16px" height="16px">
				Артефакты на вашем складе
			</div>
			<nav class="nav_element_1_stroke_5_box_1_center_panel">
				<ul id="ul_element_1_stroke_5_box_1_center_panel">';

/*------------извлечение данных sql----------VVVVVVVVVVVVVVVVVVV----*/

$db->Query("SELECT `first_name`, `second_name`, `image_name`, `category` FROM `mine_in_shop` WHERE `level` = 1");
$mine_in_shop = $db->NumRows();
if ( !empty($mine_in_shop) ) {
	while ( $row = $db->FetchAssoc() ) {
		$num = $row['category'];
		foreach ($row as $key => $value) {
			$assoc_mine_in_shop[$key][$num] = $value; // $value = $row[$key]
		}
	}
}

$db->Query("SELECT `name`, `price_mineral`, `for_count`, `category` FROM `data_mineral_to_sell`");
$data_mineral_to_sell = $db->NumRows();
if ( !empty($data_mineral_to_sell) ) {
	while ( $row = $db->FetchAssoc() ) {
		$num = $row['category'];
		foreach ($row as $key => $value) {
			$assoc_data_mineral_to_sell[$key][$num] = $value; // $value = $row[$key]
		}
	}
}

/*------------извлечение данных sql----------AAAAAAAAAAAAAAAAAAA----*/
$db->Query("SELECT `tourmaline`, `topaz`, `emerald`, `diamond` FROM `users_data` WHERE `uid` = $_SESSION[id]");
$users_data = $db->NumRows();
if ( !empty($users_data) ) {
    $assoc_users_data = $db->FetchAssoc();
}

function mine_mineral_category ($category) {
	global $assoc_mine_in_shop;
	global $assoc_data_mineral_to_sell;
    global $assoc_users_data;

    switch ($category) {
        case 1:
            $amount_minerals = round($assoc_users_data['tourmaline'], 2);
            break;
        case 2:
            $amount_minerals = round($assoc_users_data['topaz'], 2);
            break;
        case 3:
           $amount_minerals = round($assoc_users_data['emerald'], 2);
            break;
        case 4:
            $amount_minerals = round($assoc_users_data['diamond'], 2);
            break;

        default:
            $amount_minerals = 0;
            break;
    }
	


	echo'
					<li>
						<div class="element_1234-e1s5b1cp" id="sell_mineral_'.$category.'">
							<div class="stroketext_1-element_1234-e1s5b1cp">
								<p><span>Артефакты '.$assoc_mine_in_shop['second_name'][$category].'</span><br>"'.$assoc_mine_in_shop['first_name'][$category].'"</p>
							</div>
								<img class="strokeimage-element_1234-e1s5b1cp" src="../img/auth/my_field/'.$assoc_mine_in_shop['image_name'][$category].'">
							<button class="stroketext_2-element_1234-e1s5b1cp_'.$category.'">';
/*контейнер ajax-----VVVVVVVVVVVV--баланс для покупок---*/

/*контейнер ajax-----AAAAAAAAAAAA-----*/
	echo'
							</button>
							<button class="stroketext_3-element_1234-e1s5b1cp">
								Курс за '.round($assoc_data_mineral_to_sell['for_count'][$category], 0).' артефактов<br>
								<span>'.$assoc_data_mineral_to_sell['price_mineral'][$category].' руб.</span>
							</button>
							<input type="number" class="input_amount_minerals" id="amount_minerals_'.$category.'" value="'.$amount_minerals.'">
							<div class="stroketext_4-element_1234-e1s5b1cp">
								Вы получите после продажи:
							</div>
							<input type="hidden" id="get_money_'.$category.'" value="0" step="0.00001" >
							<input type="hidden" id="category_'.$category.'" value="'.$category.'" >
							<input type="text" class="input_get_money" id="show_get_money_'.$category.'" value="0.00000 руб." readonly>
							<button class="button_stroketext_5-element_1234-e1s5b1cp" onclick="post_query(\'mine_shop/actions/mineralssell\', \'mineralssell\', \'amount_minerals_'.$category.'*+*category_'.$category.'\'); ajax_index_top_auth(); ajax_market();">
								продать артефакты
							</button>			
						</div>
					</li>';
}
mine_mineral_category (1);
mine_mineral_category (2);
mine_mineral_category (3);
mine_mineral_category (4);
	echo'
				</ul>								
			</nav>

                
		</div>
	</div>';

	echo'
	<script>
		var mineral_to_sell_price_mineral_1 = '.$assoc_data_mineral_to_sell['price_mineral'][1].';
		var mineral_to_sell_price_mineral_2 = '.$assoc_data_mineral_to_sell['price_mineral'][2].';
		var mineral_to_sell_price_mineral_3 = '.$assoc_data_mineral_to_sell['price_mineral'][3].';
		var mineral_to_sell_price_mineral_4 = '.$assoc_data_mineral_to_sell['price_mineral'][4].';

		var mineral_to_sell_for_count_1 = '.$assoc_data_mineral_to_sell['for_count'][1].';
		var mineral_to_sell_for_count_2 = '.$assoc_data_mineral_to_sell['for_count'][2].';
		var mineral_to_sell_for_count_3 = '.$assoc_data_mineral_to_sell['for_count'][3].';
		var mineral_to_sell_for_count_4 = '.$assoc_data_mineral_to_sell['for_count'][4].';

		function calcul(category, pr_mineral, f_count ) {
			var a = document.getElementById("sell_mineral_" + category);
            $(document).ready(function() {
                var first = +document.getElementById("amount_minerals_" + category).value;
                var price_mineral = pr_mineral;
                var for_count = f_count;
                var get_money = document.getElementById("get_money_" + category).value = ( first * (price_mineral / for_count) ).toFixed(5);

                if (get_money == 0) {
                    var get_money = "0.00000";
                }
                document.getElementById("show_get_money_" + category).value = get_money+" руб.";
            });
			a.onchange = a.onkeyup = function() {
				var first = +document.getElementById("amount_minerals_" + category).value;
				var price_mineral = pr_mineral;
				var for_count = f_count;
				var get_money = document.getElementById("get_money_" + category).value = ( first * (price_mineral / for_count) ).toFixed(5);

				if (get_money == 0) {
					var get_money = "0.00000";
				}
				document.getElementById("show_get_money_" + category).value = get_money+" руб.";
			};
		};
		calcul(1, mineral_to_sell_price_mineral_1, mineral_to_sell_for_count_1);
		calcul(2, mineral_to_sell_price_mineral_2, mineral_to_sell_for_count_2);
		calcul(3, mineral_to_sell_price_mineral_3, mineral_to_sell_for_count_3);
		calcul(4, mineral_to_sell_price_mineral_4, mineral_to_sell_for_count_4);
	</script>
';

bottom_auth();
?>