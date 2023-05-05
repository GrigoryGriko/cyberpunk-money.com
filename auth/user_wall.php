<?php
    header("Content-Type: text/html; charset=utf-8");  // стандартная установка кодировки
?>
<?php

if ($_POST['login'] and !empty($_POST['login']) and $_POST['login'] != '') {
    $db->Query("SELECT `id`, `login` FROM `users` WHERE `login` = '$_POST[login]'");
    $num_u = $db->NumRows();
    if ( !empty($num_u) ) {
        $as_u = $db->FetchAssoc();
        $_GET['id'] = $as_u['id'];
    }
}
if (!$_GET['id']) $_GET['id'] = $_SESSION['id'];

top_auth('Стена пользователя', 'auth/style/user_wallstyle', false, 'user_wall');

$db->Query("SELECT * FROM `users` WHERE `id` = $_GET[id]");
$row_users = $db->NumRows();
if ( !empty($row_users) ) {
	$as_u = $db->FetchAssoc();
//--VVVV---Заработано в сёрфинге    
    $db->Query("SELECT `earn_money` FROM `user_seen_surf_list` WHERE `uid` = $_GET[id]");
    $row_u_s_s_l = $db->NumRows();
    if ( !empty($row_u_s_s_l) ) {
        $as_u_s_s_l = $db->FetchAssoc();
    }
    else {
        $as_u_s_s_l['earn_money'] = 0;
    }

    $db->Query("SELECT * FROM `users_stats` WHERE `uid` = $_GET[id]");
    $row_users = $db->NumRows();
    if ( !empty($row_users) ) {
        $as_u_s = $db->FetchAssoc();
        $as_u_s['money_earn_refs'] = ($as_u_s['money_earn_refs_1'] + $as_u_s['money_earn_refs_2']);

    }
    else {
        $as_u_s['money_payin'] = 0;
        $as_u_s['money_payout'] = 0;
        $as_u_s['getmoney_dailybonus'] = 0;
        $as_u_s['money_earn_refs'] = 0;
        $as_u_s['money_earn_surfing'] = 0;
        $as_u_s['money_earn_mine'] = 0;
    }



    if ($as_u_s['expend_to_mine'] == 0 and $as_u_s['money_earn_refs'] == 0 and $as_u_s['money_earn_surfing'] == 0 and $as_u_s['getmoney_dailybonus'] == 0) {
        $a = $b = $c = $d = 1;
    }

//--VVVV---Количество рефералов
    $db->Query("SELECT COUNT(`id`) AS `referals_id` FROM `users` WHERE (`ref` = '$_GET[id]' AND `ref_lvl_2` = '$_GET[id]') OR (`ref` = '$_GET[id]' OR `ref_lvl_2` = '$_GET[id]')");
    $row_count_u = $db->NumRows();
    if ( !empty($row_count_u) ) {
        $as_count_u = $db->FetchAssoc();
    }
    else {
        $as_count_u['referals_id'] = 0;
    }
//--VVVV---разработано рудников
    $db->Query("SELECT COUNT(`id`) AS `mine_id` FROM `users_amount_mine` WHERE `uid` = '$_GET[id]'");
    $row_count_u_a_m = $db->NumRows();
    if ( !empty($row_count_u_a_m) ) {
        $as_count_u_a_m = $db->FetchAssoc();
    }
    else {
        $as_count_u_a_m['mine_id'] = 0;
    }
//--VVVV---кликов в сёрвинг
    $db->Query("SELECT COUNT(`id`) AS `surf_click_id` FROM `user_seen_surf_list` WHERE `uid` = '$_GET[id]'");
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
        $as_u_ref['login'] = 'Никто';
    }
   
    $db->Query("SELECT SUM(`money_withdrawn`) AS `sum_payout` FROM `history_money_payout` WHERE `uid` = '$_GET[id]'"); //
    $numrows_sum_payout = $db->NumRows();
    $as_money_sum_payout = $db->FetchAssoc();
    if ($as_money_sum_payout['sum_payout'] == NULL) {   
        $as_money_sum_payout['sum_payout'] = 0;   //Сумма выплат
    }

	$time_in_project = intval( (time() - strtotime($as_u['date_reg'])) / 3600 / 24 );		//intval - возвращает целое число (без округления)
    $link_http_host = stristr($_SERVER['HTTP_REFERER'], '/', true ).'//'.$_SERVER['HTTP_HOST'];

	echo '

		<div class="box_strokeafter_2_center_panel">
			<div class="box_1_center_panel">
				<ul class="ul_elementgradient_1_stroke_3_box_1_center_panel">
					<li>
						<div class="elementgradient_1_stroke_3_box_1_center_panel">

                            <img src="../img/auth/wall/wallet.png">
							<div class="text-1-elementgradient_1_stroke_3_box_1_center_panel">
								сумма выплат
							</div>
							<div class="text-2-elementgradient_1_stroke_3_box_1_center_panel">
								'.$as_money_sum_payout['sum_payout'].' <span>руб</span>
							</div>

						</div>
					</li>
					<li>
						<div class="elementgradient_2_stroke_3_box_1_center_panel">

                            <img src="../img/auth/wall/team.png">
							<div class="text-1-elementgradient_2_stroke_3_box_1_center_panel">
								количество рефералов
							</div>
							<div class="text-2-elementgradient_2_stroke_3_box_1_center_panel">
								'.$as_count_u['referals_id'].' <span>чел</span>
							</div>

						</div>
					</li>
					<li>
						<div class="elementgradient_3_stroke_3_box_1_center_panel">

                            <img src="../img/auth/wall/group.png">
							<div class="text-1-elementgradient_3_stroke_3_box_1_center_panel">
								доход с рефералов
							</div>
							<div class="text-2-elementgradient_3_stroke_3_box_1_center_panel">
								'.$as_u_s['money_earn_refs'].' <span>руб.</span>
							</div>

						</div>
					</li>								
				</ul>';

    $db->Query("SELECT * FROM `users` WHERE `id` = '$_GET[id]'");
    $row_u = $db->NumRows();
    if ( !empty($row_u) ) {
        $as_u = $db->FetchAssoc();
    }
    $db->Query("SELECT `id`, `login` FROM `users` WHERE `id` = '$as_u[ref]'");
    $row_u = $db->NumRows();
    if ( !empty($row_u) ) {
        $as_u_r = $db->FetchAssoc();
    }
    

