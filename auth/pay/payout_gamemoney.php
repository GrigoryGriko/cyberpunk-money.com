<?php
    header("Content-Type: text/html; charset=utf-8");  // стандартная установка кодировки
?>
<?php
top_auth('Заказать выплату игровых денег', '/auth/pay/style/payout_gamemoneystyle', false, 'payout_money', true);
?>

<?php

$db->Query("SELECT * FROM `users` WHERE `id` = '$_SESSION[id]'");
$NumRows = $db->NumRows();
if ( !empty($NumRows) ) {
    $assoc_users = $db->FetchAssoc();
    if ( !empty($assoc_users['payment_password']) ) {
        $payment_password = true;
    }
    else {
        $payment_password = false;
    }
}

echo '
	<div class="box_strokeafter_2_center_panel">
		<div class="box_1_center_panel">
			<div class="centerer_message"</div>';
				MessageShow();

echo '			
	
			</div>
			<div class="flexbox_element_1_stroke_3_box_1_center_panel">';


$ps_count = 13; //количество платежных систем
for ($id = 1; $id <= $ps_count; $id++) {	//Количество платежных систем
	
	if ($id == 1) {
		$width_img = '216px';
		$height_img = '44px';
	}
	else if ($id == 2) {
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
		$width_img = '121px';
		$height_img = '38px';
	}
	else if ($id == 6) {
		$width_img = '121px';
		$height_img = '38px';
	}
	else if ($id == 7) {
		$width_img = '102px';
		$height_img = '55px';
	}
	else if ($id == 8) {
		$width_img = '172px';
		$height_img = '45px';
	}
	else if ($id == 9) {
		$width_img = '105px';
		$height_img = '40px';
	}
	else if ($id == 10) {
		$width_img = 'auto';
		$height_img = '60px';
	}
	else if ($id == 11) {
		$width_img = 'auto';
		$height_img = '35px';
	}
	else if ($id == 12) {
		$width_img = 'auto';
		$height_img = '57px';
	}
	else if ($id == 13) {
		$width_img = '128px';
		$height_img = '72px';
	}

	if ($id != 2) {
		$ps_id = 'ps_'.$id;

		echo '			
					<div class="div_element_1_stroke_3_box_1_center_panel">
						<img src="../img/auth/payout/'.$ps_id.'.png" width="'.$width_img.'" height="'.$height_img.'">
						<button id="'.$ps_id.'" class="button_bps orange_button">Заказать выплату</button>	
					</div>';
	}
}


echo '
	<div class="blackout" style="display: none">
		<div class="background_around" style="display: none"></div>
		
		<div class="field_payin">
			<div class="header_field_payin">
				Вывод средств

				<img id="cross_01" src="../img/auth/payout/cross_01.png" width="15px" height="15px">
				<img id="cross_02" style="display: none;" src="../img/auth/payout/cross_02.png" width="15px" height="15px">
			</div>

			<div class="part_2_field_payin">';

/*---------------------------Извлечение данных кошельков из базы-----------VVVVVVVVVVVVVVVVVVVVVVVVVVVVV--------------------------------------------*/

$db->Query('SELECT * FROM `wallet_payout_info`');
$NumRows_wallet = $db->NumRows();
if ( !empty($NumRows_wallet) ) {
	while ( $row = $db->FetchAssoc() ) {
		$num = $row['id'];
        foreach ($row as $key => $value) {
            $assoc_wallet[$num][$key] = $value; // $value = $row[$key]
        }
	}
}

/*---------------------------Извлечение данных кошельков из базы-----------AAAAAAAAAAAAAAAAAAAAAAAAAAAAA--------------------------------------------*/

