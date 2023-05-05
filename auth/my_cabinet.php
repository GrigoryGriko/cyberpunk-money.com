<?php
    header("Content-Type: text/html; charset=utf-8");  // стандартная установка кодировки
?>
<?php
top_auth('Мой кабинет', 'auth/style/my_cabinetstyle', false, 'my_cabinet', true);

$db->Query("SELECT * FROM `games_schulte_tab` WHERE `user_create_id` = '$_SESSION[id]' AND `user_invite_id` != 0 AND `id_user_win` = 0");       /*обновление для игры "Таблица внимательности"*/
$NumRows = $db->NumRows();
if ( !empty($NumRows) ) {
    while ( $assoc = $db->FetchAssoc() ) {
        $assoc['date_user_2_start'] = strtotime($assoc['date_user_2_start']);
        $interval = time() - $assoc['date_user_2_start'];
        if ($interval > 1800) { //более получаса
            $COMISSION = 0.08; //8%
            

            $assoc['sum_bet'] = $assoc['sum_bet'] - ($assoc['sum_bet'] * $COMISSION);

            $user_id_win = $assoc['user_create_id'];

            $QUERY_UPDATE_1 = $db->Query_recordless("UPDATE `games_schulte_tab` SET `id_user_win` = '$user_id_win', `date_user_2_end` = NOW() WHERE `id` = '$assoc[id]'");
            $QUERY_UPDATE_2 = $db->Query_recordless("UPDATE `users_data` SET `balance_buy` = (`balance_buy` + '$assoc[sum_bet]') WHERE `uid` = '$user_id_win'");

            @mysqli_free_result($QUERY_UPDATE_1);
            @mysqli_free_result($QUERY_UPDATE_2);
        }
    }
}
$db->Query("SELECT * FROM `games_schulte_tab` WHERE `user_invite_id` = '$_SESSION[id]' AND `id_user_win` = 0");       /*обновление для игры "Таблица внимательности"*/
$NumRows = $db->NumRows();
if ( !empty($NumRows) ) {
    while ( $assoc = $db->FetchAssoc() ) {
        $assoc['date_user_2_start'] = strtotime($assoc['date_user_2_start']);
        $interval = time() - $assoc['date_user_2_start'];
        if ($interval > 1800) { //более получаса
            $COMISSION = 0.08; //8%
            

            $assoc['sum_bet'] = $assoc['sum_bet'] - ($assoc['sum_bet'] * $COMISSION);

            $user_id_win = $assoc['user_create_id'];

            $QUERY_UPDATE_1 = $db->Query_recordless("UPDATE `games_schulte_tab` SET `id_user_win` = '$user_id_win', `date_user_2_end` = NOW() WHERE `id` = '$assoc[id]'");
            $QUERY_UPDATE_2 = $db->Query_recordless("UPDATE `users_data` SET `balance_buy` = (`balance_buy` + '$assoc[sum_bet]') WHERE `uid` = '$user_id_win'");

            @mysqli_free_result($QUERY_UPDATE_1);
            @mysqli_free_result($QUERY_UPDATE_2);
        }
    }
}



