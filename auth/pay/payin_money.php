<?php
    header("Content-Type: text/html; charset=utf-8");  // стандартная установка кодировки
?>
<?php
top_auth('Пополнение баланса', '/auth/pay/style/payin_moneystyle', false, 'payin_money');
?>

<?php

echo '
	<div class="box_strokeafter_2_center_panel">
		<div class="box_1_center_panel">
			<div class="field_b1cp">
				<p class="p_field_b1cp">Платежные системы</p>
				<div class="centerer_message"</div>';
					MessageShow();
echo '				
				</div>	
				<div class="container_fb1cp">';

$ps_count = 17; //количество платежных систем
$external_link = array('ПОПОЛНИТЬ', 'PAYEER', 'FREEKASSA', 'AdvCash', 'Exmo', 'Сбербанк онлайн', 'Яндекс деньги', 'Qiwi кошелек',  'Bitecoin', 'Litecoin', 'Ethereum', 'DASH', 'Monero', 'Dogecoin DOGE', 'Мегафон', 'Билайн', 'МТС', 'Tele2');

/*echo '
        <div class="box_payment_system">
            Сайт взломан, мы вынуждены остановить пополнения и выплаты, до устранения причины
        </div>';*/

for ($id = 1; $id <= $ps_count; $id++) {	//Количество платежных систем
	$style = '';
	if ($id == 1) {
		$width_img = '150px';
		$height_img = 'auto';
	}
	else if ($id == 2) {
		/*$style = 'display: none';*/

		$width_img = '216px';
		$height_img = '44px';
	}
	else if ($id == 3) {
		$width_img = '150px';
		$height_img = '50px';
	}
	else if ($id == 4) {
		$width_img = '117px';
		$height_img = '47px';
	}
	else if ($id == 5) {
		$width_img = '135px';
		$height_img = '38px';
	}
	else if ($id == 6) {
		$width_img = '110px';
		$height_img = '51px';
	}
	else if ($id == 7) {
		$width_img = '137px';
		$height_img = '47px';
	}
	else if ($id == 8) {
		$width_img = '150px';
		$height_img = '31px';
	}
	else if ($id == 9) {
		$width_img = '132px';
		$height_img = '29px';
	}
	else if ($id == 10 or $id == 11 or $id == 12 or $id == 13) {
		$width_img = '117px';
		$height_img = '47px';
	}
	else if ($id == 14) {
		$width_img = '102px';
		$height_img = '55px';
	}
	else if ($id == 15) {
		$width_img = '121px';
		$height_img = '38px';
	}
	else if ($id == 16) {
		$width_img = '172px';
		$height_img = '45px';
	}
	else if ($id == 17) {
		$width_img = '105px';
		$height_img = '40px';
	}
	else {
		$width_img = '216px';
		$height_img = '44px';
	}

	$ps_id = 'ps_'.$id;

	echo '
					<div class="box_payment_system" style="'.$style.'">
						<img src="../img/auth/pay/'.$ps_id.'.png" width="'.$width_img.'" height="'.$height_img.'">
						<button id="'.$ps_id.'" class="button_bps orange_button">'.$external_link[$id].'</button>
					</div>';
}

echo '
				</div>
			</div>
		</div>
	</div>

	<div class="blackout" style="display: none">
		<div class="background_around" style="display: none"></div>
		
		<div class="field_payin">
			<div class="header_field_payin">
				Пополнение баланса

				<img id="cross_01" src="../img/auth/pay/cross_01.png" width="15px" height="15px">
				<img id="cross_02" style="display: none;" src="../img/auth/pay/cross_02.png" width="15px" height="15px">
			</div>

			<div class="part_2_field_payin">';

/*$external_link = array('../pay/payin_money', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k');	//массив ссылок обработки запроса для каждой кнопки*/
for ($id2 = 1; $id2 <= $ps_count; $id2++) {	//вывод окна соответствующего платеэной системе
	if ($id2 == 1) {
		$width_img = '150px';
		$height_img = 'auto';
	}
	else if ($id2 == 2) {
		$width_img = '216px';
		$height_img = '44px';
	}
	else if ($id2 == 3) {
		$width_img = '150px';
		$height_img = '50px';
	}
	else if ($id2 == 4) {
		$width_img = '117px';
		$height_img = '47px';
	}
	else if ($id2 == 5) {
		$width_img = '135px';
		$height_img = '38px';
	}
	else if ($id2 == 6) {
		$width_img = '110px';
		$height_img = '51px';
	}
	else if ($id2 == 7) {
		$width_img = '137px';
		$height_img = '47px';
	}
	else if ($id2 == 8) {
		$width_img = '150px';
		$height_img = '31px';
	}
	else if ($id2 == 9) {
		$width_img = '132px';
		$height_img = '29px';
	}
	else if ($id2 == 10 or $id2 == 11 or $id2 == 12 or $id2 == 13) {
		$width_img = '117px';
		$height_img = '47px';
	}
	else if ($id2 == 14) {
		$width_img = '102px';
		$height_img = '55px';
	}
	else if ($id2 == 15) {
		$width_img = '121px';
		$height_img = '38px';
	}
	else if ($id2 == 16) {
		$width_img = '172px';
		$height_img = '45px';
	}
	else if ($id2 == 17) {
		$width_img = '105px';
		$height_img = '40px';
	}
	else {
		$width_img = '216px';
		$height_img = '44px';
	}	

	$ps_id2 = 'ps_'.$id2;
	$img_ps = 'img_ps_'.$id2;

	echo '			
				<img id="'.$img_ps.'" class="img_ps" style="display: none" src="../img/auth/pay/'.$ps_id2.'.png" width="'.$width_img.'" height="'.$height_img.'">';
}

echo '
				<p class="text_p2fp">Введите сумму пополнения: (руб.)</p>';

for ($id3 = 1; $id3 <= $ps_count; $id3++) {
	$form_id3 = 'form_ps_'.$id3;
	$ps_id3 = 'button_ps_'.$id3;
	$ps_post = 'payin_sys_'.$id3;
	$name_id3 = 'name_'.$id3;
	if ( empty($external_link[$id3]) ) {
		$external_link[$id3] = $external_link[0];	//внешняя ссылка для кнопки			
	}	
	echo '
				<form id="'.$form_id3.'" class="form_class" style="display: none" name="'.$name_id3.'" method="post" action="/actions/pay_money">
					<input name="sum_payin" id="'.$id3.'" onfocus="this.className=\'onfocus\'" onblur="this.className=\'onblur\'">	<!--Сумма платежа (внутри сайта)-->
					<input type="hidden" name="'.$ps_post.'" value="'.$ps_post.'">		<!--Идентификатор платежной системы (внутри сайта)-->
					<button type="submit" id="'.$ps_id3.'" class="button_bps blue_button">пополнить баланс</button>
				</form>';	//переход на страницу оплаты, а в проследствии на сайт платежной системы
}

echo '
			</div>
		</div>
	</div>
		';

bottom_auth();
?>