for ($id3 = 1; $id3 <= $ps_count; $id3++) {

	if ($id3 == 1) {
		$width_img = '216px';
		$height_img = '44px';
	}
	else if ($id3 == 2) {
		$width_img = '216px';
		$height_img = '44px';
	}
	else if ($id3 == 3) {
		$width_img = '150px';
		$height_img = '50px';
	}
	else if ($id3 == 4) {
		$width_img = '117px';
		$height_img = '47px';
	}
	else if ($id3 == 5) {
		$width_img = '121px';
		$height_img = '38px';
	}
	else if ($id3 == 6) {
		$width_img = '121px';
		$height_img = '38px';
	}
	else if ($id3 == 7) {
		$width_img = '102px';
		$height_img = '55px';
	}
	else if ($id3 == 8) {
		$width_img = '172px';
		$height_img = '45px';
	}
	else if ($id3 == 9) {
		$width_img = '105px';
		$height_img = '40px';
	}
	else if ($id3 == 10) {
		$width_img = 'auto';
		$height_img = '60px';
	}
	else if ($id3 == 11) {
		$width_img = 'auto';
		$height_img = '35px';
	}
	else if ($id3 == 12) {
		$width_img = 'auto';
		$height_img = '57px';
	}
	else if ($id3 == 13) {
		$width_img = '128px';
		$height_img = '72px';
	}

	$ps_id3 = 'ps_'.$id3;
	$img_ps = 'img_ps_'.$id3;
	$block_ps = 'block_ps_'.$id3;

	$form_id3 = 'form_ps_'.$id3;
	$button_id3 = 'button_ps_'.$id3;
	$ps_post = 'payin_sys_'.$id3;
	$name_id3 = 'name_'.$id3;
	if ( empty($external_link[$id3]) ) {
		$external_link[$id3] = $external_link[0];	//внешняя ссылка для кнопки			
	}		
	echo '
				<form id="'.$form_id3.'" class="form_class" style="display: none" name="'.$name_id3.'" method="post" action="/actions/get_gamemoney">
					<img id="'.$img_ps.'" class="img_ps" style="display: none" src="../img/auth/payout/'.$ps_id3.'.png" width="'.$width_img.'" height="'.$height_img.'">
					<div id="'.$block_ps.'" class="block_ps" >
						<div class="text_p2fp"><div>Мин. сумма:</div> <div>'.round($assoc_wallet[$id3]['min_sum'], 2).' руб.</div></div>
						<div class="text_p2fp"><div>Макс. сумма:</div> <div>'.round($assoc_wallet[$id3]['max_sum'], 2).' руб.</div></div>
						<div class="text_p2fp"><div>Комиссия:</div> <div>'.round($assoc_wallet[$id3]['comission'], 2).'% + '.round($assoc_wallet[$id3]['comission_rub']).' руб.</div></div>
						<div class="text_p2fp"><div>Формат кошелька:</div> <div>'.$assoc_wallet[$id3]['format'].'</div></div>
						
						<input name="sum_payout" id="input_sum_'.$id3.'" onfocus="this.className=\'onfocus\'" onblur="this.className=\'onblur\'" type="text" placeholder="Введите суммы выплаты... (руб.)">
						<input name="sum_payout_none" id="total_money_'.$id3.'" type="hidden" value="0.00">
						<input id="show_total_money_'.$id3.'" type="text" value="Вы получите с учётом комиссии: 0.00" readonly="readonly">
						
						<input name="acount_wallet" id="account_wallet_'.$id3.'" onfocus="this.className=\'onfocus\'" onblur="this.className=\'onblur\'" type="text" placeholder="Укажите номер кошелька для выплаты">';
    if ($payment_password == true) {                    
        echo '
                        <input name="payment_password" id="payment_password_'.$id3.'" onfocus="this.className=\'onfocus\'" onblur="this.className=\'onblur\'" type="password" placeholder="Введите платежный пароль">';
    }

    echo '
						<input type="hidden" name="'.$ps_post.'" value="'.$ps_post.'">		<!--Идентификатор платежной системы (внутри сайта)-->
					</div>

					<button type="submit" id="'.$button_id3.'" class="button_bps blue_button">заказать выплату</button>
				</form>';
}

echo '
			</div>
		</div>
	</div>
	</div>';


echo '
	<div class="flexbox_element_1_stroke_4_box_1_center_panel">
		<div class="over_fe1s4b1cp">
			<div class="fe1s4b1cp">
				<div class="header_block">';
/*echo '
	Сайт был взломан. Поэтому нам пришлось на время отключить кошелек от сайта. 
	<br>Ваши средства в безопасности, работы по исправлению проблем идут. И безусловно 
	<br>теперь у нас будут ручные выплаты - это не заменит ни одна защита.
';*/


echo '
					ВАШИ ИГРОВЫЕ ВЫПЛАТЫ<br>
					ПОСЛЕДНИЕ 100 ВЫПЛАТ.<br>
                    <p style="text-transform: none; font-weight: 400">(Выплаты обрабатываются за 5 минут)</p>';
echo '
				</div>
				<div class="block_table">

					<table class="table_element_1_stroke_4_box_2_center_panel">

	                        <tr id="tr_fill">
	                            <td>
	                                Дата Выплаты
	                            </td>
	                            <td>
	                               Сумма Выплаты
	                            </td>
	                            <td>
	                            	Платежная Система
	                            </td> 
	                            <td>
	                                Реквизиты
	                            </td>
	                            <td>
	                                Статус
	                            </td>                       
	                        </tr>';
	