//--VVVV---Количество рефералов
    $db->Query("SELECT COUNT(`id`) AS `referals_id` FROM `users` WHERE (`ref` = '$_GET[id]' AND `ref_lvl_2` = '$_GET[id]') OR (`ref` = '$_GET[id]' OR `ref_lvl_2` = '$_GET[id]')");
    $row_count_u = $db->NumRows();
    if ( !empty($row_count_u) ) {
        $as_count_u = $db->FetchAssoc();
    }
    else {
        $as_count_u['referals_id'] = 0;
    }
//--VVVV---разработано рудников
    $db->Query("SELECT COUNT(`id`) AS `mine_id` FROM `users_amount_mine` WHERE `uid` = '$_GET[id]'");
    $row_count_u_a_m = $db->NumRows();
    if ( !empty($row_count_u_a_m) ) {
        $as_count_u_a_m = $db->FetchAssoc();
    }
    else {
        $as_count_u_a_m['mine_id'] = 0;
    }
//--VVVV---кликов в сёрвинг
    $db->Query("SELECT COUNT(`id`) AS `surf_click_id` FROM `user_seen_surf_list` WHERE `uid` = '$_GET[id]'");
    $row_count_u_s_s_l = $db->NumRows();
    if ( !empty($row_count_u_s_s_l) ) {
        $as_count_u_s_s_l = $db->FetchAssoc();
    }
    else {
        $as_count_u_s_s_l['surf_click_id'] = 0;
    }        

//--VVVV---доход с рефералов
    $db->Query("SELECT * FROM `users_stats` WHERE `uid` = $_GET[id]");
    $row_users = $db->NumRows();
    if ( !empty($row_users) ) {
        $as_u_s = $db->FetchAssoc();
        $as_u_s['money_earn_refs'] = ($as_u_s['money_earn_refs_1'] + $as_u_s['money_earn_refs_2']);
    }
    else {
        $as_u_s['money_earn_refs'] = 0;
    }