$db->Query("SELECT * FROM `users` WHERE `id` = $_SESSION[id]");
$row_users = $db->NumRows();
if ( !empty($row_users) ) {
	$as_u = $db->FetchAssoc();
//--VVVV---Заработано в сёрфинге    
    $db->Query("SELECT `earn_money` FROM `user_seen_surf_list` WHERE `uid` = $_SESSION[id]");
    $row_u_s_s_l = $db->NumRows();
    if ( !empty($row_u_s_s_l) ) {
        $as_u_s_s_l = $db->FetchAssoc();
    }
    else {
        $as_u_s_s_l['earn_money'] = 0;
    }

//--VVVV---сумма пополнений
    $db->Query("SELECT SUM(`money_payin`) AS `sum_payin` FROM `history_money_payin` WHERE `uid` = $_SESSION[id]"); //
    $numrows_sum_payin = $db->NumRows();
    if ( !empty($numrows_sum_payin) ) {
       $as_money_payin = $db->FetchAssoc();
    }
    else {
        $as_money_payin['sum_payin'] = 0;
    }
    if ( empty($as_money_payin['sum_payin']) ) {
        $as_money_payin['sum_payin'] = 0;
    }

//--VVVV---сумма выплат
    $db->Query("SELECT SUM(`money_withdrawn`) AS `sum_withdrawn` FROM `history_money_payout` WHERE `uid` = $_SESSION[id]"); //
    $numrows_sum_withdrawn = $db->NumRows();
    if ( !empty($numrows_sum_withdrawn) ) { //условие все равно выполняется, если значение пустое
       $as_money_withdrawn_day = $db->FetchAssoc();
    }
    else {
        $as_money_withdrawn_day['sum_withdrawn'] = 0;
    }
    if ( empty($as_money_withdrawn_day['sum_withdrawn']) ) {
        $as_money_withdrawn_day['sum_withdrawn'] = 0;
    }


    $db->Query("SELECT * FROM `users_stats` WHERE `uid` = $_SESSION[id]");
    $row_users = $db->NumRows();
    if ( !empty($row_users) ) {
        $as_u_s = $db->FetchAssoc();
        $as_u_s['money_earn_refs'] = ($as_u_s['money_earn_refs_1'] + $as_u_s['money_earn_refs_2']);

        $a = $as_u_s['money_earn_mine'];
        $b = $as_u_s['money_earn_refs'];
        
        $d = $as_u_s['getmoney_dailybonus'];
    }
    else {
        $as_u_s['getmoney_dailybonus'] = 0;
        $as_u_s['money_earn_refs'] = 0;
        
        $as_u_s['money_earn_mine'] = 0;
    }

    $db->Query("SELECT SUM(`earn_money`) AS `earn_money` FROM `user_seen_surf_list` WHERE `uid` = $_SESSION[id]");
    $row = $db->NumRows();
    if ( !empty($row) ) {
    	$as_e_s = $db->FetchAssoc();
    	$as_u_s['money_earn_surfing'] = $as_e_s['earn_money'];
    	$c = $as_u_s['money_earn_surfing'];
        if ($as_u_s['money_earn_surfing'] == NULL) {
            $as_u_s['money_earn_surfing'] = 0;
            $c = $as_u_s['money_earn_surfing'] = 0;
        }
    }

    if ($as_u_s['expend_to_mine'] == 0 and $as_u_s['money_earn_refs'] == 0 and $as_u_s['money_earn_surfing'] == 0 and $as_u_s['getmoney_dailybonus'] == 0) {
        $a = $b = $c = $d = 1;
    }

//--VVVV---Количество рефералов
    $db->Query("SELECT COUNT(`id`) AS `referals_id` FROM `users` WHERE (`ref` = '$_SESSION[id]' AND `ref_lvl_2` = '$_SESSION[id]') OR (`ref` = '$_SESSION[id]' OR `ref_lvl_2` = '$_SESSION[id]')");
    $row_count_u = $db->NumRows();
    if ( !empty($row_count_u) ) {
        $as_count_u = $db->FetchAssoc();
    }
    else {
        $as_count_u['referals_id'] = 0;
    }
//--VVVV---разработано рудников
    $db->Query("SELECT COUNT(`id`) AS `mine_id` FROM `users_amount_mine` WHERE `uid` = '$_SESSION[id]'");
    $row_count_u_a_m = $db->NumRows();
    if ( !empty($row_count_u_a_m) ) {
        $as_count_u_a_m = $db->FetchAssoc();
    }
    else {
        $as_count_u_a_m['mine_id'] = 0;
    }
//--VVVV---кликов в сёрвинг
    $db->Query("SELECT COUNT(`id`) AS `surf_click_id` FROM `user_seen_surf_list` WHERE `uid` = '$_SESSION[id]'");
    $row_count_u_s_s_l = $db->NumRows();
    if ( !empty($row_count_u_s_s_l) ) {
        $as_count_u_s_s_l = $db->FetchAssoc();
    }
    else {
        $as_count_u_s_s_l['surf_click_id'] = 0;
    }

//--VVVV---Меня пригласил
    $db->Query("SELECT `id`, `login` FROM `users` WHERE `id` = '$as_u[ref]'");
    $row_u_ref = $db->NumRows();
    if ( !empty($row_u_ref) ) {
        $as_u_ref = $db->FetchAssoc();
    }
    else {
        $as_u_ref['login'] = 'Админ';
    }
   

	$time_in_project = intval( (time() - strtotime($as_u['date_reg'])) / 3600 / 24 );		//intval - возвращает целое число (без округления)
    $link_http_host = stristr($_SERVER['HTTP_REFERER'], '/', true ).'//'.$_SERVER['HTTP_HOST'];

	echo '

		<div class="box_strokeafter_2_center_panel">
			<div class="box_1_center_panel">
				<nav class="nav_stroke_3_box_1_center_panel">
					<ul class="ul_elementgradient_1_stroke_3_box_1_center_panel">
						<li>
							<div class="elementgradient_1_stroke_3_box_1_center_panel">';                                      
    echo '
								<div class="text-1-elementgradient_1_stroke_3_box_1_center_panel">
									кол-во рефералов
								</div>
								<div class="text-2-elementgradient_1_stroke_3_box_1_center_panel">
									'.$as_count_u['referals_id'].' <span>чел</span>
								</div>';

								/*<div class="text-3-elementgradient_1_stroke_3_box_1_center_panel">
									<a href="/prostats">Больше, чем у <span style="color: #fff">100%</span></a>
								</div>/*----BETA----*/
    echo '                            
							</div>
						</li>
						<li>
							<div class="elementgradient_2_stroke_3_box_1_center_panel">
								<div class="text-1-elementgradient_2_stroke_3_box_1_center_panel">
									сумма выплат
								</div>
								<div class="text-2-elementgradient_2_stroke_3_box_1_center_panel">
									'.$as_money_withdrawn_day['sum_withdrawn'].' <span>руб</span>
								</div>';

								/*<div class="text-3-elementgradient_1_stroke_3_box_1_center_panel">
									<a href="/prostats">Больше, чем у <span style="color: #fff">0%</span></a>
								</div>/*----BETA----*/
    echo '         
							</div>
						</li>
						<li>
							<div class="elementgradient_3_stroke_3_box_1_center_panel">
								<div class="text-1-elementgradient_3_stroke_3_box_1_center_panel">
									кликов в сёрфинге
								</div>
								<div class="text-2-elementgradient_3_stroke_3_box_1_center_panel">
									'.$as_count_u_s_s_l['surf_click_id'].' <span>шт</span>
								</div>';

								/*<div class="text-3-elementgradient_1_stroke_3_box_1_center_panel">
									<a href="/prostats">Больше, чем у <span style="color: #fff">0%</span></a>
								</div>/*----BETA----*/
    echo '
							</div>
						</li>								
					</ul>
				</nav>

                <div class="container_datatable">
                    <div class="container_datatable_part1">
    					<div class="element_1_stroke_4_box_1_center_panel">
    						<div class="titletext_e1s4b1cp">
                                 Данные аккаунта
                            </div>
    						<table class="table_element_1_stroke_4_box_1_center_panel">
    							<tr>
    								<td>
    									<div class="text1_table_element_1_stroke_4_box_1_center_panel">
    										Имя: </div>
    									<div class="text2_table_element_1_stroke_4_box_1_center_panel">
    										'.htmlspecialchars($as_u['Name']).'
    									</div>
    								</td>						
    							</tr>
    							<tr>
    								<td>
    									<div class="text1_table_element_1_stroke_4_box_1_center_panel">
    										Логин: </div>
    									<div class="text2_table_element_1_stroke_4_box_1_center_panel">
    										'.htmlspecialchars($as_u['login']).'
    									</div>
    								</td>
    							</tr>
    							<tr>
    								<td>
    									<div class="text1_table_element_1_stroke_4_box_1_center_panel">
    										E-mail: </div>
    									<div class="text2_table_element_1_stroke_4_box_1_center_panel">
    										'.htmlspecialchars($as_u['email']).'
    									</div>
    								</td>
    							</tr>
    							<tr>
    								<td>
    									<div class="text1_table_element_1_stroke_4_box_1_center_panel">
    										ID в системе: </div>
    									<div class="text2_table_element_1_stroke_4_box_1_center_panel">
    										'.$_SESSION['id'].'
    									</div>
    								</td>						
    							</tr>
    							<tr>
    								<td>
    									<div class="text1_table_element_1_stroke_4_box_1_center_panel">
    										Меня пригласил: </div>
    									<div class="text2_table_element_1_stroke_4_box_1_center_panel">
    										'.$as_u_ref['login'].'
    									</div>
    								</td>						
    							</tr>
    							<tr>
    								<td>
    									<div class="text1_table_element_1_stroke_4_box_1_center_panel">
    										Время на проекте: </div>
    									<div class="text2_table_element_1_stroke_4_box_1_center_panel">
    										'.htmlspecialchars($time_in_project).' дн.
    									</div>
    								</td>						
    							</tr>
    						</table>
                            <a href="/setting_account"><button id="button_after_tab_setting" class="button_after_tab">
                                <img id="setting_icon_button" class="icon_button" src="../img/auth/my_cabinet/setting_icon_button.png" style="display: block" width="15px" height="15px">
                                <img id="setting_icon_button_02" class="icon_button" src="../img/auth/my_cabinet/setting_icon_button_02.png" style="display: none" width="15px" height="15px">
                                Настройки аккаунта
                            </button></a>
    					</div>

    					<div class="element_2_stroke_4_box_1_center_panel">
                            <div class="titletext_e2s4b1cp">
    						  Статистика аккаунта
                            </div>
    						<table class="table_element_2_stroke_4_box_1_center_panel">
    							<tr>
    								<td>
    									<div class="text1_table_element_1_stroke_4_box_1_center_panel">
    										Сумма пополнений: </div>
    									<div class="text2_table_element_1_stroke_4_box_1_center_panel">
    										'.$as_money_payin['sum_payin'].' ₽
    									</div>
    								</td>						
    							</tr>
    							<tr>
    								<td>
    									<div class="text1_table_element_1_stroke_4_box_1_center_panel">
    										Сумма выплат: </div>
    									<div class="text2_table_element_1_stroke_4_box_1_center_panel">
    										'.$as_money_withdrawn_day['sum_withdrawn'].' ₽
    									</div>
    								</td>						
    							</tr>
    							<tr>
    								<td>
    									<div class="text1_table_element_1_stroke_4_box_1_center_panel">
    										Доход с рефералов: </div>
    									<div class="text2_table_element_1_stroke_4_box_1_center_panel">
    										'.$as_u_s['money_earn_refs'].' ₽
    									</div>
    								</td>						
    							</tr>
    							<tr>
    								<td>
    									<div class="text1_table_element_1_stroke_4_box_1_center_panel">
    										Сделано кликов: </div>
    									<div class="text2_table_element_1_stroke_4_box_1_center_panel">
    										'.$as_count_u_s_s_l['surf_click_id'].'
    									</div>
    								</td>						
    							</tr>
    							<tr>
    								<td>
    									<div class="text1_table_element_1_stroke_4_box_1_center_panel">
    										Кол-во рефералов: </div>
    									<div class="text2_table_element_1_stroke_4_box_1_center_panel">
    										'.$as_count_u['referals_id'].'
    									</div>
    								</td>						
    							</tr>
    							<tr>
    								<td>
    									<div class="text1_table_element_1_stroke_4_box_1_center_panel">
    										Прокачано персонажей: </div>
    									<div class="text2_table_element_1_stroke_4_box_1_center_panel">
    										'.$as_count_u_a_m['mine_id'].'
    									</div>
    								</td>						
    							</tr>
    						</table>

                            <a href="/user_wall?id='.$_SESSION['id'].'"><button id="button_after_tab_userwall" class="button_after_tab">
                                <img id="userwall_icon_button" class="icon_button" src="../img/auth/my_cabinet/userwall_icon_button.png" width="15px" height="15px" style="display: block">
                                <img id="userwall_icon_button_02" class="icon_button" src="../img/auth/my_cabinet/userwall_icon_button_02.png" width="15px" height="15px" style="display: none"> 
                                Стена пользователя
                            </button></a>
    					</div>
                    </div>
                    
                    <!--<div class="linkslot">
                        <div class="linkslot_banner" id="linkslot_288196"><script src="https://linkslot.ru/bancode.php?id=288196" async></script></div>

                        <center><a href="https://linkslot.ru/link.php?id=288197" target="_blank" rel="noopener">Купить ссылку здесь за <span id="linprice_288197"></span> руб.</a><div id="linkslot_288197" style="margin: 10px 0;"><script src="https://linkslot.ru/lincode.php?id=288197" async></script></div></center>
                    </div> -->

                    
                </div>
			</div>
			<div class="box_2_center_panel">

				<div class="elementgradient_1-z2-4_stroke_3_box_2_center_panel">
				</div>
                <div class="centerer_textelementgradient_1">
                	<p class="textelementgradient_1-z2-4_stroke_3_box_2_center_panel">
    					<span>Ваша реферальная ссылка</span> <a href="/partneract">что это?</a>
    				</p>
                </div>
				
				<div class="box_elementgradient_2-z2-4_stroke_3_box_2_center_panel">
                    <div class="centerer_centerer">
    					<div class="elementgradient_2-z2-4_stroke_3_box_2_center_panel">                                      
        					<p class="textelementgradient_2-z2-4_stroke_3_box_2_center_panel">
        						'.$link_http_host.'/?g='.$_SESSION['id'].'
        					</p>
                        </div>
                    </div>
				</div>				

				<div class="elementgradient-z1-4_stroke_3_box_2_center_panel">
				</div>

				<div class="diagramma_round_box_2_center_panel">
                    <h2>График по типам заработка</h2>
                    <div id="graph"></div>
                    <pre id="code" class="prettyprint linenums" style="display: none;"> <!-- Убрать отображение кода, но оставить результат его работы -->
                        Morris.Donut({
                            element: "graph",
                            data: [
                            {value: '.$a.', label: "'.$as_u_s['money_earn_mine'].' руб.", formatted: "Прокачка персонажей" },
                            {value: '.$b.', label: "'.$as_u_s['money_earn_refs'].' руб.", formatted: "Доходы с рефералов" },
                            {value: '.$c.', label: "'.$as_u_s['money_earn_surfing'].' руб.", formatted: "Сёрфинг сайтов" },
                            {value: '.$d.', label: "'.$as_u_s['getmoney_dailybonus'].' руб.", formatted: "Сбор бонусов" }
                            ],
                            formatter: function (x, data) { return data.formatted; }
                        });
                    </pre>

    
				</div>

			</div>
		</div>
	';

	bottom_auth();
}
else {
	exit(header('Location: /'));
}
?>