$db->Query("SELECT * FROM `history_money_payout` WHERE `uid` = '$_SESSION[id]' AND `bot` = 2 ORDER BY `date` DESC");
$NumRows_list_payout = $db->NumRows();
if ( !empty($NumRows_list_payout) ) {
	$count_color = 1;
	while ( $assoc_list_payout = $db->FetchAssoc() ) {
		$count_color++;
		if ($count_color % 2 != 0) $style_color = 'style="background: #f2eeff"';
		else $style_color = '';

		switch ($assoc_list_payout['status']) {
			case 0:
				$assoc_list_payout['status'] = 'Ожидание';
				$image_name = 'await.png';
				break;
			case 1:
				$assoc_list_payout['status'] = 'Выплачено';
				$image_name = 'confirm.png';
				break;
			default:
				$assoc_list_payout['status'] = 'отказано';
				$image_name = 'denied.png';
				break;

		}

		echo '
	                        <tr class="contain_data" '.$style_color.'">
	                            <td class="lvl_num">
	                            	'.$assoc_list_payout['date'].'
	                            </td>
	                            <td>
	                                '.$assoc_list_payout['money_withdrawn'].' руб.
	                            </td>
	                            <td>
	                                '.$assoc_list_payout['payment_system'].'
	                            </td> 
	                            <td>
	                                '.$assoc_list_payout['account_wallet'].'
	                            </td>
	                            <td class="status_payout">
	                            	<img src="../img/auth/payout/'.$image_name.'" width="18px" height="18px">
	                                '.$assoc_list_payout['status'].'
	                            </td>
	                        </tr>';
	}
}	

echo '	    
	                    </table> 

				</div>
			</div>
		</di>
	</div>';

echo'
	<script>
		var comission_wallet_1 = '.$assoc_wallet[1]['comission'].';
		var comission_wallet_2 = '.$assoc_wallet[2]['comission'].';
		var comission_wallet_3 = '.$assoc_wallet[3]['comission'].';
		var comission_wallet_4 = '.$assoc_wallet[4]['comission'].';
		var comission_wallet_5 = '.$assoc_wallet[5]['comission'].';
		var comission_wallet_6 = '.$assoc_wallet[6]['comission'].';
		var comission_wallet_7 = '.$assoc_wallet[7]['comission'].';
		var comission_wallet_8 = '.$assoc_wallet[8]['comission'].';
		var comission_wallet_9 = '.$assoc_wallet[9]['comission'].';

		var comission_wallet_10 = '.$assoc_wallet[10]['comission'].';
		var comission_rub_10 = '.$assoc_wallet[10]['comission_rub'].';

		var comission_wallet_11 = '.$assoc_wallet[11]['comission'].';
		var comission_rub_11 = '.$assoc_wallet[11]['comission_rub'].';

		var comission_wallet_12 = '.$assoc_wallet[12]['comission'].';
		var comission_rub_12 = '.$assoc_wallet[12]['comission_rub'].';

		var comission_wallet_13 = '.$assoc_wallet[13]['comission'].';
		var comission_rub_13 = '.$assoc_wallet[13]['comission_rub'].';

		function calcul(wallet_num, wal_comission, comission_rub = 0) {
			var a = document.getElementById("block_ps_" + wallet_num);
			a.onchange = a.onkeyup = function() {
				

				var first = +document.getElementById("input_sum_" + wallet_num).value;

				var wallet_comission = wal_comission;
				var comis_rub = comission_rub

				var total_money = document.getElementById("total_money_" + wallet_num).value = ( first - (first * wal_comission / 100) - comis_rub).toFixed(2);	/*вычитание из введенного значения комиссии*/
				if (total_money < 0) {
					var total_money = "0.00";
				}
				if (total_money == 0) {
					var total_money = "0.00";
				}
				document.getElementById("show_total_money_" + wallet_num).value = "Вы получите с учётом комиссии: "+total_money+" руб.";



			};
		};
		calcul(1, comission_wallet_1);
		calcul(2, comission_wallet_2);
		calcul(3, comission_wallet_3);
		calcul(4, comission_wallet_4);
		calcul(5, comission_wallet_5);
		calcul(6, comission_wallet_6);
		calcul(7, comission_wallet_7);
		calcul(8, comission_wallet_8);
		calcul(9, comission_wallet_9);
		calcul(10, comission_wallet_10, comission_rub_10);
		calcul(11, comission_wallet_11, comission_rub_11);
		calcul(12, comission_wallet_12, comission_rub_12);
		calcul(13, comission_wallet_13, comission_rub_13);
	</script>
';

bottom_auth();
?>