//--VVVV---потрачено на рекламу (серфинг)        
    $db->Query("SELECT SUM(`spending_stats`) AS `spending_stats` FROM `user_addsurf_sites` WHERE `uid` = '$_GET[id]'"); //
    $numrows_s_s = $db->NumRows();
    if ( empty($numrows_s_s) ) { //условие все равно выполняется, если значение пустое
       $as_money_s_s = $db->FetchAssoc();
    }
    else {
        $as_money_s_s['spending_stats'] = 0;  
    }
    if ($as_money_s_s['spending_stats'] == '') $as_money_s_s['spending_stats'] = 0;


    echo '
				<div class="box_data">
                    <div class="container_r">
                        <div class="stroke_e">
                            <div class="side">
                                <div class="one">Имя:</div>
                                <div class="two">'.htmlspecialchars($as_u['Name']).'</div>
                            </div>
                            <div class="side">
                                <div class="one">Реферер:</div>';

    if ($as_u_r['id'] == 1 or $as_u_r['id'] == 0) {
        echo '                  <div class="two">'.htmlspecialchars($as_u_r['login']).'</div>';
    }

    else  {
        echo '
                                <div class="two"><a href="/user_wall?id='.$as_u_r['id'].'">'.$as_u_r['login'].'</a></div>';
    }
    if ( $as_u['origin_url'] == '') {
        $as_u['origin_url'] = 'нет данных';
    }

    echo '                      </div>
                        </div>

                        <div class="stroke_e">
                            <div class="side">
                                <div class="one">Логин:</div>
                                <div class="two">'.$as_u['login'].'</div>
                            </div>
                            <div class="side">
                                <div class="one">ID в системе:</div>
                                <div class="two">'.$as_u['id'].'</div>
                            </div>
                        </div>

                        <div class="stroke_e">
                            <div class="side">
                                <div class="one">Сумма выплат:</div>
                                <div class="two">'.$as_money_sum_payout['sum_payout'].' руб.</div>
                            </div>
                            <div class="side">
                                <div class="one">Сделано кликов в сёрфинге:</div>
                                <div class="two">'.$as_count_u_s_s_l['surf_click_id'].'</div>
                            </div>
                        </div>

                        <div class="stroke_e">
                            <div class="side">
                                <div class="one">Количество рефералов:</div>
                                <div class="two">'. $as_count_u['referals_id'].'</div>
                            </div>
                            <div class="side">
                                <div class="one">Доход с рефералов:</div>
                                <div class="two">'.$as_u_s['money_earn_refs'].' руб.</div>
                            </div>
                        </div>

                        <div class="stroke_e">
                            <div class="side">
                                <div class="one">Прокачано персонажей:</div>
                                <div class="two">'.$as_count_u_a_m['mine_id'].'</div>
                            </div>
                            <div class="side">
                                <div class="one">Потрачено на рекламу:</div>
                                <div class="two">'.$as_money_s_s['spending_stats'].'</div>
                            </div>
                        </div>

                        <div class="stroke_e">
                            <div class="side">
                                <div class="one">Откуда пришел:</div>
                                <div class="two">'.$as_u['origin_url'].'</div>
                            </div>
                            <div class="side">
                                
                            </div>
                        </div>


                        

                    </div>
                </div>

                <div class="block_title">
                    <img class="user_icon" src="../img/auth/wall/user_icon.png" width="11" height="12">
                    Персонажей в Найт-Сити '.$as_u['login'].':
                </div>';

/*______________V______________Рудники______________V______________*/
    $db->Query("SELECT * FROM `users_amount_mine` WHERE `uid` = '$_GET[id]' ORDER BY `date_level_update` DESC, `date_buy` DESC");
    $row_users_amount_mine = $db->NumRows();
    if ( !empty($row_users_amount_mine) ) {
    echo '
                <nav class="nav_element_1_stroke_6_box_1_center_panel">
                    <ul id="ul_ajax-element_1_stroke_6_box_1_center_panel">';

        /*$as_u_a_m = $assoc_users_amount_mine--------обозначения*/
        while ( $as_u_a_m = $db->FetchAssoc() ) {   /*Отображение рудников, купленных пользователем*/

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
                                    <img class="fromsql_strokeimage-e1s6b1cp" src="../img/auth/my_field/'.$as_u_a_m['image_name'].'" width="190px">';

            echo '
                            </div>                      
                        </li>';
        }
        echo '  
                    </ul>                               
                </nav>';
    }
    else {
        echo '
                <nav class="nav_element_1_stroke_6_box_1_center_panel empty">
                    
                </nav>';
    }
/*______________A______________Рудники______________A______________*/

    echo '
			</div>
			<div class="box_2_center_panel">
                <form class="search_block" method="POST" action="/user_wall">
				    <input class="search_input input_button_style" id="user_login" name="login" type="text" placeholder="Введите логин пользователя">
                    <input type="submit" id="search_button" class="set_button input_button_style" value="Поиск">
                </form>

                <div class="user_avatar">
                    <div class="container_r">';
    
    $db->Query("SELECT * FROM `users_data` WHERE `uid` = '$_GET[id]'");                 
    $NumRows_users_data = $db->NumRows();
    if ( !empty($NumRows_users_data) ) {
        $assoc_users_data = $db->FetchAssoc();

        if ( file_exists($assoc_users_data['name_image_avatar']) ) {
            echo '
                        <img src="../'.$assoc_users_data['name_image_avatar'].'">';
        }
        else {
            echo '  
                        <img src="../img/auth/home/avatar.png">';
        }
    }
    else {
        echo '  
                        <img src="../img/auth/home/avatar.png">';
    }


    echo '
                    </div>';

    $db->Query("SELECT * FROM `online_users` WHERE `uid` = '$_GET[id]'");                 
    $num_rows = $db->NumRows();

    if ( !empty($num_rows) ) {
        echo '
                        <div class="online">
                            online
                        </div>';
    }
    else {
        echo '
                        <div class="offline">
                            offline
                        </div>';
    } 
    echo '               
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