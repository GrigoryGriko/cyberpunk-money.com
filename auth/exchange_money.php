<?php
    header("Content-Type: text/html; charset=utf-8");  // стандартная установка кодировки
?>
<?php
top_auth('Обмен баланса', 'auth/style/exchange_moneystyle');
?>

<script type='text/javascript'>
	function ajax_exchange_money() {

	};
</script>

<?php


echo '
	<div class="box_strokeafter_2_center_panel">
		<div class="box_1_center_panel">
			<nav class="nav_element_1_stroke_3_box_1_center_panel">
				<ul class="ul_element_1_stroke_3_box_1_center_panel">
					<li>
						<div class="div_element_1_stroke_3_box_1_center_panel">
							<div class="text1_e1s3b1cp">
								Обмен с вывода<br>
								на покупки
							</div>
							<img src="../img/auth/exchange_money/exchange1.png" class="image_e1s3b1cp" width="256" height="126">

							<div class="text2_e1s3b1cp">
								<div class="line_text2_e1s3b1cp">
								</div>
								<div class="text_text2_e1s3b1cp">
									Односторонний обмен средств с Вашего
									баланса для вывода, на Ваш баланс для
									покупок. Минимальная сумма: 1 руб.<br>

									<span>
										-Описание направления обмена
									</span>
								</div>

								<input type="text" class="input_exchangemoney" id="from_moneypayout_to_moneybuy" value="Введите сумму обмена... (руб.)">
								<button class="button_text2_e1s3b1cp" onclick="post_query(\'mine_shop/actions/moneyexchange\', \'moneyexchange_buy\', \'from_moneypayout_to_moneybuy\'); ajax_index_top_auth();">
									Произвести обмен
								</button>	
							</div>
						</div>
					</li>
	
					<li>
						<div class="div_element_1_stroke_3_box_1_center_panel">
							<div class="text1_e1s3b1cp">
								Обмен с вывода<br>
								на рекламу
							</div>
							<img src="../img/auth/exchange_money/exchange1.png" class="image_e1s3b1cp">
                            
							<div class="text2_e1s3b1cp">
								<div class="line_text2_e1s3b1cp">
								</div>
								<div class="text_text2_e1s3b1cp">
									Односторонний обмен средств с Вашего
									баланса для вывода, на Ваш баланс для
									рекламы. Минимальная сумма: 1 руб.<br>
									<span>
										Описание направления обмена
									</span>
								</div>

								<input type="text" class="input_exchangemoney" id="from_moneypayout_to_moneyad" value="Введите сумму обмена... (руб.)">
								<button class="button_text2_e1s3b1cp" id="button_from_moneypayout_to_moneyad" onclick="post_query(\'mine_shop/actions/moneyexchange\', \'moneyexchange_ad\', \'from_moneypayout_to_moneyad\'); ajax_index_top_auth();">
									Произвести обмен
								</button>
							</div>
						</div>
					</li>
				</ul>
			</nav>
		</div>
	</div>

	<script>	

		var m_buy = document.getElementById("from_moneypayout_to_moneybuy");
		var m_ad = document.getElementById("from_moneypayout_to_moneyad");
		
		function switch_input(id_input1, id_input2) { 
			id_input1.onfocus = function() {
				id_input2.value = "Введите сумму обмена... (руб.)";	
				if (id_input1.value == "Введите сумму обмена... (руб.)") {
					id_input1.value = "";
				}
			}
			id_input1.onblur = function() {
				if (id_input1.value == "") {
					id_input1.value = "Введите сумму обмена... (руб.)";
				}
			}
		}
		
		switch_input(m_buy, m_ad);
		switch_input(m_ad, m_buy);

	</script>
		';

bottom_auth();
?>