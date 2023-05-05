<?php
    header("Content-Type: text/html; charset=utf-8");  // стандартная установка кодировки
?>
<?php
include_once 'setting.php'; //подключение файла с поролями
session_start();

if ( !isset($_SESSION['origURL']) ) {
	$_SESSION['origURL'] = $_SERVER["HTTP_REFERER"];
}

if ( is_numeric($_GET['g']) or is_string($_GET['g']) ) {
	setcookie('g', $_GET['g'], strtotime('+1 week'));
	header('location: '.$page.''); //регистрация реферала
}
else if ( is_numeric($_GET['ref']) or is_string($_GET['ref']) ) {
	setcookie('g', $_GET['ref'], strtotime('+1 week'));
	header('location: '.$page.''); //регистрация реферала
}


if ( is_numeric($_GET['it']) ) {
	$_SESSION['it'] = $_GET['it'];
	header('location: /surf_grab'); //регистрация реферала
}


function __autoload($name){ include("classes/_class.".$name.".php");}
$config = new config;

$db = new db($config->HostDB, $config->UserDB, $config->PassDB, $config->BaseDB);
//$CONNECT = mysqli_connect(HostDB, UserDB, PassDB, BaseDB); //Подключение к БД из setting.php

if ($_SERVER['REQUEST_URI'] == '/') {
$page = 'home';
$_SESSION['page'] = $page;

$Page = 'index';
$_SESSION['Page'] = $Page;

$Module = 'index';
$_SESSION['Module'] = $Module;
} 
else {
$page = substr($_SERVER['REQUEST_URI'], 1);
$_SESSION['page'] = $page;		//Возможно помещать в $_SESSION не нужно
//if ( !preg_match('/^[A-z0-9]{3,15}$/', $page) ) not_found();  //---запретить ненужные символы в URL

$URL_Path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$URL_Parts = explode('/', trim($URL_Path, ' /'));
$Page = array_shift($URL_Parts);
$_SESSION['Page'] = $Page;		//Возможно помещать в $_SESSION не нужно

$Module = array_shift($URL_Parts);
$_SESSION['Module'] = $Module;		//Возможно помещать в $_SESSION не нужно
	if (!empty($Module)) {
		$Param = array();
		for ($i = 0; $i < count($URL_Parts); $i++) {
		$_SESSION['$Param'][$URL_Parts[$i]] = $Param[$URL_Parts[$i]] = $URL_Parts[++$i]; 	//Возможно помещать в $_SESSION не нужно
		}
	}
	else if ($Page == 'news' or $Page == 'loads') {
	$Module = 'main';
	$_SESSION['Module'] = $Module;		//Возможно помещать в $_SESSION не нужно
	}					//Чтобы работал модуль новостей
}

if ( !isset($_SESSION['USER_LOGIN_IN']) ) {
    $_SESSION['USER_LOGIN_IN'] = false;
}

$data_developer ='
<hr>
	Данные разработчика:<br>
	SESSION id ='.$_SESSION['USER_LOGIN_IN'].'
<hr>';


if (!$db) {
	exit('MySQl ОШИБКААА!');
}


function go_auth_cookie($data/*, $data2*//*(1)связана*/) { //для бывалых
	foreach ($data as $key => $value) {
		$_SESSION[$key] = $value;
	}
	/*if ($data2) {
		while ( $row2 = mysqli_fetch_assoc($data2) ) {
			$num = $row2['category'];
			$_SESSION['amount'][$num] = $row2['amount'];
		}
	}*/	/*(1)связана*/
	global $db;
	$db->Query("UPDATE `users` SET `date_login` = NOW() WHERE `id` = '$_SESSION[id]'");
	exit(header('Location: /my_cabinet'));
}

if ($_SESSION['USER_LOGIN_IN'] != 1 and $_COOKIE['user'] and ($_COOKIE['userl'] or $_COOKIE['userm']) ) {
	
	if ($_COOKIE['userl'] and !$_COOKIE['userm']) {
		$cookie_login_or_email = $_COOKIE['userl'];
		$row_login_or_email = 'login';
	}
	else if ($_COOKIE['userm'] and !$_COOKIE['userl']) {
		$cookie_login_or_email = $_COOKIE['userm'];
		$row_login_or_email = 'email';
	}
	else {
		setcookie('userl', '', strtotime('-30 days'), '/');
		unset($_COOKIE['userl']);
		
		$cookie_login_or_email = $_COOKIE['userm'];
		$row_login_or_email = 'email';
	}

	$db->Query("SELECT `{$row_login_or_email}` FROM `users` WHERE `{$row_login_or_email}` = '$cookie_login_or_email'");
	$row_loginemail = $db->NumRows();
	if ( empty($row_loginemail) ) {
	    setcookie('userm', '', strtotime('-30 days'), '/');
	    setcookie('user', '', strtotime('-30 days'), '/');
	    unset($_COOKIE['userm']);
	    unset($_COOKIE['user']);

	    header('location: /');
	}
	else {
		$db->Query("SELECT `password` FROM `users` WHERE `{$row_login_or_email}` = '$cookie_login_or_email' AND `password` = '$_COOKIE[user]'");
		$row_password = $db->NumRows();
		if ( empty($row_password) ) {
			setcookie('userm', '', strtotime('-30 days'), '/');
		    setcookie('user', '', strtotime('-30 days'), '/');
		    unset($_COOKIE['userm']);
		    unset($_COOKIE['user']);

		    header('location: /');
		}
		else {
			$db->Query("SELECT * FROM `users` WHERE `{$row_login_or_email}` = '$cookie_login_or_email'");
			$row = $db->FetchAssoc();

			/*$db->Query("SELECT * FROM `time_metasom` WHERE `umail` = '$_COOKIE[userm]'");
			$query2 = $this->LastQuery; */

			$db->Query('INSERT INTO `logs_authorization` VALUES (NULL, "'.$row['id'].'", "'.$cookie_login_or_email.'", "succes COOKIE ", NOW(), 1, "'.define_IP().'")');		//лог авторизации

			$_SESSION['USER_LOGIN_IN'] = 1;
			go_auth_cookie($row/*, $query2*//*(1)связана*/);
		}
	}
}


if ( file_exists('all/'.$page.'.php') ) include 'all/'.$page.'.php';


else if ($page == 'dailybonus' and file_exists('auth/dailybonus.php') ) include 'auth/dailybonus.php';    //вседоступность страницы
else if ($page == 'schulte_tab_start' and file_exists('auth/games/schulte_tab_start.php') ) include 'auth/games/schulte_tab_start.php';
else if ($page == 'games' and file_exists('auth/games.php') ) include 'auth/games.php';
else if ($page == 'free_chance' and file_exists('auth/free_chance.php') ) include 'auth/free_chance.php';
else if (file_exists('auth/free_chance/'.$page.'.php') ) include 'auth/free_chance/'.$page.'.php';

else if (substr($_SERVER['REQUEST_URI'], 1, 19) == 'schulte_tab_start?') include 'auth/games/schulte_tab_start.php';
else if (substr($_SERVER['REQUEST_URI'], 1, 11) == 'dailybonus?') include 'auth/dailybonus.php';

else if (substr($_SERVER['REQUEST_URI'], 1, 1) == '?') include 'all/home.php';

else if ( $_SESSION['USER_LOGIN_IN'] and file_exists('auth/'.$page.'.php') ) include 'auth/'.$page.'.php';


else if ( $_SESSION['USER_LOGIN_IN'] and file_exists('auth/games/'.$page.'.php') ) include 'auth/games/'.$page.'.php';
else if ( $_SESSION['USER_LOGIN_IN'] and file_exists('auth/games/request/'.$page.'.php') ) include 'auth/games/'.$page.'.php';

else if ( $_SESSION['USER_LOGIN_IN'] and file_exists('auth/games/events/'.$page.'.php') ) include 'auth/games/events/'.$page.'.php';
else if ( $_SESSION['USER_LOGIN_IN'] and substr($_SERVER['REQUEST_URI'], 1, 20) == 'mining_ways_(event)?') include 'auth/games/events/mining_ways_(event).php';
else if ( $_SESSION['USER_LOGIN_IN'] and substr($_SERVER['REQUEST_URI'], 1, 20) == 'schulte_tab_(event)?') include 'auth/games/events/schulte_tab_(event).php';
else if ( $_SESSION['USER_LOGIN_IN'] and file_exists('auth/games/events/request/'.$page.'.php') ) include 'auth/games/events/request/'.$page.'.php';
else if ( $_SESSION['USER_LOGIN_IN'] and file_exists('auth/games/events/ajax/'.$page.'.php') ) include 'auth/games/events/ajax/'.$page.'.php';


else if ( $_SESSION['USER_LOGIN_IN'] and file_exists('auth/pay/'.$page.'.php') ) include 'auth/pay/'.$page.'.php';
else if (file_exists('all/payment/'.$page.'.php') ) include 'all/payment/'.$page.'.php';
else if (file_exists('all/cron/'.$page.'.php') ) include 'all/cron/'.$page.'.php';
else if (file_exists('all/payment/event/'.$page.'.php') ) include 'all/payment/event/'.$page.'.php';
else if ( !$_SESSION['USER_LOGIN_IN'] and file_exists('auth/'.$page.'.php') ) not_acces(); //доступ только для авторизованных
else if ( $_SESSION['USER_LOGIN_IN'] and file_exists('guest/'.$page.'.php') ) header('location: /my_cabinet');

else if (file_exists('all/admin/'.$page.'.php') ) include 'all/admin/'.$page.'.php';

else if ( !$_SESSION['USER_LOGIN_IN'] and file_exists('guest/'.$page.'.php') ) include 'guest/'.$page.'.php';


else if (file_exists('all/admin/'.$page.'.php') ) include 'all/admin/'.$page.'.php';
else if (file_exists('all/admin/action/'.$page.'.php') ) include 'all/admin/action/'.$page.'.php';


else if (file_exists('all/padman/'.$page.'.php') ) include 'all/padman/'.$page.'.php';
else if ( $_SESSION['padman'] and file_exists('padman/'.$page.'.php') ) include 'padman/'.$page.'.php';


else if ( $_SESSION['USER_LOGIN_IN'] and substr($_SERVER['REQUEST_URI'], 1, 10) == 'user_wall?') include 'auth/user_wall.php';  //разрешает доступ к страницt с get параетром
else if (substr($_SERVER['REQUEST_URI'], 1, 8) == 'confirm?') include 'guest/confirm.php';  //разрешает доступ к страницt с get параметром
else if (substr($_SERVER['REQUEST_URI'], 1, 11) == 'allconfirm?') include 'all/allconfirm.php';  //разрешает доступ к страницt с get параетром

//---запрос оплаты---VVVV
/*
СТАВИТЬ ТОЛЬКО ПОСЛЕ условий с ...file_exists('auth/'.$page.'.php') )*/
else if (substr($_SERVER['REQUEST_URI'], 1, 16) == 'payment/success?') include 'all/payment/success.php';
else if (substr($_SERVER['REQUEST_URI'], 1, 21) == 'payment/successfree?') include 'all/payment/successfree.php';
else if (substr($_SERVER['REQUEST_URI'], 1, 13) == 'payment/fail?') include 'all/payment/fail.php';

else if (substr($_SERVER['REQUEST_URI'], 1, 21) == 'payment/event/payeer?') include 'all/payment/event/payeer.php';
else if (substr($_SERVER['REQUEST_URI'], 1, 24) == 'payment/event/freekassa?') include 'all/payment/event/freekassa.php';

else if (substr($_SERVER['REQUEST_URI'], 1, 8) == 'request?') include 'all/admin/action/request.php';

/*---запрос оплаты---LLLL
else if ( $_SESSION['USER_LOGIN_IN'] and file_exists('auth/payment/'.$page) ) include 'auth/payment/'.$page;
else if ( $_SESSION['USER_LOGIN_IN'] and file_exists('auth/payment/event/'.$page) ) include 'auth/payment/event/'.$page;
*/



/* +-------------прописывание ссылок с директориями---------*/
else if ( $_SESSION['id'] and $Page == 'my_field' and in_array( $_SESSION['Module'], array('minebuy') ) ) {
	include("auth/mine_shop/actions/$_SESSION[Module].php");
}

else if ( $_SESSION['id'] and stristr($_SESSION['page'], '?', true) == 'mine' and $_GET['id'] ) {
	include("auth/mine.php");
}
/*
//Выплата на кошелек---VVV---
else if ( $_SESSION['USER_LOGIN_IN'] and $Page == 'wofmoney' and in_array( $Module, array('ps_yandex_money', 'ps_qiwi', 'ps_payeer') ) and $Param['get'] == 'money') {
	include("auth/pay/actions/getmoney.php");
}
else if ( $_SESSION['USER_LOGIN_IN'] and $Page == 'wofmoney' and in_array( $Module, array('ps_yandex_money', 'ps_qiwi', 'ps_payeer') ) ) {
	include("auth/pay/ps/$Module.php");
}
*/
//Выплата на кошелек---LLL---

//Пополнение---VVV---
else if ( $_SESSION['USER_LOGIN_IN'] and $Page == 'payment' and in_array( $Module, array('freekassa_pay', 'payeer_pay') ) and $Param['pay'] == 'money') {
	include("auth/pay/actions/pay_money.php");
}
else if ( $_SESSION['USER_LOGIN_IN'] and $Page == 'payment' and in_array( $Module, array('freekassa_pay', 'payeer_pay') ) ) {
	include("auth/pay/ps/$Module.php");
}
//Пополнение---LLL---

/*else if ( $_SESSION['admin'] and $Page == 'home_a' and in_array( $Module, array('withdraw_confirm', 'wofmoney_confirm') ) ) {
	include("admin/actions/$Module.php");
}Админка*/


/*----------------*/

else not_found();



function message( $text, $url_u = false, $close_u = false, $status = false) {	//$url - перенаправление на сайт; $close - закрытие вкладки
	exit('{ 
		"message" : "'.$text.'", 
		"url_u" : "'.$url_u.'",
		 "close_u" : "'.$close_u.'",
         "status" : "'.$status.'"
		}');
}

function MessageSend($p1, $p2 = '', $p3 = 1) {
	$_SESSION['message'] = '<div class="MessageBlock">'.$p1.'</div>';
	if ($p3) {
		if ($p2) $_SERVER['HTTP_REFERER'] = $p2;
		exit(header('Location: '.$_SERVER['HTTP_REFERER']));
	}
}
function MessageSendBlue($p1, $p2 = '', $p3 = 1) {
	$_SESSION['message'] = '<div class="MessageBlockBlue">'.$p1.'</div>';
	if ($p3) {
		if ($p2) $_SERVER['HTTP_REFERER'] = $p2;
		exit(header('Location: '.$_SERVER['HTTP_REFERER']));
	}
}



function MessageShow() {
if ($_SESSION['message']) $Message = $_SESSION['message'];
echo $Message;
$_SESSION['message'] = array();
}

function define_IP() {
$client  = @$_SERVER['HTTP_CLIENT_IP'];
			$forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
			$remote  = @$_SERVER['REMOTE_ADDR'];
			 
			if(filter_var($client, FILTER_VALIDATE_IP)) $ip = $client;  
			else if(filter_var($forward, FILTER_VALIDATE_IP)) $ip = $forward;
			else $ip = $remote;
return $ip;
}

function go( $url ) {
	exit('{ "go" : "'.$url.'"}');
}

function random_str($num=30) {
	return substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, $num);
}

function not_acces() {
	header("Content-Type: text/html; charset=utf-8");
	/*exit(
		'
		<!DOCTYPE html>
		<html>
		<head>
		<meta charset="UTF-8">
		<title>404.Страница не существует</title>
		<link rel="stylesheet" href="../error_404.css">
		<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
		<head>
			<body>
				<div class="centerer">
					<div class="container">
						<div class="text_1">Эй кот! Стой!</div>
						<div class="text_2">Страница доступна для авторизованных пользователей.</div>
						<div class="text_3">Для начала перейдите на <a href="/login">Страницу авторизации</a></div>
					</div>
					<img src="../img/access.png" width="225px" height="225px">
				</div>
			</body>
		</head>
		'
	);*/
	exit(header('Location: /login'));
}
function not_found() {
    header("Content-Type: text/html; charset=utf-8");  // стандартная установка кодировки
	exit(
		'
		<!DOCTYPE html>
		<html>
		<head>
		<meta charset="UTF-8">
		<title>404.Страница не существует</title>
		<link rel="stylesheet" href="../error_404.css">
		<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
		<head>
			<body>
				<div class="centerer">
					<div class="container">
						<div class="text_1">404 Страница не найдена</div>
						<div class="text_2">Возможно она выиграла много денег и свалила в Вегас.</div>
						<div class="text_3">Ну а вы пока можете вернуться на <a href="/">главную страницу</a></div>
					</div>
					<img src="../img/404.png" width="259px" height="260px">
				</div>
			</body>
		</head>
		'
	);
}


/*function chudo_parsing($link_site) {
    $name_link = substr($link_site, 8, 30);
   
    require_once 'simple_html_dom.php';
    $html = new simple_html_dom();
    $html->load_file($link_site);
    

   
    foreach($html->find('link') as $s=>$css) {
        $html->find('link', $s)->href = $link_site.$css->href;
    }
    foreach($html->find('a') as $a=>$alink) {
        $html->find('a', $a)->href = $link_site.$alink->href;
    }
        
    foreach($html->find('img') as $k=>$img) {
        $find_img = $html->find('img', $k)->src;
        if ( strripos($find_img, $name_link) == false ) {  
            $html->find('img', $k)->src = $link_site.$img->src;
        }
    }
   	$html->show();
}
*/

function captcha_valid() {
	global $db;
	if ( is_numeric($_POST['captcha']) ) {
		if ($_POST['captcha'] >= 0) {
			if ( strlen($_POST['captcha'] < 11) ) {
				

				$half_time_mix = ceil( time()/3600/12 );
				$crypto_input = hash('sha256', $_POST['captcha'].'g'.$half_time_mix);	//шифрованное введенное значение в input

				$crypto_input_for_not = hash('sha256', (5 - $_POST['captcha']).'g'.$half_time_mix);	//шифрованное введенное значение в input для not

				if ( $_SESSION['captcha_rid'] == hash('sha256', '0g'.$half_time_mix ) ) {	//rid-dir..ection
					if ( $_SESSION['captcha_tonse'] == hash('sha256', 'yesg'.$half_time_mix) ) {	//tonse-yesnot
						if ($crypto_input != $_SESSION['captcha1']) {		//input - число, введенное в поле
				        	$_SESSION['message_surfing'] = 'Капча введена неверно, просмотр не засчитан';
				        	return 'denied';
					    }
					}
					else if ( $_SESSION['captcha_tonse'] == hash('sha256', 'notg'.$half_time_mix) ) {
						if ($crypto_input_for_not != $_SESSION['captcha1']) {	//captcha1-up, 2-left, 3-down, 4-right				
				        	$_SESSION['message_surfing'] = 'Капча введена неверно, просмотр не засчитан';
				        	return 'denied';
					    }
					}
					else {
						message('ERROR not exist0x01, просмотр не засчитан', false, false, 'error');
					}
				}

				else if ( $_SESSION['captcha_rid'] == hash('sha256', '90g'.$half_time_mix) ) {
					if ( $_SESSION['captcha_tonse'] == hash('sha256', 'yesg'.$half_time_mix) ) {
						if ($crypto_input != $_SESSION['captcha2']) {			        	
				        	$_SESSION['message_surfing'] = 'Капча введена неверно, просмотр не засчитан';
				        	return 'denied';
					    }
					}
					else if ( $_SESSION['captcha_tonse'] == hash('sha256', 'notg'.$half_time_mix) ) {
						if ($crypto_input_for_not != $_SESSION['captcha2']) {				        	
				        	$_SESSION['message_surfing'] = 'Капча введена неверно, просмотр не засчитан';
				        	return 'denied';
					    }
					}
					else {
						$_SESSION['message_surfing'] = 'ERROR not exist0x01, просмотр не засчитан';
				        return 'denied';
					}
				}

				else if ( $_SESSION['captcha_rid'] == hash('sha256', '180g'.$half_time_mix) ) {
					if ( $_SESSION['captcha_tonse'] == hash('sha256', 'yesg'.$half_time_mix) ) {
						if ($crypto_input != $_SESSION['captcha3']) {				        	
				        	$_SESSION['message_surfing'] = 'Капча введена неверно, просмотр не засчитан';
				        	return 'denied';
					    }
					}
					else if ( $_SESSION['captcha_tonse'] == hash('sha256', 'notg'.$half_time_mix) ) {
						if ($crypto_input_for_not != $_SESSION['captcha3']) {				        	
				        	$_SESSION['message_surfing'] = 'Капча введена неверно, просмотр не засчитан';
				        	return 'denied';
					    }
					}
					else {
						$_SESSION['message_surfing'] = 'ERROR not exist0x01, просмотр не засчитан';
				        return 'denied';
					}
				}
				else if ( $_SESSION['captcha_rid'] == hash('sha256', '270g'.$half_time_mix) ) {
					if ( $_SESSION['captcha_tonse'] == hash('sha256', 'yesg'.$half_time_mix) ) {
						if ($crypto_input != $_SESSION['captcha4']) {				        	
				        	$_SESSION['message_surfing'] = 'Капча введена неверно, просмотр не засчитан';
				        	return 'denied';
					    }
					}
					else if ( $_SESSION['captcha_tonse'] == hash('sha256', 'notg'.$half_time_mix) ) {
						if ($crypto_input_for_not != $_SESSION['captcha4']) {
				        	$_SESSION['message_surfing'] = 'Капча введена неверно, просмотр не засчитан';
				        	return 'denied';
					    }
					}
					else {
						$_SESSION['message_surfing'] = 'ERROR not exist0x01, просмотр не засчитан';
				        return 'denied';
					}
				}
				else {
					$_SESSION['message_surfing'] = 'ERROR not exist, просмотр не засчитан';
				    return 'denied';
				}
			}
			else {
				$_SESSION['message_surfing'] = 'Число больше 10, просмотр не засчитан';
				return 'denied';
			}
		}
		else {
			$_SESSION['message_surfing'] = 'Число меньше 0, просмотр не засчитан';
			return 'denied';
		}
	}
	else {
		$_SESSION['message_surfing'] = 'Вы не ввели число, просмотр не засчитан';
		return 'denied';
	}	
}

/*
function captcha_valid() {
	global $db;
	if ( is_numeric($_POST['captcha']) ) {
		if ($_POST['captcha'] >= 0) {
			if ( strlen($_POST['captcha'] < 11) ) {
				

				$half_time_mix = ceil( time()/3600/12 );
				$crypto_input = hash('sha256', $_POST['captcha'].'g'.$half_time_mix);	//шифрованное введенное значение в input

				$crypto_input_for_not = hash('sha256', (5 - $_POST['captcha']).'g'.$half_time_mix);	//шифрованное введенное значение в input для not

				if ( $_SESSION['captcha_rid'] == hash('sha256', '0g'.$half_time_mix ) ) {	//rid-dir..ection
					if ( $_SESSION['captcha_tonse'] == hash('sha256', 'yesg'.$half_time_mix) ) {	//tonse-yesnot
						if ($crypto_input != $_SESSION['captcha1']) {		//input - число, введенное в поле
				        	$_SESSION['message_surfing'] = 'Капча введена неверно, просмотр не засчитан';
				        	return 'denied';
					    }
					}
					else if ( $_SESSION['captcha_tonse'] == hash('sha256', 'notg'.$half_time_mix) ) {
						if ($crypto_input_for_not != $_SESSION['captcha1']) {	//captcha1-up, 2-left, 3-down, 4-right				
				        	$_SESSION['message_surfing'] = 'Капча введена неверно, просмотр не засчитан';
				        	return 'denied';
					    }
					}
					else {
						message('ERROR not exist0x01, просмотр не засчитан', false, false, 'error');
					}
				}

				else if ( $_SESSION['captcha_rid'] == hash('sha256', '90g'.$half_time_mix) ) {
					if ( $_SESSION['captcha_tonse'] == hash('sha256', 'yesg'.$half_time_mix) ) {
						if ($crypto_input != $_SESSION['captcha2']) {			        	
				        	$_SESSION['message_surfing'] = 'Капча введена неверно, просмотр не засчитан';
				        	return 'denied';
					    }
					}
					else if ( $_SESSION['captcha_tonse'] == hash('sha256', 'notg'.$half_time_mix) ) {
						if ($crypto_input_for_not != $_SESSION['captcha2']) {				        	
				        	$_SESSION['message_surfing'] = 'Капча введена неверно, просмотр не засчитан';
				        	return 'denied';
					    }
					}
					else {
						$_SESSION['message_surfing'] = 'ERROR not exist0x01, просмотр не засчитан';
				        return 'denied';
					}
				}

				else if ( $_SESSION['captcha_rid'] == hash('sha256', '180g'.$half_time_mix) ) {
					if ( $_SESSION['captcha_tonse'] == hash('sha256', 'yesg'.$half_time_mix) ) {
						if ($crypto_input != $_SESSION['captcha3']) {				        	
				        	$_SESSION['message_surfing'] = 'Капча введена неверно, просмотр не засчитан';
				        	return 'denied';
					    }
					}
					else if ( $_SESSION['captcha_tonse'] == hash('sha256', 'notg'.$half_time_mix) ) {
						if ($crypto_input_for_not != $_SESSION['captcha3']) {				        	
				        	$_SESSION['message_surfing'] = 'Капча введена неверно, просмотр не засчитан';
				        	return 'denied';
					    }
					}
					else {
						$_SESSION['message_surfing'] = 'ERROR not exist0x01, просмотр не засчитан';
				        return 'denied';
					}
				}
				else if ( $_SESSION['captcha_rid'] == hash('sha256', '270g'.$half_time_mix) ) {
					if ( $_SESSION['captcha_tonse'] == hash('sha256', 'yesg'.$half_time_mix) ) {
						if ($crypto_input != $_SESSION['captcha4']) { /*-----------------чекпоинт-------------------*/		/*		        	
				        	$_SESSION['message_surfing'] = 'Капча введена неверно, просмотр не засчитан';
				        	return 'denied';
					    }
					}
					else if ( $_SESSION['captcha_tonse'] == hash('sha256', 'notg'.$half_time_mix) ) {
						if ($crypto_input_for_not != $_SESSION['captcha4']) {
				        	$_SESSION['message_surfing'] = 'Капча введена неверно, просмотр не засчитан';
				        	return 'denied';
					    }
					}
					else {
						$_SESSION['message_surfing'] = 'ERROR not exist0x01, просмотр не засчитан';
				        return 'denied';
					}
				}
				else {
					$_SESSION['message_surfing'] = 'ERROR not exist, просмотр не засчитан';
				    return 'denied';
				}
			}
			else {
				$_SESSION['message_surfing'] = 'Число больше 10, просмотр не засчитан';
				return 'denied';
			}
		}
		else {
			$_SESSION['message_surfing'] = 'Число меньше 0, просмотр не засчитан';
			return 'denied';
		}
	}
	else {
		$_SESSION['message_surfing'] = 'Вы не ввели число, просмотр не засчитан';
		return 'denied';
	}	
}*/

function captcha_valid_login() {
	global $db;
	if ( is_numeric($_POST['captcha']) ) {
		if ($_POST['captcha'] >= 0) {
			if ( strlen($_POST['captcha'] < 11) ) {
				

				$half_time_mix = ceil( time()/3600/12 );
				$crypto_input = hash('sha256', $_POST['captcha'].'g'.$half_time_mix);	//шифрованное введенное значение в input

				$crypto_input_for_not = hash('sha256', (5 - $_POST['captcha']).'g'.$half_time_mix);	//шифрованное введенное значение в input для not

				if ( $_SESSION['captcha_rid'] == hash('sha256', '0g'.$half_time_mix ) ) {	//rid-dir..ection
					if ( $_SESSION['captcha_tonse'] == hash('sha256', 'yesg'.$half_time_mix) ) {	//tonse-yesnot
						if ($crypto_input != $_SESSION['captcha1']) {		//input - число, введенное в поле
							//$db->Query('INSERT INTO `logs_authorization` VALUES (NULL, 0, "'.$_POST['email'].'", "Капча введена неверно", NOW(), 0, "'.define_IP().'")');
				        	message('Капча введена неверно, посмотрите внимательнее', false, false, 'warning');
					    }
					}
					else if ( $_SESSION['captcha_tonse'] == hash('sha256', 'notg'.$half_time_mix) ) {
						if ($crypto_input_for_not != $_SESSION['captcha1']) {	//captcha1-up, 2-left, 3-down, 4-right
							//$db->Query('INSERT INTO `logs_authorization` VALUES (NULL, 0, "'.$_POST['email'].'", "Капча введена неверно", NOW(), 0, "'.define_IP().'")');
				        	message('Капча введена неверно, посмотрите внимательнее', false, false, 'warning');
					    }
					}
					else {
						message('ERROR not exist0x01', false, false, 'error');
					}
				}

				else if ( $_SESSION['captcha_rid'] == hash('sha256', '90g'.$half_time_mix) ) {
					if ( $_SESSION['captcha_tonse'] == hash('sha256', 'yesg'.$half_time_mix) ) {
						if ($crypto_input != $_SESSION['captcha2']) {
				        	//$db->Query('INSERT INTO `logs_authorization` VALUES (NULL, 0, "'.$_POST['email'].'", "Капча введена неверно", NOW(), 0, "'.define_IP().'")');
				        	message('Капча введена неверно, посмотрите внимательнее', false, false, 'warning');
					    }
					}
					else if ( $_SESSION['captcha_tonse'] == hash('sha256', 'notg'.$half_time_mix) ) {
						if ($crypto_input_for_not != $_SESSION['captcha2']) {
				        	//$db->Query('INSERT INTO `logs_authorization` VALUES (NULL, 0, "'.$_POST['email'].'", "Капча введена неверно", NOW(), 0, "'.define_IP().'")');
				        	message('Капча введена неверно, посмотрите внимательнее', false, false, 'warning');
					    }
					}
					else {
						message('ERROR not exist0x02', false, false, 'error');
					}
				}

				else if ( $_SESSION['captcha_rid'] == hash('sha256', '180g'.$half_time_mix) ) {
					if ( $_SESSION['captcha_tonse'] == hash('sha256', 'yesg'.$half_time_mix) ) {
						if ($crypto_input != $_SESSION['captcha3']) {
				        	//$db->Query('INSERT INTO `logs_authorization` VALUES (NULL, 0, "'.$_POST['email'].'", "Капча введена неверно", NOW(), 0, "'.define_IP().'")');
				        	message('Капча введена неверно, посмотрите внимательнее', false, false, 'warning');
					    }
					}
					else if ( $_SESSION['captcha_tonse'] == hash('sha256', 'notg'.$half_time_mix) ) {
						if ($crypto_input_for_not != $_SESSION['captcha3']) {
				        	//$db->Query('INSERT INTO `logs_authorization` VALUES (NULL, 0, "'.$_POST['email'].'", "Капча введена неверно", NOW(), 0, "'.define_IP().'")');
				        	message('Капча введена неверно, посмотрите внимательнее', false, false, 'warning');
					    }
					}
					else {
						message('ERROR not exist0x03', false, false, 'error');
					}
				}
				else if ( $_SESSION['captcha_rid'] == hash('sha256', '270g'.$half_time_mix) ) {
					if ( $_SESSION['captcha_tonse'] == hash('sha256', 'yesg'.$half_time_mix) ) {
						if ($crypto_input != $_SESSION['captcha4']) { /*-----------------чекпоинт-------------------*/
				        	//$db->Query('INSERT INTO `logs_authorization` VALUES (NULL, 0, "'.$_POST['email'].'", "Капча введена неверно", NOW(), 0, "'.define_IP().'")');
				        	message('Капча введена неверно, посмотрите внимательнее', false, false, 'warning');
					    }
					}
					else if ( $_SESSION['captcha_tonse'] == hash('sha256', 'notg'.$half_time_mix) ) {
						if ($crypto_input_for_not != $_SESSION['captcha4']) {
				        	//$db->Query('INSERT INTO `logs_authorization` VALUES (NULL, 0, "'.$_POST['email'].'", "Капча введена неверно", NOW(), 0, "'.define_IP().'")');
				        	message('Капча введена неверно, посмотрите внимательнее', false, false, 'warning');
					    }
					}
					else {
						message('ERROR not exist0x04', false, false, 'error');
					}
				}
				else {
					message('ERROR not exist', false, false, 'error');
				}


			}
			else {
				//$db->Query('INSERT INTO `logs_authorization` VALUES (NULL, 0, "'.$_POST['email'].'", "Капча. Попытка ввода много символов", NOW(), 0, "'.define_IP().'")');
	    		message('Введите в капчу число не больше 10', false, false, 'warning');
			}
		}
		else {
			//$db->Query('INSERT INTO `logs_authorization` VALUES (NULL, 0, "'.$_POST['email'].'", "Капча. Введено отрицательное число", NOW(), 0, "'.define_IP().'")');
	    	message('Число в капче должно быть больше либо равно 0', false, false, 'warning');
		}
	}
	else {
		//$db->Query('INSERT INTO `logs_authorization` VALUES (NULL, 0, "'.$_POST['email'].'", "Капча. ШАЙТАН ввел тест в чисовое поле", NOW(), 0, "'.define_IP().'")');
	    message('В поле капчи должно быть введено число', false, false, 'warning');
	}	
}
function captcha_valid_logless() {
global $db;
	if ( is_numeric($_POST['captcha']) ) {
		if ($_POST['captcha'] >= 0) {
			if ( strlen($_POST['captcha'] < 11) ) {			
				$half_time_mix = ceil( time()/3600/12 );
				$crypto_input = hash('sha256', $_POST['captcha'].'g'.$half_time_mix);	//шифрованное введенное значение в input

				$crypto_input_for_not = hash('sha256', (5 - $_POST['captcha']).'g'.$half_time_mix);	//шифрованное введенное значение в input для not

				if ( $_SESSION['captcha_rid'] == hash('sha256', '0g'.$half_time_mix ) ) {	//rid-dir..ection
					if ( $_SESSION['captcha_tonse'] == hash('sha256', 'yesg'.$half_time_mix) ) {	//tonse-yesnot
						if ($crypto_input != $_SESSION['captcha1']) {		//input - число, введенное в поле
				        	message('Капча введена неверно, посмотрите внимательнее', false, false, 'warning');
					    }
					}
					else if ( $_SESSION['captcha_tonse'] == hash('sha256', 'notg'.$half_time_mix) ) {
						if ($crypto_input_for_not != $_SESSION['captcha1']) {	//captcha1-up, 2-left, 3-down, 4-right
				        	message('Капча введена неверно, посмотрите внимательнее', false, false, 'warning');
					    }
					}
					else {
						message('ERROR not exist0x11', false, false, 'error');
					}
				}

				else if ( $_SESSION['captcha_rid'] == hash('sha256', '90g'.$half_time_mix) ) {
					if ( $_SESSION['captcha_tonse'] == hash('sha256', 'yesg'.$half_time_mix) ) {
						if ($crypto_input != $_SESSION['captcha2']) {
				        	message('Капча введена неверно, посмотрите внимательнее', false, false, 'warning');
					    }
					}
					else if ( $_SESSION['captcha_tonse'] == hash('sha256', 'notg'.$half_time_mix) ) {
						if ($crypto_input_for_not != $_SESSION['captcha2']) {
				        	message('Капча введена неверно, посмотрите внимательнее', false, false, 'warning');
					    }
					}
					else {
						message('ERROR not exist0x12', false, false, 'error');
					}
				}

				else if ( $_SESSION['captcha_rid'] == hash('sha256', '180g'.$half_time_mix) ) {
					if ( $_SESSION['captcha_tonse'] == hash('sha256', 'yesg'.$half_time_mix) ) {
						if ($crypto_input != $_SESSION['captcha3']) {
				        	message('Капча введена неверно, посмотрите внимательнее', false, false, 'warning');
					    }
					}
					else if ( $_SESSION['captcha_tonse'] == hash('sha256', 'notg'.$half_time_mix) ) {
						if ($crypto_input_for_not != $_SESSION['captcha3']) {
				        	message('Капча введена неверно, посмотрите внимательнее', false, false, 'warning');
					    }
					}
					else {
						message('ERROR not exist0x13', false, false, 'error');
					}
				}
				else if ( $_SESSION['captcha_rid'] == hash('sha256', '270g'.$half_time_mix) ) {
					if ( $_SESSION['captcha_tonse'] == hash('sha256', 'yesg'.$half_time_mix) ) {
						if ($crypto_input != $_SESSION['captcha4']) { /*-----------------чекпоинт-------------------*/
				        	message('Капча введена неверно, посмотрите внимательнее', false, false, 'warning');
					    }
					}
					else if ( $_SESSION['captcha_tonse'] == hash('sha256', 'notg'.$half_time_mix) ) {
						if ($crypto_input_for_not != $_SESSION['captcha4']) {
				        	message('Капча введена неверно, посмотрите внимательнее', false, false, 'warning');
					    }
					}
					else {
						message('ERROR not exist0x14', false, false, 'error');
					}
				}
				else {
					message('ERROR not exist', false, false, 'error');
				}


			}
			else {
	    		message('Введите в капчу число не больше 10', false, false, 'warning');
			}
		}
		else {
	    	message('Число в капче должно быть больше либо равно 0', false, false, 'warning');
		}
	}
	else {
	    message('В поле капчи должно быть введено число', false, false, 'warning');
	}
}

function email_valid() {
	global $db;

	if (!$_POST['email']) {
		message('Не указан E-mail', false, false, 'info');
	}
	else if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
		message('Неправильно указан E-mail', false, false, 'info');
	}
}
function login_valid() {
	global $db;

	if (!$_POST['login']) {
		message('Логин не указан', false, false, 'info');
	}
	else if (!preg_match('/^[A-z0-9]{3,14}$/', $_POST['login'])) {
		message('Неправильно указан логин, он должен содержать от 3 до 14 латинских символов и/или цифр', false, false, 'info');
	}
}
function Login_or_Email_valid() {
	global $db;

	if (!$_POST['Login_or_Email']) {
		//$db->Query('INSERT INTO `logs_authorization` VALUES (NULL, 0, "'.$_POST['Login_or_Email'].'", "Пустой логин или email valid", NOW(), 0, "'.define_IP().'")');
		message('Логин или email не указан', false, false, 'info');
	}
	else if ( strripos($_POST['Login_or_Email'], '@') == false ) {		//возвращает позицию последнего вхождения символа в строке
		global $login_vs_email;
		$login_vs_email = 'login';
		if (!preg_match('/^[A-z0-9]{3,14}$/', $_POST['Login_or_Email'])) {
			//$db->Query('INSERT INTO `logs_authorization` VALUES (NULL, 0, "'.substr($_POST['Login_or_Email'], 0, 15).'", "Логин или email не по форме valid", NOW(), 0, "'.define_IP().'")');
			message('Неправильно указан логин, он должен содержать от 3 до 14 латинских символов и/или цифр', false, false, 'info');
		}
	}
	else if ( strripos($_POST['Login_or_Email'], '@') != false ) {		//возвращает позицию последнего вхождения символа в строке
		global $login_vs_email;
		$login_vs_email = 'email';
		if ( !filter_var($_POST['Login_or_Email'], FILTER_VALIDATE_EMAIL) ) {
			//$db->Query('INSERT INTO `logs_authorization` VALUES (NULL, 0, "'.substr($_POST['Login_or_Email'], 0, 45).'", "Email не по форме valid", NOW(), 0, "'.define_IP().'")');
			message('Неправильно указан E-mail', false, false, 'info');
		}
	}
	else {
        message('ErRoR_imposible', false, false, 'error');
    }
}

function password_valid($Register_or_Login) {
	global $db;

	switch($Register_or_Login) {
		case 'register':
			$name_table_in_db = 'logs_registration';
			break;
		case 'login':
			$name_table_in_db = 'logs_authorization';
			break;
		case 'logs_recovery':
			$name_table_in_db = 'logs_recovery';
			break;
        default:
            $name_table_in_db = 'log_less';
            break;
	}
    if ($name_table_in_db != 'log_less') {
    	if (!$_POST['password']) {
    		$db->Query('INSERT INTO `'.$name_table_in_db.'` VALUES (NULL, 0, "'.$_POST['Login_or_Email'].'", "Пустой пароль valid", NOW(), 0, "'.define_IP().'")');
    		message('Не указан пароль', false, false, 'info');
    	}
    	else if (!preg_match('/^[A-z0-9]{6,30}$/', $_POST['password'])) {
    		$db->Query('INSERT INTO `'.$name_table_in_db.'` VALUES (NULL, 0, "'.$_POST['Login_or_Email'].'", "Пароль не по форме valid", NOW(), 0, "'.define_IP().'")');
    		message('Неправильно указан пароль, он должен содержать от 6 до 30 латинских символов и/или цифр', false, false, 'info');
    	}
    	else {
    		if ($Register_or_Login == 'login') {
    			$_POST['password'] = hash(sha256, $_POST['password']);
    		}
    		else {
    			if ($_POST['retype_password'] != $_POST['password']) {
    				message('Пароли не совпадают', false, false, 'info');
    			}
    			else {
    				$_POST['password'] = hash(sha256, $_POST['password']);
    			}
    		}
    	}
    }
    else {
        if (!$_POST['password']) {
            message('Не указан пароль', false, false, 'info');
        }
        else if (!preg_match('/^[A-z0-9]{6,30}$/', $_POST['password'])) {
            message('Неправильно указан пароль, он должен содержать от 6 до 30 латинских символов и/или цифр', false, false, 'info');
        }
        else {
            if ($Register_or_Login == 'login') {
                $_POST['password'] = hash(sha256, $_POST['password']);
            }
            else {
                if ($_POST['retype_password'] != $_POST['password']) {
                    message('Пароли не совпадают', false, false, 'info');
                }
                else {
                    $_POST['password'] = hash(sha256, $_POST['password']);
                }
            }
        }
    }
}

//-------------vvvvvvvvvvvvvv------------функция для отображения страницы




function top_guest($title, $style, $JavaScript = false) {	//вначале скрипт изменяет value checkbox, для его отправки по ajax
	header("Content-Type: text/html; charset=utf-8");  // стандартная установка кодировки
    global $db;

	echo '<!DOCTYPE html>
	<html>
	<head>
	<meta charset="UTF-8">
	<meta name="description" content="Экономический симулятор с выводом реальных денег!">
	<meta name="Keywords" content="cyberpunk-money, игры с выводом денег, игры с выводом денег,, экономический симулятор, заработок, заработок в интернете, хайп, хайпы, well-money, mmgp.ru, долгосрочный хайп, инвестиции"> 
	
	<title>'.$title.'</title>
	
	<meta property="og:image" content="/img/bigfav.png" />
	<meta property="og:image:secure_url" content="/img/bigfav.png" />
	<meta property="og:image:type" content="image/png" />
	<meta property="og:image:width" content="100px" />
	<meta property="og:image:height" content="100px" />
	<meta property="og:image:alt" content="Cyberpunk-Money.com - экономический симулятор" />

	<link rel="stylesheet" href="'.$style.'.css">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
    <script data-ad-client="ca-pub-4624626706857484" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
	<script src="javascript/jquery-1.12.4.js"></script>
    <link rel="stylesheet" type"text/css" href="../all/sweetalert2.css">
    <script type="text/javascript" src="../javascript/sweetalert2.js"></script>
	<script src="javascript/script.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

	<script type="text/javascript">
		$(document).ready(function() {
			$("#checkbox_1").change(function() {
				if ($(this).is(":checked")) {
					$(this).val(1);
				}
				else {
					$(this).val(0);
				}
			});';
	echo '
		});';

		/*--------login.php----------VVVVVVVVVVVVVVVVVVVV----*/
	if ($JavaScript == 'login') {
		echo '
			function ajax_button_login_change_failsuccess() {
				$.get("ajax/ajax_fail_login", function(data) {	//функция получает данные data с файла по директории
				data = $(data);
				$("#button_login_container_ajax").html( $("#button_login_change", data).html() );	//извлечение конкретных балансов из одного файла

				});
			};
			function field_captcha_show() {
				$(".blackout").show();
			};
			function field_captcha_hide() {
				$(".blackout").hide();
			};';
	}
		/*--------login.php----------AAAAAAAAAAAAAAAAAAAA----*/

/*--------------------------prostats_JS----------VVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVV----*/

echo '
        $(document).on("mouseenter", "#button_register", function() {
            setTimeout( function() {
                $("#img_buton_R_01").hide();
                $("#img_buton_R_02").show();
            }, 100);
        });
        $(document).on("mouseleave", "#button_register", function() {
            setTimeout( function() {
                $("#img_buton_R_02").hide();
                $("#img_buton_R_01").show();
            }, 100);
        });

        $(document).on("mouseenter", "#button_login", function() {
            setTimeout( function() {
                $("#img_buton_L_01").hide();
                $("#img_buton_L_02").show();
            }, 100);
        });
        $(document).on("mouseleave", "#button_login", function() {
            setTimeout( function() {
                $("#img_buton_L_02").hide();
                $("#img_buton_L_01").show();
            }, 100);
        });


        $(document).on("mouseenter", "#button_my_account", function() {
            setTimeout( function() {
                $("#img_buton_01").hide();
                $("#img_buton_02").show();
            }, 100);
        });
        $(document).on("mouseleave", "#button_my_account", function() {
            setTimeout( function() {
                $("#img_buton_02").hide();
                $("#img_buton_01").show();
            }, 100);
        });';


    if ($JavaScript == 'prostats') {
        global $as_count_ar;
        global $as_count_total;
        global $date;

        global $date_pay;
        global $as_money_payin;
        global $as_money_payin_day;
        echo'


            /*--------------------------график 1----------VVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVV----*/
            google.charts.load("current", {"packages":["corechart"]});
          google.charts.setOnLoadCallback(drawChart_1);
          function drawChart_1() {
            var data = google.visualization.arrayToDataTable([
              ["Дата", "Резерв", "Пополнено"],
              ["'.$date_pay[6].'", '.$as_money_payin[6]['sum_payin'].', '.$as_money_payin_day[6]['sum_payin'].'],
              ["'.$date_pay[5].'", '.$as_money_payin[5]['sum_payin'].', '.$as_money_payin_day[5]['sum_payin'].'],
              ["'.$date_pay[4].'", '.$as_money_payin[4]['sum_payin'].', '.$as_money_payin_day[4]['sum_payin'].'],
              ["'.$date_pay[3].'", '.$as_money_payin[3]['sum_payin'].', '.$as_money_payin_day[3]['sum_payin'].'],
              ["'.$date_pay[2].'", '.$as_money_payin[2]['sum_payin'].', '.$as_money_payin_day[2]['sum_payin'].'],
              ["'.$date_pay[1].'", '.$as_money_payin[1]['sum_payin'].', '.$as_money_payin_day[1]['sum_payin'].'],
              ["'.$date_pay[0].'", '.$as_money_payin[0]['sum_payin'].', '.$as_money_payin_day[0]['sum_payin'].']
            ]);

            var options = {
              title: "",
              hAxis: {title: "",  titleTextStyle: {color: "#333"}},
              vAxis: {minValue: 0.001},

              width: 500,
              height: 420,
              colors: ["#78D20B", "#37C409"],
            };

            var chart = new google.visualization.AreaChart(document.getElementById("chart_div_1"));
            chart.draw(data, options);
          }

          /*--------------------------график 1----------AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA----*/
          /*--------------------------график 2----------VVVVVVVVVVVVVVVVVVVVVVVVVVVVVVV----*/
          google.charts.load("current", {"packages":["corechart"]});
          google.charts.setOnLoadCallback(drawChart_2);

          function drawChart_2() {
            var data = google.visualization.arrayToDataTable([
              ["Дата", "Новых", "Всего"],
              ["'.$date[6].'", '.$as_count_ar[6]['amount_reg'].', '.$as_count_total[6]['amount_reg'].'],
              ["'.$date[5].'", '.$as_count_ar[5]['amount_reg'].', '.$as_count_total[5]['amount_reg'].'],
              ["'.$date[4].'", '.$as_count_ar[4]['amount_reg'].', '.$as_count_total[4]['amount_reg'].'],
              ["'.$date[3].'", '.$as_count_ar[3]['amount_reg'].', '.$as_count_total[3]['amount_reg'].'],
              ["'.$date[2].'", '.$as_count_ar[2]['amount_reg'].', '.$as_count_total[2]['amount_reg'].'],
              ["'.$date[1].'", '.$as_count_ar[1]['amount_reg'].', '.$as_count_total[1]['amount_reg'].'],
              ["'.$date[0].'", '.$as_count_ar[0]['amount_reg'].', '.$as_count_total[0]['amount_reg'].']
            ]);

            var options = {
              title: "",
              hAxis: {title: "",  titleTextStyle: {color: "#333"}},
              vAxis: {minValue: 0.001},

              width: 500,
              height: 420,
              colors: ["#37C409", "#78D20B"],
            };

            var chart = new google.visualization.AreaChart(document.getElementById("chart_div_2"));
            chart.draw(data, options);
          }
/*--------------------------график 2----------AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA----*/';
    }

/*--------------------------about_JS-help_JS---------VVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVV----*/
    if ($JavaScript == 'about' or $JavaScript == 'help') {   
        echo '
        
        var start_height_css_question = $(".question").css("height");

        function drop_down(element_1, element_2) {

            $(document).on("click", element_1, function () {

                var height_css = $(this).css("height");
                var b_id = $(this).attr("id");

                switch (b_id) {
                    case "b_1":
                        var change_height = "395px";
                        break;
                    case "b_2":
                        var change_height = "150px";
                        break;
                    case "b_3":
                        var change_height = "215px";
                        break;
                }

                if ( height_css == "58px") {
                    $(this).css({"height": change_height});

                    $(this).find(element_2).css({"color": "#23242C"});

                    $(this).find(".img_question_02").hide();
                    $(this).find(".img_question_01").show();

                }
                else {
                    $(this).find(element_2).css({"color": "#4dbaf9"});

                    $(this).css({"height": "58px"});
                    $(this).find(".img_question_01").hide();
                    $(this).find(".img_question_02").show();
                }
            });
        };
        drop_down(".block_q", ".question");';
    }
/*--------------------------about_JS-help_JS---------AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA----*/
    echo '
	</script>
	
	<link rel="icon" href="/img/favicon.png" type="image/png">
	<link rel="shortcut icon" href="/img/favicon.png" type="image/png">
	</head>

	<body>

	';
}
function top_second_guest () {
        echo '
        <div class="wrapper">
            <div class="header_1">
                <div class="container_block_1">
                    <a href="/home"><img class="img_logo" src="../img/home/img_logo_header_1.png"></a>';
        if (!$_SESSION['id']) {
            echo '
                    <div class="container_button">
                        <a href="/login">
                            <button id="button_register" class="button_account">
                                <img id="img_buton_R_01" class="img_button" src="../img/all/prostats/button_account_01.png" width="14px" height="14px">
                                <img id="img_buton_R_02" class="img_button" style="display: none" src="../img/all/prostats/button_account_02.png" width="14px" height="14px">
                                Вход
                            </button>
                        </a>

                        <a href="/register">
                            <button id="button_login" class="button_account">
                                <img id="img_buton_L_01" class="img_button" src="../img/all/prostats/button_register_01.png" width="14px" height="14px">
                                <img id="img_buton_L_02" class="img_button" style="display: none" src="../img/all/prostats/button_register_02.png" width="14px" height="14px">
                                Регистрация
                            </button>
                        </a>
                    </div>';
        }
        else {
            echo '
                    <a href="/my_cabinet">
                        <button id="button_my_account" class="button_account">
                            <img id="img_buton_01" class="img_button" src="../img/all/prostats/button_account_01.png" width="14px" height="14px">
                            <img id="img_buton_02" class="img_button" style="display: none" src="../img/all/prostats/button_account_02.png" width="14px" height="14px">
                            Мой аккаунт
                        </button>
                    </a>';
        }

        echo '                
                </div>
            </div>

            <div class="space_paint_over">
                <div class="header_2">
                    <div class="container_block_2">
                        <a href="/home"><div class="tab">Главная</div></a>
                        <a href="/about"><div class="tab">О проекте</div></a>
                        <a href="/prostats"><div class="tab">Статистика</div></a>
                        <a href="/guaranteed"><div class="tab">Гарантии</div></a>
                        <a href="/contest"><div class="tab">Конкурсы</div></a>
                        <a href="/feedback"><div class="tab">Отзывы</div></a>
                        <a href="/help"><div class="tab">Помощь</div></a>
                    </div>
                </div>
            </div>';
    }

function bottom_guest_second() {
    echo '
                <div id="container_5">
                <div class="element_2_container_5">
                    
                    <div class="wrapping_element_1_container_5">
                        <div class="element_1_container_5">
                            <div class="strokelogo_1_element_1_container_5"> 
                                <img src="../img/home/img_logo_container_5.png" width="226">
                            </div>

                            <div class="strokeimage_2_element_1_container_5"> 
                                экономическая игра
                            </div>

                            <div class="stroketext_3_element_1_container_5">
                                <img src="../img/home/strokeimage_3_element_1_container_5.png" width="30" height="2">
                            </div>
                            
                            <div class="stroketext_4_element_1_container_5">
                                Экономический симулятор Найт-Сити<br>
                                 с возможностью вывода денег.
                            </div>

                            <div class="strokeimagetext_5_element_1_container_5">
                                <img src="../img/home/strokeimagetext_5_element_1_container_5.png" width="9" height="13">
                                <span><b>301 East Fremont Street,
                                </b> Las Vegas,<br> 
                                st. Nevada, USA</span>
                            </div>
                            <div class="strokeimagetext_6_element_1_container_5">
                                <img src="../img/home/strokeimagetext_6_element_1_container_5.png" width="15" height="12">
                                <span>cyberpunkmoney.project@gmail.com</span>
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
                        

							<script type="text/javascript" src="https://vk.com/js/api/openapi.js?167"></script>

							<!-- VK Widget -->
							<div id="vk_groups"></div>
							<script type="text/javascript">
							VK.Widgets.Group("vk_groups", {mode: 4, width: "507", height: "182"}, 204941996);
							</script>


                        </div>

                    <div class="wrapping_element_2_container_5">
                        <div class="element_3_container_5">
                            <div class="sroketext_1_element_3_container_5">
                                наши <b>адреса</b>
                            </div>

                            <div class="srokeimage_2_element_3_container_5">
                                <img src="../img/home/strokeimage_3_element_1_container_5.png" width="30" height="2">
                            </div>
                            <a href="/home"><div class="srokeimagetext_4_element_3_container_5">
                                <div class="img_srokeimagetext_4_element_3_container_5"><img src="../img/home/strokeimagetext_4_element_3_container_5.png" width="12" height="12"></div>
                                <p>cyberpunk-money.com</p>
                            </div></a>
                        </div>
                    </div>

                </div>
                <div class="centerer_for_division_stroke_6_container_1">
                    <div class="division_stroke_5_container_5">
                        <img src="../img/home/division_container_1.png" width="264" height="27">    
                    </div>
                </div>
            </div>
            <footer id="footer_1">
                <div class="element_12_footer_1">';

$year = date("Y"); //Текущий год
echo '          
                    <div class="elementtext_1_footer_1">
                        Copyrights ©'.$year.' <a href="https://cyberpunk-money.com/"><b>cyberpunk-money.com</b></a> All rights reserved.
                    </div>

                    <nav class="elementnav_2_footer_1">
                        <ul class="ul_elementnav_2_footer_1">
                            <li><a href="/home">Главная</a></li>
                            <li><a href="/about">О проекте</a></li>
                            <li><a href="/prostats">Статистика</a></li>    
                            <li><a href="/help">Помощь</a></li>
                            <li><a href="/rules">Правила</a></li>
                        </ul>
                    </nav>

                </div>
            </footer>';
}

function bottom_guest() {
	MessageShow();
echo '

<!-- Анимация кнопки при наведении курсора --!>
<script type="text/javascript">
	$(document).ready(function() {
		
		//---Помощь

		$("#header_help_btn").mouseover(function() {
			$("#header_help_btn").hide();
			$("#header_help_btn2").show();
		});
		$("#header_help_btn").mouseout(function() {
			$("#header_help_btn").show();
			$("#header_help_btn2").hide();
		});

		$("#header_help_btn2").mouseout(function() {
			$("#header_help_btn").show();
			$("#header_help_btn2").hide();
		});
		$("#header_help_btn2").mouseover(function() {
			$("#header_help_btn").hide();
			$("#header_help_btn2").show();
		});

		//---Помощь
		//---Партнерская программа

		$("#header_partners_btn").mouseover(function() {
			$("#header_partners_btn").hide();
			$("#header_partners_btn2").show();
		});
		$("#header_partners_btn").mouseout(function() {
			$("#header_partners_btn").show();
			$("#header_partners_btn2").hide();
		});

		$("#header_partners_btn2").mouseout(function() {
			$("#header_partners_btn").show();
			$("#header_partners_btn2").hide();
		});
		$("#header_partners_btn2").mouseover(function() {
			$("#header_partners_btn").hide();
			$("#header_partners_btn2").show();
		});

		//---Партнерская программа
		//---Правила

		$("#header_rules_btn").mouseover(function() {
			$("#header_rules_btn").hide();
			$("#header_rules_btn2").show();
		});
		$("#header_rules_btn").mouseout(function() {
			$("#header_rules_btn").show();
			$("#header_rules_btn2").hide();
		});

		$("#header_rules_btn2").mouseout(function() {
			$("#header_rules_btn").show();
			$("#header_rules_btn2").hide();
		});
		$("#header_rules_btn2").mouseover(function() {
			$("#header_rules_btn").hide();
			$("#header_rules_btn2").show();
		});

		//---Правила
		//---Регистрация

		$("#register-btn").mouseover(function() {
			$("#register-btn").hide();
			$("#register-btn2").show();
		});
		$("#register-btn").mouseout(function() {
			$("#register-btn").show();
			$("#register-btn2").hide();
		});

		$("#register-btn2").mouseout(function() {
			$("#register-btn").show();
			$("#register-btn2").hide();
		});
		$("#register-btn2").mouseover(function() {
			$("#register-btn").hide();
			$("#register-btn2").show();
		});

		//---Регистрация
		//---Авторизация

		$("#login-btn").mouseover(function() {
			$("#login-btn").hide();
			$("#login-btn2").show();
		});
		$("#login-btn").mouseout(function() {
			$("#login-btn").show();
			$("#login-btn2").hide();
		});

		$("#login-btn2").mouseout(function() {
			$("#login-btn").show();
			$("#login-btn2").hide();
		});
		$("#login-btn2").mouseover(function() {
			$("#login-btn").hide();
			$("#login-btn2").show();
		});

		//---Авторизация
		//---Попробовать бесплатно

		$(".button_try_free").mouseover(function() {
			$(".button_try_free").hide();
			$(".button_try_free2").show();
		});
		$(".button_try_free").mouseout(function() {
			$(".button_try_free").show();
			$(".button_try_free2").hide();
		});

		$(".button_try_free2").mouseout(function() {
			$(".button_try_free").show();
			$(".button_try_free2").hide();
		});
		$(".button_try_free2").mouseover(function() {
			$(".button_try_free").hide();
			$(".button_try_free2").show();
		});

		//---Попробовать бесплатно

	});
</script>
	<!-- Анимация кнопки при наведении курсора --!>

	</body>
	</html>';
}


/*----function for surfsites----------VVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVV-----------------*/

function surfing_ajax_content($param_ajax = false) {
    global $db;
    global $_SESSION;
    $db->Query("SELECT * FROM `user_addsurf_sites` WHERE `banned` = 0 AND `enable` = 1 AND `watch_stats` < `max_count_views` ORDER BY `cost_watch` DESC, `time_add` DESC ");
    $NumRows_list = $db->NumRows();
    if ( !empty($NumRows_list) ) {
	    if ($param_ajax == true) {
	        echo '
	        <div>
	            <div id="container_surfing_list">';
	    }

        while ( $assoc_list = $db->FetchAssoc() ) {
            $QUERY_SELECT_user_seen_surf_list = $db->Query_recordless("SELECT `id` FROM `user_seen_surf_list` WHERE `uid` = '$_SESSION[id]' AND `uas_id` = '$assoc_list[id]'");
            $NumRows_seen_surf = mysqli_num_rows($QUERY_SELECT_user_seen_surf_list);
            if ( empty($NumRows_seen_surf) ) {   //если  в бд задания нет в просмотренных, то выводим его
                @mysqli_free_result($QUERY_SELECT_user_seen_surf_list);

                if ($assoc_list['cost_watch'] >= 0.07) {
                    $class_name_block = 'containerrow_cat1_sb2_cp';
                    $icon_timewatch_name = 'icon_timewatch_1.png';
                }
                else if ($assoc_list['cost_watch'] == 0.06) {
                    $class_name_block = 'containerrow_cat2_sb2_cp';
                    $icon_timewatch_name = 'icon_timewatch_2.png';
                }
                else {
                    $class_name_block = 'containerrow_cat3_sb2_cp';
                    $icon_timewatch_name = 'icon_timewatch_3.png';
                }

                $QUERY_SELECT = $db->Query_recordless("SELECT `balance_advertising` FROM `users_data` WHERE `uid` = '$assoc_list[uid]'");
                $NumRows_balance_ad = mysqli_num_rows($QUERY_SELECT);
                if ( !empty($NumRows_balance_ad) ) {    
                    $assoc_balance_ad = mysqli_fetch_assoc($QUERY_SELECT);     //делать запросом без записи
                    if ($assoc_balance_ad['balance_advertising'] >= $assoc_list['cost_watch']) {

		            if ($assoc_list['max_count_views'] >= 9999999999999) {
		                $views_left = 'неограничено просмотров';
		            }
		            else {
		                $views_left = 'Осталось '.($assoc_list['max_count_views'] - $assoc_list['watch_stats']).' просмотров';
		            }


/*<a href="/surf_grab/?it='.$assoc_list['id'].'" <div class="stroke_1_Lc_containerrow_cat123_sb2_cp" target="_blank">"'.$assoc_list['name_link'].'"</div></a>*/

                        echo '
                            <div class="'.$class_name_block.'">

                                <div class="content_containerrow_cat3_sb2_cp">
                                    <div class="left_content_containerrow_cat123_sb2_cp">
                                        	<div class="stroke_1_Lc_containerrow_cat123_sb2_cp">
                                                <a href="/surf_grab/?it='.$assoc_list['id'].'" target="_blank">
                                                    "'.htmlspecialchars($assoc_list['name_link']).'"
                                                </a>
                                        	</div>
                                        <div class="stroke_2_Lc_containerrow_cat123_sb2_cp">
                                            <div class="left_stroke_2_Lcccat123sb2cp">
                                                <img src="/img/auth/surfsites/'.$icon_timewatch_name.'" width="12px" height="12px">
                                                Время просмотра: '.round($assoc_list['time_watch']).' сек.
                                            </div> 
                                            <div class="right_stroke_2_Lcccat123sb2cp">
                                                <img src="/img/auth/surfsites/icon_earn.png" width="17px" height="10px">
                                                Оплата: '.$assoc_list['cost_watch'].'
                                            </div>
                                        </div>
                                    </div>
                                    <div class="right_content_containerrow_cat123_sb2_cp">';
                        if ($assoc_list['uid'] != $_SESSION['id']) {
                            echo '
                                        <div class="stroke_1_Rc_containerrow_cat123_sb2_cp" id="'.$assoc_list['id'].'">
                                            <div class="text_image_s1Rccc123sb2cp">
                                                <img src="/img/auth/surfsites/icon_report.png" width="15px" height="15px">
                                                <p class="text_report">Жалоба</p>
                                            </div>
                                        </div>';
                        }
                        else {
                            echo
                                        '<div class="stroke_1_Rc_containerrow_cat123_sb2_cp_user">
                                            <div class="raise_task_id s1Rccc123sb2cpu" id="'.$assoc_list['id'].'">Поднять</div>
                                            <a href="/addsurf" class="edit_surfsites s1Rccc123sb2cpu">Редактировать</a>
                                            <div class="start_task_id s1Rccc123sb2cpu" id="'.$assoc_list['id'].'">Остановить</div>
                                        </div>';
                        }
                        echo
                                        '<div class="stroke_2_Rc_containerrow_cat123_sb2_cp">
                                            '.$views_left.'
                                        </div>
                                    </div>
                                </div>                                
                            </div>
                            ';
                    }
                }
                @mysqli_free_result($QUERY_SELECT); //очистка пямяти от запроса 
            }
        }
        if ($param_ajax == true) {
            echo '
                    </div>
                </div>';
        }
    }
    else {
        echo 'Сайтов в сёрфинге пока нет';
    }
}
function addsurf_ajax_content($param_ajax_addsurf = false) {
    global $db;
    global $_SESSION;
    $db->Query("SELECT * FROM `user_addsurf_sites` WHERE `uid` = '$_SESSION[id]' ORDER BY `cost_watch` DESC, `time_add` DESC ");
    $NumRows_list = $db->NumRows();
    if ( !empty($NumRows_list) ) {

    	if ($param_ajax_addsurf == true) {
			echo ' 
					<div>
						<div id="container_surfing_list">';
        }
        $serial_number = 0;
        while ( $assoc_list = $db->FetchAssoc() ) {
            $serial_number += 1;

            if ($assoc_list['max_count_views'] >= 9999999999999) {
                $assoc_list['max_count_views'] = 'неогр.';
                $watch_left = 'неогр.';
            }
            else {
            	$watch_left = $assoc_list['max_count_views'] - $assoc_list['watch_stats'].'';
            }

            if ($assoc_list['enable'] == 1 and $assoc_list['banned'] == 0) {
                $style_staus_task = 'list_box_s2c2sb1cp_green';
                $style_button = 'raise_task_id green';
                $display_none = '';

            }
           	if ($assoc_list['enable'] == 0 and $assoc_list['banned'] == 0) {
                $style_staus_task = 'list_box_s2c2sb1cp_yellow';
                $style_button = 'raise_task_id yellow';
                $display_none = '';
            }
           	if ($assoc_list['banned'] == 1) {
                $style_staus_task = 'list_box_s2c2sb1cp_red';
                $display_none = 'style="display: none"';
            }
            echo '
                            <div class="'.$style_staus_task.'">
	                            <div class="in_block_surfing_list_stroke_1">
	            					<a class="a_in_block_sls" href="'.$assoc_list['url_site'].'">"'.htmlspecialchars($assoc_list['name_link']).'"</a>

	            					';

						            if ($assoc_list['banned'] == 0) {
							            if ($assoc_list['enable'] == 1) {
                                            $status = 'запущено';
                                            $img_status = "stop_task.png";
                                        }
										if ($assoc_list['enable'] == 0) {
                                            $status = 'остановлено';
                                            $img_status = "start_task.png";
                                        }
							            echo '

		            					<ul class="onoffdelete_panel" style="display: none">
	                                        <li>
	                                            <img class="start_task_id" style="cursor: pointer;" id="'.$assoc_list['id'].'" src="../img/auth/addsurf/'.$img_status.'" width="20" height="20">
	                                        </li>
	                                        <li>
	                                            <img class="edit_task_id" style="cursor: pointer;" id="'.$assoc_list['id'].'" src="../img/auth/addsurf/edit_task.png" width="20" height="20"> 
	                                        </li>
	                                        <li>
	                                            <img class="delete_task_id" style="cursor: pointer;" id="'.$assoc_list['id'].'" src="../img/auth/addsurf/delete_task.png" width="20" height="20">
	                                        </li>
	                                    </li>';
                                	}
                                    else {
                                        $status = 'забанено';
                                        echo '
                                            <div>задание удалить нельзя</div>';
                                    }
                                echo '
	            				</div>

	            				<div class="in_block_surfing_list_stroke_2">
	            					<div class="in_block_surfing_left_side_in_list_stroke_2">
	            						<div class="text1_in_block_surfing_left_side_in_list_stroke_2">
	            							Осталось '.$watch_left.' просмотров
	            						</div>
	            						<div class="text2_in_block_surfing_left_side_in_list_stroke_2">
	            							Затрачено: '.$assoc_list['spending_stats'].' руб.
	            						</div>
	            					</div>
                                    <div class="experiment">
    	            					<div '.$display_none.'class="'.$style_button.'" id="'.$assoc_list['id'].'">
    	            						поднять
    	            					</div>
                                    </div>
	            					<div class="in_block_surfing_right_side_in_list_stroke_2">
	            						<div class="text1_in_block_surfing_right_side_in_list_stroke_2">
	            							Просмотрено: '.$assoc_list['watch_stats'].' раз.
	            						</div>
	            						<div class="text2_in_block_surfing_right_side_in_list_stroke_2">
	            							Статус: '.$status.'
	            						</div>
	            					</div>
	            				</div>
                            </div>';
        }

        if ($param_ajax_addsurf == true) {
			echo ' 
						</div>';
        }

    }
    else {
        echo ' Вы не добавили ссылок';
    }
}


function PAGE_leadrace_payin_refresh($page_payin = 0) {
    global $db;
    //не существует переменной $id_user_place_before[$num_user_place]

    function check_empty($date1, $date2, $time) {
        global $db;
        global $id_user_place_before;

        //echo $date1.'<br>';
        $db->Query("SELECT SUM(`money_payin`) AS `money_payin`, `uid` FROM `history_money_payin` WHERE `date_payin` BETWEEN '$date1' AND '$date2' GROUP BY `uid` ORDER BY `money_payin` DESC LIMIT 5");
        $NumRows_h_m_p = $db->NumRows();
        if ( empty($NumRows_h_m_p) ) {      //если в таблице пусто, обновлять дату старта лидерства на текущую
            $UPDATE_leadrace_date = $db->Query_recordless("UPDATE `leadrace_date` SET `date_start` = NOW() WHERE `time_period` = $time");
            @mysqli_free_result($UPDATE_leadrace_date);
        }
        else {     //иначе,  если в тадлице есть записи, обновлять
            $num_user_place = 1;
            while ( $row_h_m_p = $db->FetchAssoc() ) {
                $SELECT_QUERY = $db->Query_recordless("SELECT `id` FROM `users` WHERE `id` = '$row_h_m_p[uid]'");        //чекпоинт //делать запросы по старинке
                $NumRows_u = mysqli_num_rows($SELECT_QUERY);
                if ( !empty($NumRows_u) ) {
                    $assoc_u = mysqli_fetch_assoc($SELECT_QUERY);

                    if ($num_user_place <= 5) {

                        $_SESSION['id_user_place_before'][$num_user_place] = (int)$id_user_place_before[$num_user_place] = $row_h_m_p['uid'];     //место пользователя до пополнения баланса (ключ массива место, значение id ользователя)
                    }//checkpoint 2

                    $num_user_place++;
                    @mysqli_free_result($SELECT_QUERY); //очистка пямяти от запроса
                   
                }
            }
        }
    }

    $db->Query("SELECT * FROM `leadrace_date`");
    $numrows_leadrace = $db->NumRows();
    if ( !empty($numrows_leadrace) ) {
        while ( $row = $db->FetchAssoc() ) {
            $num = $row['time_period'];
            foreach ($row as $key => $value) {
                $assoc_leadrace[$num][$key] = $value; // $value = $row[$key]
            }
        }
    }

    $section_date2 = date('y/m/j').' 23:59:59.999';     //вычисление текущей даты

    //Лидеры за 24 часа'; 
    check_empty($assoc_leadrace[24]['date_start'], $section_date2, 24);

    //Лидеры за 7 дней';                            
    check_empty($assoc_leadrace[7]['date_start'], $section_date2, 7);

    //Лидеры за 30 дней';             
    check_empty($assoc_leadrace[30]['date_start'], $section_date2, 30);

    //Лидеры за 365 дней';
    check_empty($assoc_leadrace[365]['date_start'], $section_date2, 365);


    /*сначала определяется, были ли пополнения за определенный период, если нет, то назначаем сегодняшний день началом гонки на пополнение, 
    после чего выполняется обработчик пополнения баланса и далее определяется список лидеров пополнений с сегодняшнего дня*/
    /*--------ОБРАБОТЧИК ПОПОЛНЕНИЯ БАЛАНСА-----VVVVVVVVVVVVVVVV-------*/
    if ($page_payin == 1) {

       // $db->Query('INSERT INTO `history_money_payin` VALUES(NULL, 12, 1, "balance_buy", "e" "e","e", "RUB", NOW(), NOW(), "success", 0)');        //симуляция пополнения баланса (!УДАЛИТЬ ПОСЛЕ ТЕСТА)
    }

    /*--------ОБРАБОТЧИК ПОПОЛНЕНИЯ БАЛАНСА-----AAAAAAAAAAAAAAAA-------*/



    function query_money_payin($date1, $date2, $time) {
        global $db;
        global $id_user_place_before;

        switch ($time) {
            case 24:
                $time_period = $time * 3600;
                break;
            default:
                $time_period = $time * 24 * 3600;
                break;
        }

        $end_period = strtotime($date1) + $time_period;     //конец периода
        $end_period = date('Y-m-d H:i:s', $end_period);  //форматируем секунды в дату

        $db->Query("SELECT SUM(`money_payin`) AS `money_payin`, `uid` FROM `history_money_payin` WHERE `date_payin` BETWEEN '$date1' AND '$date2' GROUP BY `uid` ORDER BY `money_payin` DESC LIMIT 5");
        $NumRows_h_m_p = $db->NumRows();
        if ( !empty($NumRows_h_m_p) ) {
            $num_class = 1;

            $num_user_place = 1;

            while ( $row_h_m_p = $db->FetchAssoc() ) {
                $SELECT_QUERY = $db->Query_recordless("SELECT `id` FROM `users` WHERE `id` = '$row_h_m_p[uid]'");        //чекпоинт //делать запросы по старинке

                $NumRows_u = mysqli_num_rows($SELECT_QUERY);
                if ( !empty($NumRows_u) ) {
                    $assoc_u = mysqli_fetch_assoc($SELECT_QUERY);
                    

                    if ($num_user_place <= 5) {

                        $id_user_place_after[$num_user_place] = $assoc_u['id']; //место пользователя после пополнения баланса (ключ массива место, значение id ользователя)

                        switch ($num_user_place) {
                            case 1:
                                $percent_user_income = 10;
                                break;
                            case 2:
                                $percent_user_income = 7;
                                break;
                            case 3:
                                $percent_user_income = 5;
                                break;
                            case 4:
                                $percent_user_income = 3;
                                break;
                            case 5:
                                $percent_user_income = 1;
                                break;
                            default:
                                $percent_user_income = 0;
                                break;
                        }
                    }

                    @mysqli_free_result($SELECT_QUERY); //очистка пямяти от запроса
                   
                }
                else {
                    echo 'Error_empty0x01';
                }

                switch ($time) {
                    case 24:
                        $leader_bonus_percent_income = 'leader_bonus_percent_income1';
                        break;
                    case 7:
                        $leader_bonus_percent_income = 'leader_bonus_percent_income2';
                        break;
                    case 30:
                        $leader_bonus_percent_income = 'leader_bonus_percent_income3';
                        break;
                    case 365:
                        $leader_bonus_percent_income = 'leader_bonus_percent_income4';
                        break;
                
                    default:
                        $leader_bonus_percent_income = 'leader_bonus_percent_income1';
                        break;
                } 

                if ($_SESSION['id_user_place_before'][$num_user_place] != $id_user_place_after[$num_user_place]) {  /*$percent_user_income. ставим текущую дату на окончание отрезка бонусного дохода (где uid = $id_user_place_before), считаем доход на рудниках. Далее удаляем этот отрезок дохода и создаем новый (где uid = $id_user_place_after)*/
                    //echo $_SESSION['id_user_place_before'][$num_user_place].'='.$id_user_place_after[$num_user_place].'<br>';
                    $UP_QUERY_interval_bonus = $db->Query_recordless("UPDATE `user_date_start_end_percent_per_leadrace` SET `end_period` = NOW() WHERE `uid` = ".$_SESSION['id_user_place_before'][$num_user_place]." AND `day_interval` = '$leader_bonus_percent_income'");  //если место пользователя в лидерстве меняется, то завершаем старый отрезок бонусности, потом добавляем бонусные минералы, удаляем этот отрезок, и создаем новый



    /*----------------------VVVVVVVVVVV---------------------Добавление бонусных минералов к рудникам-----VVVVVVVVVVV------------*/
                    
                    /*[OK]берем рудники пользователя, извлекаем дату сбора и если она попадает в промежуток бонусной доходности, то умножаем время в этом промежутке на
                    на доходность и т.д., полученные минералы добавляем в архив минералов
                    циклом извлекам в цикле и делаем запрос*/

                    $SELECT_QUERY_u_d_s_e_p_p_l = $db->Query_recordless("SELECT * FROM `user_date_start_end_percent_per_leadrace` WHERE `uid` = ".$_SESSION['id_user_place_before'][$num_user_place]." AND `day_interval` = '$leader_bonus_percent_income'");     //извлекаем процент, дату началаи конца
                    $numrows_u_d_s_e_p_p_l = mysqli_num_rows($SELECT_QUERY_u_d_s_e_p_p_l);
                    if ( !empty($numrows_u_d_s_e_p_p_l) ) {
                        $assoc_u_d_s_e_p_p_l = mysqli_fetch_assoc($SELECT_QUERY_u_d_s_e_p_p_l);

                        $SELECT_QUERY_u_amount_mine = $db->Query_recordless("SELECT * FROM `users_amount_mine` WHERE `uid` = ".$_SESSION['id_user_place_before'][$num_user_place]."");     //извлекаем дату последнего сбора и доходность рудника
                        $numrows_u_a_m = mysqli_num_rows($SELECT_QUERY_u_amount_mine);
                        if ( !empty($numrows_u_a_m) ) {
                            while ( $assoc_u_a_m = mysqli_fetch_assoc($SELECT_QUERY_u_d_s_e_p_p_l) ) {

                                if ( strtotime($assoc_u_a_m['date_collection']) <= strtotime($assoc_u_a_m['date_collection']) ) {//если секунды сбора меньше секунд начала лидерста, то берем период от начала лидерства до настоящего момента, если иначе, то от секунд сбора до настоящего момента
                                    $period_income = time() - strtotime($assoc_u_d_s_e_p_p_l['start_period']);
                                }
                                else {
                                    $period_income = time() - strtotime($assoc_u_a_m['date_collection']);
                                }
    /*-----chekpoint---уда-то пропадает $_SESSION['id_user_place_before'][$num_user_place]-*/
    							if ($assoc_u_a_m['rate_seconds'] == 0) $assoc_u_a_m['rate_seconds'] = 60;
                                $income = ( $period_income * ($assoc_u_a_m['rate_mining'] + $assoc_u_a_m['bonus']) ) / $assoc_u_a_m['rate_seconds'];
                                //echo 'r-'.$assoc_u_a_m['rate_mining'].'-r'.$assoc_u_a_m['date_collection'].'<-r'.$_SESSION['id_user_place_before'][$num_user_place].'-r2'.$leader_bonus_percent_income.'-r3'.$num_user_place.'-r4-';
                                $mine_bonus_keep_minerals = $income * ($percent_user_income / 100); //определяем процент дохода от процентов

                                $UPDATE_QUERY_u_a_m = $db->Query_recordless("UPDATE `users_amount_mine` SET `archive_keep_minerals` = (`archive_keep_minerals` + $mine_bonus_keep_minerals) WHERE `id` = '$assoc_u_a_m[id]'");
                                @mysqli_free_result($UPDATE_QUERY_u_a_m);
                            }
                        }
                    }

                    @mysqli_free_result($SELECT_QUERY_u_amount_mine);
                    @mysqli_free_result($SELECT_QUERY_u_d_s_e_p_p_l);

    /*----------------------AAAAAAAAAAAAAAAAAAAAAAA---------------------Добавление бонусных минералов к рудникам-----AAAAAAAAAAAAAA------------*/

                    $DELETE_QUERY_interval_bonus = $db->Query_recordless("DELETE FROM `user_date_start_end_percent_per_leadrace` WHERE `uid` = ".$_SESSION['id_user_place_before'][$num_user_place]." AND `day_interval` = '$leader_bonus_percent_income'");  //удаляем старый отрезок бонуса
                       //удаление старого отрезка
                    @mysqli_free_result($DELETE_QUERY_interval_bonus);

                    $INSERT_QUERY_interval_bonus = $db->Query_recordless('INSERT INTO `user_date_start_end_percent_per_leadrace` VALUES (NULL, "'.$id_user_place_after[$num_user_place].'", "'.$leader_bonus_percent_income.'", "'.$percent_user_income.'", NOW(), "'.$end_period.'" )'); //создание нового отрезка
                    @mysqli_free_result($UP_QUERY_interval_bonus);  //очистка данных запроса	checkpoint 3 "'.$leader_bonus_percent_income.'" - синтаксис

                    @mysqli_free_result($INSERT_QUERY_interval_bonus);  //очистка данных запроса
                    
                }
                else {  //если id на прошлом шаге не равен текущему (пользователя сместили с места лидера)
                    $SELECT_QUERY_interval_bonus = $db->Query_recordless("SELECT `id` FROM `user_date_start_end_percent_per_leadrace` WHERE `uid` = '$id_user_place_after[$num_user_place]' AND `day_interval` = '$leader_bonus_percent_income'");
                   //echo 88;
                    $numrows_user_d_s_e_p_p_l = mysqli_num_rows($SELECT_QUERY_interval_bonus);

                    if ( empty($numrows_user_d_s_e_p_p_l) ) {	//checkpoint test error
                      /*-2*/  $INSERT_QUERY_interval_bonus = $db->Query_recordless('INSERT INTO `user_date_start_end_percent_per_leadrace` VALUES (NULL, "'.$id_user_place_after[$num_user_place].'", "'.$leader_bonus_percent_income.'", "'.$percent_user_income.'", NOW(), "'.$end_period.'" )');   //8 если отрезков не было то создаем новый и не начисляем бонусы
                      @mysqli_free_result($INSERT_QUERY_interval_bonus);   //очистка запроса
                    }
                    else {

    /*----------------------VVVVVVVVVVV---------------------Добавление бонусных минералов к рудникам-----VVVVVVVVVVV------------*/
                        $SELECT_QUERY_u_d_s_e_p_p_l = $db->Query_recordless("SELECT * FROM `user_date_start_end_percent_per_leadrace` WHERE `uid` = ".$_SESSION['id_user_place_before'][$num_user_place]." AND `day_interval` = '$leader_bonus_percent_income'");     //извлекаем процент, дату начала и конца
                        $numrows_u_d_s_e_p_p_l = mysqli_num_rows($SELECT_QUERY_u_d_s_e_p_p_l);


                        if ( !empty($numrows_u_d_s_e_p_p_l) ) {	//checkpoint не выполняется условие
                            $assoc_u_d_s_e_p_p_l = mysqli_fetch_assoc($SELECT_QUERY_u_d_s_e_p_p_l);

                            $SELECT_QUERY_u_amount_mine = $db->Query_recordless("SELECT * FROM `users_amount_mine` WHERE `uid` = ".$_SESSION['id_user_place_before'][$num_user_place]."");     //извлекаем дату последнего сбора и доходность рудника
                            $numrows_u_a_m = mysqli_num_rows($SELECT_QUERY_u_amount_mine);
                            if ( !empty($numrows_u_a_m) ) {	
                                while ( $assoc_u_a_m = mysqli_fetch_assoc($SELECT_QUERY_u_amount_mine) ) {

                                    if ( strtotime($assoc_u_a_m['date_collection']) <= strtotime($assoc_u_a_m['date_collection']) ) {//если секунды сбора меньше секунд начала личдерста, то берем период от начала лидерства до настоящего момента, если иначе, то от секунд сбора до настоящего момента
                                        $period_income = time() - strtotime($assoc_u_d_s_e_p_p_l['start_period']);
                                    }
                                    else {
                                        $period_income = time() - strtotime($assoc_u_a_m['date_collection']);
                                    }
                                    if ($assoc_u_a_m['rate_seconds'] == 0) $assoc_u_a_m['rate_seconds'] = 60;
                                    $income = ( $period_income * ($assoc_u_a_m['rate_mining'] + $assoc_u_a_m['bonus']) ) / $assoc_u_a_m['rate_seconds'];
                                    $mine_bonus_keep_minerals = $income * ($percent_user_income / 100); //определяем процент дохода от процентов

                                    $UPDATE_QUERY_u_a_m = $db->Query_recordless("UPDATE `users_amount_mine` SET `archive_keep_minerals` = (`archive_keep_minerals` + $mine_bonus_keep_minerals) WHERE `id` = '$assoc_u_a_m[id]'");

                                    @mysqli_free_result($UPDATE_QUERY_u_a_m);
                                }
                            }
                        

	                        $DELETE_QUERY_u_dse_ppl = $db->Query_recordless("DELETE FROM `user_date_start_end_percent_per_leadrace` WHERE `uid` = ".$_SESSION['id_user_place_before'][$num_user_place]." AND `day_interval` = '$leader_bonus_percent_income'");
	                        @mysqli_free_result($DELETE_QUERY_u_dse_ppl);	
                        
                        }

                        $INSERT_QUERY_interval_bonus_2 = $db->Query_recordless('INSERT INTO `user_date_start_end_percent_per_leadrace` VALUES (NULL, "'.$id_user_place_after[$num_user_place].'", "'.$leader_bonus_percent_income.'", "'.$percent_user_income.'", NOW(), "'.$end_period.'" )');   //создание нового отрезка после удаления нужно, но оно приводит к созданию дополнительного
                        @mysqli_free_result($INSERT_QUERY_interval_bonus_2);   //Причина в этой строчке (Unknown column 'leader_bonus_percent_income1' in 'field list') checkpint test

                        $SELECT_QUERY_u_d_s_e_p_p_l = $db->Query_recordless("SELECT * FROM `user_date_start_end_percent_per_leadrace` WHERE `uid` = ".$_SESSION['id_user_place_before'][$num_user_place]." AND `day_interval` = '$leader_bonus_percent_income'");     //извлекаем процент, дату начала и конца
                        $numrows_u_d_s_e_p_p_l = mysqli_num_rows($SELECT_QUERY_u_d_s_e_p_p_l);
                        
                        @mysqli_free_result($SELECT_QUERY_u_amount_mine);
                        @mysqli_free_result($SELECT_QUERY_u_d_s_e_p_p_l);
                    }

    /*----------------------AAAAAAAAAAAAAAAAAAAAAAA---------------------Добавление бонусных минералов к рудникам-----AAAAAAAAAAAAAA------------*/

                    @mysqli_free_result($SELECT_QUERY_interval_bonus);
                }
                /*Если пользователь входит в число лидеров, а в таблице его нет, то создаем интервал бонусного дохода*/
                $user_id = $assoc_u['id'];
                $UPDATE_QUERY = $db->Query_recordless("UPDATE `users` SET ".$leader_bonus_percent_income." = $percent_user_income WHERE `id` = '$assoc_u[id]'");

                @mysqli_free_result($UPDATE_QUERY);     //очистка данных запроса
                @mysqli_free_result($SELECT_QUERY);     //очистка данных запроса

                $num_user_place++;
            }
        }
    }

    $section_date2 = date('y/m/j').' 23:59:59.999';     //вычисление текущей даты

    //Лидеры за 24 часа'; 
    query_money_payin($assoc_leadrace[24]['date_start'], $section_date2, 24);

    //Лидеры за 7 дней';                            
    query_money_payin($assoc_leadrace[7]['date_start'], $section_date2, 7);

    //Лидеры за 30 дней';             
    query_money_payin($assoc_leadrace[30]['date_start'], $section_date2, 30);

    //Лидеры за 365 дней';
    query_money_payin($assoc_leadrace[365]['date_start'], $section_date2, 365);





    /*Повторный запрос (иначе работает неправильно)------VVVVVVVVVVVVV-----------*/

    $db->Query("SELECT * FROM `leadrace_date`");
    $numrows_leadrace = $db->NumRows();
    if ( !empty($numrows_leadrace) ) {
        while ( $row = $db->FetchAssoc() ) {
            $num = $row['time_period'];
            foreach ($row as $key => $value) {
                $assoc_leadrace[$num][$key] = $value; // $value = $row[$key]
            }
        }
    }

    $section_date2 = date('y/m/j').' 23:59:59.999';     //вычисление текущей даты

    //Лидеры за 24 часа'; 
    check_empty($assoc_leadrace[24]['date_start'], $section_date2, 24);

    //Лидеры за 7 дней';                            
    check_empty($assoc_leadrace[7]['date_start'], $section_date2, 7);

    //Лидеры за 30 дней';             
    check_empty($assoc_leadrace[30]['date_start'], $section_date2, 30);

    //Лидеры за 365 дней';
    check_empty($assoc_leadrace[365]['date_start'], $section_date2, 365);


    /*сначала определяется, были ли пополнения за определенный период, если нет, то назначаем сегодняшний день началом гонки на пополнение, 
    после чего выполняется обработчик пополнения баланса и далее определяется список лидеров пополнений с сегодняшнего дня*/



    //Лидеры за 24 часа'; 
    query_money_payin($assoc_leadrace[24]['date_start'], $section_date2, 24);

    //Лидеры за 7 дней';                            
    query_money_payin($assoc_leadrace[7]['date_start'], $section_date2, 7);

    //Лидеры за 30 дней';             
    query_money_payin($assoc_leadrace[30]['date_start'], $section_date2, 30);

    //Лидеры за 365 дней';
    query_money_payin($assoc_leadrace[365]['date_start'], $section_date2, 365);


    /*Повторный запрос (иначе работает неправильно)------AAAAAAAAAAAAAAAAAAAAAA-----------*/

    unset($_SESSION['id_user_place_before']);
}





function top_auth($title, $style, $name_mine = false, $JavaScript = false, $social_banner_toogle = false) {

	global $_SESSION;
    global $db;
/*________V______счетчик онлайн_____V_______*/
    $session = session_id();
    $time = time();

    $db->Query("SELECT * FROM `online_users` WHERE `session` = '$session'");
    $num_rows = $db->NumRows();
    if ( empty($num_rows) ) {
        $db->Query('INSERT INTO `online_users` VALUES("'.$_SESSION['id'].'", "'.$session.'", "'.$time.'")');
    }
    else {
        $db->Query("UPDATE `online_users` SET `time` = $time WHERE `session` = '$session'");
    }

    $db->Query("DELETE FROM `online_users` WHERE `time` < ($time - 10)");

/*________A______счетчик онлайн_____A_______*/

    header("Content-Type: text/html; charset=utf-8");  // стандартная установка кодировки

    

	if (isset($_SESSION['page'])) {
		if ( stristr($_SESSION['page'], '?', true) == 'mine' ) {	//обрезка до конкретного символа
			$elementtext_2_stroke_1_center_panel = $name_mine;

			$stroke_2_center_panel = '<a class href="/">Главная</a>&nbsp;/&nbsp;<a href="/my_cabinet">Кабинет пользователя</a>&nbsp;/&nbsp;<a href="/my_field">Мой Найт-Сити</a>&nbsp;/&nbsp;<a href="/'.$_SESSION['page'].'"><span class="data_name_mine">'.$name_mine.'</span></a>';
		}
		else {
			switch($_SESSION['page']) {	//Если текущая страница равна case'', то отображаем: текст в элементе
				case 'my_cabinet':

                    $db->Query("SELECT `Name` FROM `users` WHERE `id` = '$_SESSION[id]'");
                    $numrows_users = $db->NumRows();
                    if ( !empty($numrows_users) ) {
                        $assoc_users = $db->FetchAssoc();
                        if ($assoc_users['Name'] != 'Без имени') {
                            $name_user = htmlspecialchars($assoc_users['Name']);
                        }
                        else {
                            $name_user = $_SESSION['login'];
                        }    
                    }

                    $seconds_today = time() - strtotime('today');
                    if ($seconds_today < 39600) {
                        $welcome_text = 'Доброе утро';
                    }
                    else if ($seconds_today >= 39600 and $seconds_today < 64800) {
                        $welcome_text = 'Добрый день';
                    }
                    else {
                        $welcome_text = 'Добрый вечер';
                    } 

					$elementtext_2_stroke_1_center_panel = $welcome_text.', '.$name_user.'!';
					$div_elementbutton_3_stroke_1_center_panel = '<div class="elementbutton_3_stroke_1_center_panel"></div>';
					$stroke_2_center_panel = '<a href="/">Главная</a>&nbsp;/&nbsp;<a href="/my_cabinet">Кабинет пользователя</a>'; 
					break;	
				default: 
					$elementtext_2_stroke_1_center_panel = $title;

					$stroke_2_center_panel = '<a href="/">Главная</a>&nbsp;/&nbsp;<a href="/my_cabinet">Кабинет пользователя</a>&nbsp;/&nbsp;<a href="/'.$_SESSION['page'].'">'.$title.'</a>'; 
					break;				
			}
		}
	}
/*----function for surfsites----------AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA-----------------*/

	echo '<!DOCTYPE html>
	<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="description" content="Экономический симулятор с выводом реальных денег!">
	<meta name="Keywords" content="cyberpunk-money, игры с выводом денег, игры с выводом денег,, экономический симулятор, заработок, заработок в интернете, хайп, хайпы, well-money, mmgp.ru, долгосрочный хайп, инвестиции"> 

	<meta property="og:image" content="/img/bigfav.png" />
	<meta property="og:image:secure_url" content="/img/bigfav.png" />
	<meta property="og:image:type" content="image/png" />
	<meta property="og:image:width" content="100px" />
	<meta property="og:image:height" content="100px" />
	<meta property="og:image:alt" content="Cyberpunk-Money.com - экономический симулятор" />

	<title>'.$title.'</title>
	<link rel="stylesheet" href="'.$style.'.css">
    <link rel="stylesheet" type"text/css" href="../all/sweetalert2.css">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
    <script data-ad-client="ca-pub-4624626706857484" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
	<script src="../javascript/jquery-1.12.4.js"></script>
    <script type="text/javascript" src="../javascript/sweetalert2.js"></script>
	<script src="../javascript/script.js"></script>';

	if ($JavaScript == 'my_cabinet') {
        echo'
    	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
    	<script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.2/raphael-min.js"></script>
        <script src="../javascript/morris.js"></script>
    	<script src="https://cdnjs.cloudflare.com/ajax/libs/prettify/r224/prettify.min.js"></script>
    	<script src="../javascript/example.js"></script>
    	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prettify/r224/prettify.min.css">
    	<link rel="stylesheet" href="../all/style/morris.css">';
    }

    /*-------------------------my_refs_JS--------------VVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVV-----------------------*/

    if ($JavaScript == 'my_refs') {
        global $as_count_ar;
        global $date;
        echo'
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.2/raphael-min.js"></script>
        <script src="../javascript/morris.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/prettify/r224/prettify.min.js"></script>
        <script src="../javascript/example.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prettify/r224/prettify.min.css">
        <link rel="stylesheet" href="../all/style/morris.css">
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script type="text/javascript">
          google.charts.load("current", {"packages":["corechart"]});
          google.charts.setOnLoadCallback(drawChart);

          function drawChart() {
            var data = google.visualization.arrayToDataTable([
              ["Дата", "Кол-во регистраций"],
              ["'.$date[6].'", '.$as_count_ar[6]['amount_reg'].'],
              ["'.$date[5].'", '.$as_count_ar[5]['amount_reg'].'],
              ["'.$date[4].'", '.$as_count_ar[4]['amount_reg'].'],
              ["'.$date[3].'", '.$as_count_ar[3]['amount_reg'].'],
              ["'.$date[2].'", '.$as_count_ar[2]['amount_reg'].'],
              ["'.$date[1].'", '.$as_count_ar[1]['amount_reg'].'],
              ["'.$date[0].'", '.$as_count_ar[0]['amount_reg'].']
            ]);

            var options = {
              title: "График кол-ва регистраций по реферальной ссылке",
              hAxis: {title: "Дата",  titleTextStyle: {color: "#333"}},
              vAxis: {minValue: 0.001},

              colors: ["#865FFA"]

            };

            var chart = new google.visualization.AreaChart(document.getElementById("chart_div"));
            chart.draw(data, options);
          }
        </script>';
    }


/*-------------------------my_refs_JS--------------AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA-----------------------*/

    echo '
	<script type="text/javascript">
		function ajax_index_top_auth() {
			$.get("ajax/ajax_index_top_auth", function(data) {	//функция получает данные data с файла по директории
				data = $(data);
				$(".div_3_elementtext_2_stroke_2_left_panel").html( $(".container_balance_buy", data).html() );	//извлечение конкретных балансов из одного файла
				$(".div_3_elementtext_3_stroke_2_left_panel").html( $(".container_balance_withdrawal", data).html() );
				$(".div_3_elementtext_4_stroke_2_left_panel").html( $(".container_balance_advertising", data).html() );

			});
		};

        $(document).on("click", ".area_click_right", function() {
                var overflow_block_fallout_menu_height = $(".overflow_block_fallout_menu").css("height");

                if (overflow_block_fallout_menu_height == "110px") {
                	$(".image_arrow_01").css("display", "none");
                	$(".image_arrow_02").css("display", "block");
                    
                    $(".menu_list_fallout_menu").css("display", "block");
                    $(".overflow_block_fallout_menu").css("height", "285px");
                }
                else {
                	$(".image_arrow_02").css("display", "none");
                	$(".image_arrow_01").css("display", "block");

                    setTimeout(function() {
                        $(".menu_list_fallout_menu").css("display", "none");        
                    }, 300); 
                    $(".overflow_block_fallout_menu").css("height", "110px");
                }


                var fallout_menu_border_left = $(".border_left_fm").css("width");
                
                if (fallout_menu_border_left == "2px") {
                    $(".border_left_fm").css("width", "0px");
                    $(".border_left_fm").css("background", "transparent");
                }
                else {
                	$(".border_left_fm").css("width", "2px");
                    $(".border_left_fm").css("background", "#ffd642");
                }


                var fallout_menu_background_color = $(".fallout_menu").css("background-color");

                if (fallout_menu_background_color == "rgb(75, 25, 82)") {
                    $(".fallout_menu").css("background-color", "#ffd642");
                }

                else {
                    $(".fallout_menu").css("background-color", "#4b1952");    
                }
            });
       
        $(document).on("click", ".avatar_with_pointer_online", function() {
        	var overflow_right_avatar_height = $(".overflow_right_avatar").css("height");	

        	if (overflow_right_avatar_height == "0px") {
                $(".overflow_right_avatar").css("display", "block");
                $(".right_avatar_fallout_menu").css("display", "flex");
        		$(".overflow_right_avatar").css("height", "155px");
        	}
        	else {
        		setTimeout(function() {
        			$(".overflow_right_avatar").css("display", "none");
                    $(".right_avatar_fallout_menu").css("display", "none");        
                }, 200);
        		$(".overflow_right_avatar").css("height", "0px");
        	}
        	
        });

        $(document).on("mouseenter", ".box_ul_text_menu", function() {
        	var v = $(this).find(".mouse_tab");
        	var v_02 = $(this).find(".mouse_tab_02");

        	$(v).hide();
        	$(v_02).show();	
        });
        $(document).on("mouseleave", ".box_ul_text_menu", function() {
        	var v = $(this).find(".mouse_tab");
        	var v_02 = $(this).find(".mouse_tab_02");

        	$(v_02).hide();
        	$(v).show();		
        });
        ';


if ($JavaScript == 'my_cabinet') {
	echo '
	/*----my_cabinet_JS----------VVVVVVVVVVVVVVVVVVVVVVVVVVVVVVV-----------------*/
		$(document).on("mouseenter", "#button_after_tab_setting", function() {
			setTimeout( function() {
				$("#setting_icon_button").hide();
				$("#setting_icon_button_02").show();
			}, 100);
		});
		$(document).on("mouseleave", "#button_after_tab_setting", function() {
			setTimeout( function() {
				$("#setting_icon_button_02").hide();
				$("#setting_icon_button").show();
			}, 100);
		});

		$(document).on("mouseenter", "#button_after_tab_userwall", function() {
			setTimeout( function() {
                $("#userwall_icon_button").hide();
				$("#userwall_icon_button_02").show();        
            }, 100); 
			

		});
		$(document).on("mouseleave", "#button_after_tab_userwall", function() {
			setTimeout( function() {
				$("#userwall_icon_button_02").hide();
				$("#userwall_icon_button").show();
			}, 100);

		});



	/*----my_cabinet_JS----------AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA-----------------*/
	';
}

if ($JavaScript == 'surfsites') {
    echo '
    /*----surfsites_JS---------VVVVVVVVVVVVVVVVVVVVVVVVVVVVVVV-----------------*/

        $(document).on("mouseenter", "#container2_sb1cp_button", function() {
            setTimeout( function() {
                $("#img_icon_button_01").hide();
                $("#img_icon_button_02").show();
            }, 100);
        });
        $(document).on("mouseleave", "#container2_sb1cp_button", function() {
            setTimeout( function() {
                $("#img_icon_button_02").hide();
                $("#img_icon_button_01").show();
            }, 100);
        });     /*замена картнки в кнопке при плавном изменении фона*/

    /*----surfsites_JS----------AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA-----------------*/
    ';
}

if ($JavaScript == 'payin_money') {
    echo '
    /*----payin_money_JS----------VVVVVVVVVVVVVVVVVVVVVVVVVVVVVVV-----------------*/
        $(document).on("mouseenter", "#cross_01", function() {
            $("#cross_01").hide();
            $("#cross_02").show();
        });
        $(document).on("mouseleave", "#cross_02", function() {
            $("#cross_02").hide();
            $("#cross_01").show();
        });


        $(document).on("click", ".orange_button", function() {
            //получаем id кнопки и показываем окно плюс id картинки картинки
            var ps_id = $(this).attr("id");
            var img_id ="#img_"+ps_id+"";
            
            $(img_id).show();
           

            var form_id ="#form_"+ps_id+"";
            $(form_id).show();
            
            $(".blackout").show();
            $(".background_around").show();
        });

        $(document).on("click", "#cross_02", function() {
            $(".img_ps").hide();
            
            $(".form_class").hide();
            $(".blackout").hide();
            $(".background_around").hide();
        });
        $(document).on("click", ".background_around", function() {  /*событие клика на область вне диалогового окна*/
            $(".img_ps").hide();
            
            $(".form_class").hide();
            $(".blackout").hide();
            $(".background_around").hide();
        });
    ';
}

if ($JavaScript == 'payout_money') {
    echo '
    /*----payin_money_JS----------VVVVVVVVVVVVVVVVVVVVVVVVVVVVVVV-----------------*/
        $(document).on("mouseenter", "#cross_01", function() {
            $("#cross_01").hide();
            $("#cross_02").show();
        });
        $(document).on("mouseleave", "#cross_02", function() {
            $("#cross_02").hide();
            $("#cross_01").show();
        });


        $(document).on("click", ".orange_button", function() {
            //получаем id кнопки и показываем окно плюс id картинки картинки
            var ps_id = $(this).attr("id");
            var img_id = "#img_"+ps_id+"";
            
            $(img_id).show();

           

            var form_id ="#form_"+ps_id+"";
            $(form_id).show();
            
            $(".blackout").show();
            $(".background_around").show();
        });

        $(document).on("click", "#cross_02", function() {
            $(".img_ps").hide();
            
            $(".form_class").hide();
            $(".blackout").hide();
            $(".background_around").hide();
        });
        $(document).on("click", ".background_around", function() {  /*событие клика на область вне диалогового окна*/
            $(".img_ps").hide();
            
            $(".form_class").hide();
            $(".blackout").hide();
            $(".background_around").hide();
        });
    ';
}

if ($JavaScript == 'addsurf') {
	echo '
	/*----addsurf_JS----------VVVVVVVVVVVVVVVVVVVVVVVVVVVVVVV-----------------*/
	        $(document).ready(function() {
				function refresh_addsurf() {
					$.get("ajax/ajax_addsurf", function(data3) {	//функция получает данные data с файла по директории
						data3 = $(data3);
						$("#stroke2_c2_sb1cp").html( $("#container_surfing_list", data3).html() );
					});
				};

				function calcul2() {
		            var a = document.getElementById("time_watch");
		            a.onchange = a.onkeyup = function() {

		                var time_watch = document.getElementById("time_watch").value;
		                var cost_watch = document.getElementById("cost_watch").value;

		                switch (time_watch) {
		                    case "20":
		                        var value_cost_watch = 0.03;
		                        break;
		                    case "30":
		                        var value_cost_watch = 0.04;
		                        break;
		                    case "40":
		                        var value_cost_watch = 0.05;
		                        break;
		                    case "50":
		                        var value_cost_watch = 0.06;
		                        break;
		                    case "60":
		                        var value_cost_watch = 0.07;
		                        break;

		                    default:
		                        var value_cost_watch = 0.03;
		                        break;
		                }

		                document.getElementById("cost_watch").value = value_cost_watch+" руб.";
		                document.getElementById("value_cost_watch").value = value_cost_watch;
		            };
		        };
				calcul2(); 

				$(document).on("change", "#checkbox_ad", function() {
	                if ($(this).is(":checked")) {
	                    $(this).val(1);
	                }
	                else {
	                    $(this).val(0);
	                }
	            });

	            $(document).on("change", "#checkbox_enable", function() {
	                if ($(this).is(":checked")) {
	                	$(this).val(1);
	                }
	                else {
	                	$(this).val(0);
	                }
	            });

	            $(document).on("change", "#checkbox_count_views", function() {
	                if ($(this).is(":checked")) {
	                	$(this).val(1);
	                	$("#max_count_views").attr("readonly", "readonly");
	                	$("#max_count_views").val("Неограничено просмотров");
	                }
	                else {
	                	$(this).val(0);
	                	$("#max_count_views").removeAttr("readonly");	//Удалить атрибут readonly
	                	$("#max_count_views").val("1000");
	                }
	            });

                /*----V---start_task---V----*/
                $(document).on("mouseenter", ".list_box_s2c2sb1cp_green", function() {
                    $(this).find(".onoffdelete_panel").css("display", "flex");
                });
                $(document).on("mouseenter", ".list_box_s2c2sb1cp_yellow", function() {
                    $(this).find(".onoffdelete_panel").css("display", "flex");
                });

                $(document).on("mouseleave", ".list_box_s2c2sb1cp_green", function() {
                    $(this).find(".onoffdelete_panel").css("display", "none");
                    
                });
                $(document).on("mouseleave", ".list_box_s2c2sb1cp_yellow", function() {
                    $(this).find(".onoffdelete_panel").css("display", "none");
                    
                });
                /*----A---start_task---A----*/

	            /*----V---start_task---V----*/
	            $(document).on("click", ".start_task_id", function() {
					var start_task_id = $(this).attr("id");
					var action_f = "start_task";
					$.ajax({
						url : "mine_shop/actions/addsurf_action_task_POST",
						type: "POST",
						data: "start_task_id="+start_task_id +"&"+ "action_f="+action_f,
						cache: false,
                        success: function(result) {
                            obj = jQuery.parseJSON(result);
                            if (obj.go) {
                                go(obj.go);
                            }
                            else {
                                switch (obj.status) {
                                    case "warning":
                                        Swal.fire(
                                            "Внимание",
                                            obj.message,
                                            "warning"
                                        ).then((result) => {
                                            if (result.value == true || result.value == undefined) {
                                                if (obj.close_u == true) {
                                                    window.close();
                                                }
                                            }
                                        });
                                        break;
                                    case "error":
                                        Swal.fire(
                                            "Ошибка!",
                                            obj.message,
                                            "error"
                                        ).then((result) => {
                                            if (result.value == true || result.value == undefined) {
                                                if (obj.close_u == true) {
                                                    window.close();
                                                }
                                            }
                                        });
                                        break;
                                    case "info":
                                        Swal.fire(
                                            "Уведомление",
                                            obj.message,
                                            "info"
                                        ).then((result) => {
                                            if (result.value == true || result.value == undefined) {
                                                if (obj.close_u == true) {
                                                    window.close();
                                                }
                                            }
                                        });
                                        break;
                                    case "Question":
                                        Swal.fire(
                                            "Информация",
                                            obj.message,
                                            "question"
                                        ).then((result) => {
                                            if (result.value == true || result.value == undefined) {
                                                if (obj.close_u == true) {
                                                    window.close();
                                                }
                                            }
                                        });
                                        break;
                                    default:
                                        Swal.fire(
                                            "Успешно",
                                            obj.message,
                                            "success"
                                        ).then((result) => {
                                            if (result.value == true || result.value == undefined) {
                                                if (obj.url_u) {
                                                    window.location.href = obj.url_u;
                                                }
                                            }
                                        });
                                        break;
                                }

                                /*
                                if (obj.url_u) {
                                    window.location.href = obj.url_u;
                                }
                                if (obj.close_u == true) {
                                    window.close();
                                }*/
                            }
                        }
					});
					refresh_addsurf();
				});
				/*----A---start_task---A----*/
				/*----V---edit_task---V----*/
				$(document).on("click", ".edit_task_id", function() {
					var edit_task_id = $(this).attr("id");
					var action_f = "edit_task";

					/*Передать данные через cookie*/
					document.cookie = "edit_task_id="+edit_task_id;
					document.cookie = "action_f="+action_f;

					$.get("mine_shop/actions/addsurf_action_task_GET", function(data) {	//функция получает данные data с файла по директории
						data = $(data);
						$("#container1_sb1cp").html( $("#container_edit_task", data).html() );
					});
				});
				$(document).on("click", "#button_save_change", function() {
					refresh_addsurf();
				});
				/*----A---edit_task---A----*/
				/*----V---delete_task---V----*/
				$(document).on("click", ".delete_task_id", function() {
					var delete_task_id = $(this).attr("id");
					var action = "delete_task";

/*-----------------------Первый SweetAlert-----------VVVVVVVVVVV----------*/
                        Swal.fire({
                          title: "Вы действительно хотите удалить сайт?",
                          type: "question",
                          confirmButtonText:  "Да",
                          cancelButtonText:  "Нет",
                          showCancelButton: true,
                          showCloseButton: true
                        }).then((result) => {
                            if (result.value == true) {
                                $.ajax({
                                    url : "mine_shop/actions/addsurf_action_task_POST",
                                    type: "POST",
                                    data: "delete_task_id="+delete_task_id +"&"+ "action_f="+action,
                                    cache: false,
                                    success: function(result) {
                                        obj = jQuery.parseJSON(result);
                                        if (obj.go) {
                                            go(obj.go);
                                        }
                                        else {
                                            switch (obj.status) {
                                                case "warning":
                                                    Swal.fire(
                                                        "Внимание",
                                                        obj.message,
                                                        "warning"
                                                    ).then((result) => {
                                                        if (result.value == true || result.value == undefined) {
                                                            if (obj.close_u == true) {
                                                                window.close();
                                                            }
                                                        }
                                                    });
                                                    break;
                                                case "error":
                                                    Swal.fire(
                                                        "Ошибка!",
                                                        obj.message,
                                                        "error"
                                                    ).then((result) => {
                                                        if (result.value == true || result.value == undefined) {
                                                            if (obj.close_u == true) {
                                                                window.close();
                                                            }
                                                        }
                                                    });
                                                    break;
                                                case "info":
                                                    Swal.fire(
                                                        "Уведомление",
                                                        obj.message,
                                                        "info"
                                                    ).then((result) => {
                                                        if (result.value == true || result.value == undefined) {
                                                            if (obj.close_u == true) {
                                                                window.close();
                                                            }
                                                        }
                                                    });
                                                    break;
                                                case "Question":
                                                    Swal.fire(
                                                        "Информация",
                                                        obj.message,
                                                        "question"
                                                    ).then((result) => {
                                                        if (result.value == true || result.value == undefined) {
                                                            if (obj.close_u == true) {
                                                                window.close();
                                                            }
                                                        }
                                                    });
                                                    break;
                                                default:
                                                    Swal.fire(
                                                        "Успешно",
                                                        obj.message,
                                                        "success"
                                                    ).then((result) => {
                                                        if (result.value == true || result.value == undefined) {
                                                            if (obj.url_u) {
                                                                window.location.href = obj.url_u;
                                                            }
                                                        }
                                                    });
                                                    break;
                                            }
                                        }
                                    }
                                });
                                refresh_addsurf();
                            }
                        })
/*-----------------------Первый SweetAlert----------AAAAAAAAAAAAAAA-----------*/

				});
				/*----A---delete_task---A----*/
                /*----V---raise_task---V----*/
                $(document).on("click", ".raise_task_id", function() {
                    var raise_task_id = $(this).attr("id");
                    var action = "raise_task";

                    Swal.fire({
                      title: "Поднять сайт в сёрфинге?",
                      text: "Стоимость 1 руб. Задание будет показываться первым в данной категории",
                      type: "question",
                      confirmButtonText:  "Да",
                      cancelButtonText:  "Нет",
                      showCancelButton: true,
                      showCloseButton: true
                    }).then((result) => {
                        if (result.value == true) {
                            $.ajax({
                                url : "mine_shop/actions/addsurf_action_task_POST",
                                type: "POST",
                                data: "raise_task_id="+raise_task_id +"&"+ "action_f="+action,
                                cache: false,
                                success: function(result) {
                                    obj = jQuery.parseJSON(result);
                                    if (obj.go) {
                                        go(obj.go);
                                    }
                                    else {
                                        switch (obj.status) {
                                            case "warning":
                                                Swal.fire(
                                                    "Внимание",
                                                    obj.message,
                                                    "warning"
                                                ).then((result) => {
                                                    if (result.value == true || result.value == undefined) {
                                                        if (obj.close_u == true) {
                                                            window.close();
                                                        }
                                                    }
                                                });
                                                break;
                                            case "error":
                                                Swal.fire(
                                                    "Ошибка!",
                                                    obj.message,
                                                    "error"
                                                ).then((result) => {
                                                    if (result.value == true || result.value == undefined) {
                                                        if (obj.close_u == true) {
                                                            window.close();
                                                        }
                                                    }
                                                });
                                                break;
                                            case "info":
                                                Swal.fire(
                                                    "Уведомление",
                                                    obj.message,
                                                    "info"
                                                ).then((result) => {
                                                    if (result.value == true || result.value == undefined) {
                                                        if (obj.close_u == true) {
                                                            window.close();
                                                        }
                                                    }
                                                });
                                                break;
                                            case "Question":
                                                Swal.fire(
                                                    "Информация",
                                                    obj.message,
                                                    "question"
                                                ).then((result) => {
                                                    if (result.value == true || result.value == undefined) {
                                                        if (obj.close_u == true) {
                                                            window.close();
                                                        }
                                                    }
                                                });
                                                break;
                                            default:
                                                Swal.fire(
                                                    "Успешно",
                                                    obj.message,
                                                    "success"
                                                ).then((result) => {
                                                    if (result.value == true || result.value == undefined) {
                                                        if (obj.url_u) {
                                                            window.location.href = obj.url_u;
                                                        }
                                                    }
                                                });
                                                break;
                                        }
                                    }
                                }
                            });
                            refresh_addsurf();
                            ajax_index_top_auth();
                        }
                    })
                });
                /*----A---raise_task---A----*/
	        });

	/*----addsurf_JS---------AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA-----------------*/';
}

if ($JavaScript == 'surfsites') {   
	echo '
	/*----surfsites_JS---------VVVVVVVVVVVVVVVVVVVVVVVVVVVVVVV-----------------*/
			$(document).ready(function() {
				/*function refresh_surfsites() {
					$.get("ajax/ajax_surfsite", function(data) {	//функция получает данные data с файла по директории
						data = $(data);
						$("#strokeblock2_cp").html( $("#container_surfing_list", data).html() );	//извлечение конкретных балансов из одного файла
					});
				};
				setInterval(function() {
					refresh_surfsites();
				}, 1000); CHECKPOINT 29.07.19 */ 

			    $(document).on("click", ".stroke_1_Rc_containerrow_cat123_sb2_cp", function() {
			    	var id_surfing = $(this).attr("id");   

                    Swal.fire({
                        title: "Жалоба на задание",
                        input: "textarea",
                        inputPlaceholder: "Опишите причину жалобы",
                        showCancelButton: true
                    }).then((result) => {
                        if (result.value != undefined) {
                            $.ajax({
    							url : "mine_shop/actions/report_send",
    							type: "POST",
    							data: "id_surfing="+id_surfing +"&"+ "input_value="+result.value,
    							cache: false,
                                success: function(result) {
                                    obj = jQuery.parseJSON(result);
                                    if (obj.go) {
                                        go(obj.go);
                                    }
                                    else {
                                        switch (obj.status) {
                                            case "warning":
                                                Swal.fire(
                                                    "Внимание",
                                                    obj.message,
                                                    "warning"
                                                ).then((result) => {
                                                    if (result.value == true || result.value == undefined) {
                                                        if (obj.close_u == true) {
                                                            window.close();
                                                        }
                                                    }
                                                });
                                                break;
                                            case "error":
                                                Swal.fire(
                                                    "Ошибка!",
                                                    obj.message,
                                                    "error"
                                                ).then((result) => {
                                                    if (result.value == true || result.value == undefined) {
                                                        if (obj.close_u == true) {
                                                            window.close();
                                                        }
                                                    }
                                                });
                                                break;
                                            case "info":
                                                Swal.fire(
                                                    "Уведомление",
                                                    obj.message,
                                                    "info"
                                                ).then((result) => {
                                                    if (result.value == true || result.value == undefined) {
                                                        if (obj.close_u == true) {
                                                            window.close();
                                                        }
                                                    }
                                                });
                                                break;
                                            case "Question":
                                                Swal.fire(
                                                    "Информация",
                                                    obj.message,
                                                    "question"
                                                ).then((result) => {
                                                    if (result.value == true || result.value == undefined) {
                                                        if (obj.close_u == true) {
                                                            window.close();
                                                        }
                                                    }
                                                });
                                                break;
                                            default:
                                                Swal.fire(
                                                    "Успешно",
                                                    obj.message,
                                                    "success"
                                                ).then((result) => {
                                                    if (result.value == true || result.value == undefined) {
                                                        if (obj.url_u) {
                                                            window.location.href = obj.url_u;
                                                        }
                                                    }
                                                });
                                                break;
                                        }
                                    }
                                }
                            });
						}
					});
			    });


/*----V---stop_task---V----*/
                $(document).on("click", ".start_task_id", function() {
                    var start_task_id = $(this).attr("id");
                    var action_f = "start_task";

                    Swal.fire({
                      title: "Приостановить показ задания?",
                      type: "question",
                      confirmButtonText:  "Да",
                      cancelButtonText:  "Нет",
                      showCancelButton: true,
                      showCloseButton: true
                    }).then((result) => {
                        if (result.value == true) {

                            $.ajax({
                                url : "mine_shop/actions/addsurf_action_task_POST",
                                type: "POST",
                                data: "start_task_id="+start_task_id +"&"+ "action_f="+action_f,
                                cache: false,
                                success: function(result) {
                                    obj = jQuery.parseJSON(result);
                                    if (obj.go) {
                                        go(obj.go);
                                    }
                                    else {
                                        switch (obj.status) {
                                            case "warning":
                                                Swal.fire(
                                                    "Внимание",
                                                    obj.message,
                                                    "warning"
                                                ).then((result) => {
                                                    if (result.value == true || result.value == undefined) {
                                                        if (obj.close_u == true) {
                                                            window.close();
                                                        }
                                                    }
                                                });
                                                break;
                                            case "error":
                                                Swal.fire(
                                                    "Ошибка!",
                                                    obj.message,
                                                    "error"
                                                ).then((result) => {
                                                    if (result.value == true || result.value == undefined) {
                                                        if (obj.close_u == true) {
                                                            window.close();
                                                        }
                                                    }
                                                });
                                                break;
                                            case "info":
                                                Swal.fire(
                                                    "Уведомление",
                                                    obj.message,
                                                    "info"
                                                ).then((result) => {
                                                    if (result.value == true || result.value == undefined) {
                                                        if (obj.close_u == true) {
                                                            window.close();
                                                        }
                                                    }
                                                });
                                                break;
                                            case "Question":
                                                Swal.fire(
                                                    "Информация",
                                                    obj.message,
                                                    "question"
                                                ).then((result) => {
                                                    if (result.value == true || result.value == undefined) {
                                                        if (obj.close_u == true) {
                                                            window.close();
                                                        }
                                                    }
                                                });
                                                break;
                                            default:
                                                Swal.fire(
                                                    "Успешно",
                                                    obj.message,
                                                    "success"
                                                ).then((result) => {
                                                    if (result.value == true || result.value == undefined) {
                                                        if (obj.url_u) {
                                                            window.location.href = obj.url_u;
                                                        }
                                                    }
                                                });
                                                break;
                                        }

                                        /*
                                        if (obj.url_u) {
                                            window.location.href = obj.url_u;
                                        }
                                        if (obj.close_u == true) {
                                            window.close();
                                        }*/
                                    }
                                }
                            });
                            refresh_surfsites();
                        }
                    });
                });
/*----A---stop_task---A----*/
                /*----V---raise_task---V----*/
                $(document).on("click", ".raise_task_id", function() {
                    var raise_task_id = $(this).attr("id");
                    var action = "raise_task";

                    Swal.fire({
                      title: "Поднять сайт в сёрфинге?",
                      text: "Стоимость 1 руб. Задание будет показываться первым в данной категории",
                      type: "question",
                      confirmButtonText:  "Да",
                      cancelButtonText:  "Нет",
                      showCancelButton: true,
                      showCloseButton: true
                    }).then((result) => {
                        if (result.value == true) {
                            $.ajax({
                                url : "mine_shop/actions/addsurf_action_task_POST",
                                type: "POST",
                                data: "raise_task_id="+raise_task_id +"&"+ "action_f="+action,
                                cache: false,
                                success: function(result) {
                                    obj = jQuery.parseJSON(result);
                                    if (obj.go) {
                                        go(obj.go);
                                    }
                                    else {
                                        switch (obj.status) {
                                            case "warning":
                                                Swal.fire(
                                                    "Внимание",
                                                    obj.message,
                                                    "warning"
                                                ).then((result) => {
                                                    if (result.value == true || result.value == undefined) {
                                                        if (obj.close_u == true) {
                                                            window.close();
                                                        }
                                                    }
                                                });
                                                break;
                                            case "error":
                                                Swal.fire(
                                                    "Ошибка!",
                                                    obj.message,
                                                    "error"
                                                ).then((result) => {
                                                    if (result.value == true || result.value == undefined) {
                                                        if (obj.close_u == true) {
                                                            window.close();
                                                        }
                                                    }
                                                });
                                                break;
                                            case "info":
                                                Swal.fire(
                                                    "Уведомление",
                                                    obj.message,
                                                    "info"
                                                ).then((result) => {
                                                    if (result.value == true || result.value == undefined) {
                                                        if (obj.close_u == true) {
                                                            window.close();
                                                        }
                                                    }
                                                });
                                                break;
                                            case "Question":
                                                Swal.fire(
                                                    "Информация",
                                                    obj.message,
                                                    "question"
                                                ).then((result) => {
                                                    if (result.value == true || result.value == undefined) {
                                                        if (obj.close_u == true) {
                                                            window.close();
                                                        }
                                                    }
                                                });
                                                break;
                                            default:
                                                Swal.fire(
                                                    "Успешно",
                                                    obj.message,
                                                    "success"
                                                ).then((result) => {
                                                    if (result.value == true || result.value == undefined) {
                                                        if (obj.url_u) {
                                                            window.location.href = obj.url_u;
                                                        }
                                                    }
                                                });
                                                break;
                                        }
                                    }
                                }
                            });
                            refresh_surfsites();
                            ajax_index_top_auth();
                        }
                    })
                });
                /*----A---raise_task---A----*/
			});
/*----surfsites_JS---------AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA-----------------*/';
}

if ($JavaScript == 'payin_admoney') {
    echo '
/*-------------------------payin_admoney_JS--------------VVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVV------------------------*/
        $(document).on("mouseenter", "#cross_01", function() {
            $("#cross_01").hide();
            $("#cross_02").show();
        });
        $(document).on("mouseleave", "#cross_02", function() {
            $("#cross_02").hide();
            $("#cross_01").show();
        });


        $(document).on("click", ".orange_button", function() {
            //получаем id кнопки и показываем окно плюс id картинки картинки
            var ps_id = $(this).attr("id");
            var img_id ="#img_"+ps_id+"";
            
            $(img_id).show();
           

            var button_id ="#button_"+ps_id+"";
            $(button_id).show();
            
            $(".blackout").show();
            $(".background_around").show();
        });

        $(document).on("click", "#cross_02", function() {
            $(".img_ps").hide();
            
            $(".blue_button").hide();
            $(".blackout").hide();
            $(".background_around").hide();
        });
        $(document).on("click", ".background_around", function() {  /*событие клика на область вне диалогового окна*/
            $(".img_ps").hide();
            
            $(".blue_button").hide();
            $(".blackout").hide();
            $(".background_around").hide();
        });
/*-------------------------payin_admoney_JS--------------AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA-----------------------*/';

}

	echo '
	</script>

	<link rel="icon" href="/img/favicon.png" type="image/png">
	<link rel="shortcut icon" href="/img/favicon.png" type="image/png">

	</head>

	<body>

	'.$social_banner.'

	<div class="wrapper">
		<div class="container">
			<div class="header">
                <div class="logo_header">
                    <a href="/home"><div class="img_logo_header"><img src="../img/auth/home/img_logo_header.png" width="175" height="48"></div></a>
                </div>
                <div class="line_right"></div>

                <div class="menu_header">
                     <a href="/home"><div class="item_menu">Главная</div></a>
                     <a href="/about"><div class="item_menu">О проекте</div></a>
                     <a href="/guaranteed"><div class="item_menu">Гарантии</div></a>
                     <a href="/contest"><div class="item_menu">Конкурсы</div></a>
                     <a href="/feedback"><div class="item_menu">Отзывы</div></a>
                     <a href="/help"><div class="item_menu">Помощь</div></a>
                </div>
                <div class="block_img_right_avatar">
                	<div class="avatar_with_pointer_online">
                        <div class="image_overflow">';

    $db->Query("SELECT * FROM `users_data` WHERE `uid` = '$_SESSION[id]'");                 
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
                        </div>
	                	<div class="point_online_avatar"></div>
	                </div>
	                <div class="overflow_right_avatar">
	                	<ul class="right_avatar_fallout_menu">
	            			<a href="/my_cabinet"><li>Мой кабинет</li></a>
	            			<a href="/setting_account"><li>Настройки</li></a>
	            			<a href="/my_refs"><li>Рефералы</li></a>
		                	<li id="division_bottom_block_rafm"></li>
		                	<a href="/logout"><li>Выход</li></a>
			            </ul>
			        </div>
                </div>

			</div>
            <div class="left_panel">
                <div class="content_1_left_panel">

                    <div class="overflow_block_fallout_menu" style="height: 110px;">
                        <div class="fallout_menu" style="border-left: 0px solid transparent; background-color: transparent;">
                            <div class="border_left_fm"></div>
                            <div class="stroke_1_left_panel">
                                <div class="elementimage_1_stroke_1_left_panel">
                                    <a href="/user_wall">
                                        <div class="borders_avatar_top_auth">';
                  

        $db->Query("SELECT * FROM `users_data` WHERE `uid` = '$_SESSION[id]'");                 
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
                                        </div>
                                    </a>
                                </div>
                                <div class="elementtext_2_stroke_1_left_panel">
                                    <div class="string_1_elementtext_2_stroke_1_left_panel"><a href="/user_wall">'.$_SESSION['login'].'</a></div>
                                    <div class="string_2_elementtext_2_stroke_1_left_panel">ONLINE</div>
                                </div>
                                <div class="arrow_stroke_1_left_panel">
                                    <img class="image_arrow_01" src="../img/auth/home/arrow_stroke_1_left_panel.png" width="10" height="10">
                                    <img class="image_arrow_02" src="../img/auth/home/arrow_stroke_1_left_panel_02.png" width="10" height="10">
                                </div>
                                <div class="area_click_right"></div>
                            </div>

                            <div class="menu_list_fallout_menu" style="display: block";>
                                <a href="/my_cabinet"><div class="stroke_menu_list_fallout_menu">Кабинет пользователя</div></a>
                                <a href="/user_wall"><div class="stroke_menu_list_fallout_menu">Стена пользователя</div></a>
                                <a href="/setting_account"><div class="stroke_menu_list_fallout_menu">Настройки аккаунта</div></a>';
                                
                                /*<a href="/private_messages"><div class="stroke_menu_list_fallout_menu">Личные сообщения</div></a>
                                /*<a href="/history_operation"><div class="stroke_menu_list_fallout_menu">История операций</div></a>/*___BETA___*/
    echo '
                                <a href="/my_refs"><div class="stroke_menu_list_fallout_menu">Мои рефералы</div></a>
                                <a href="/logout"><div class="stroke_menu_list_fallout_menu">Выход</div></a>
                            </div>
                        </div>
                    </div>

                    <div class="stroke_2_left_panel">
                        <div class="elementtext_1_stroke_2_left_panel">
                            Информация о балансе
                        </div>
                        <div class="box_ul_elementimagetext_2-3_stroke_2_left_panel">
                            <ul id="ul_elementimagetext_2_stroke_2_left_panel">
                                <li><img src="../img/auth/home/plus_balance.png" height="12" width="12" title="Баланс для покупок"></li>
                                <li><p class="li_2_elementtext_2_stroke_2_left_panel">на покупки:</p></li>
                                <div class="div_3_elementtext_2_stroke_2_left_panel">';
global $db;
    $db->Query("SELECT * FROM `users_data` WHERE `uid` = '$_SESSION[id]'");
    $row_users_data = $db->FetchAssoc();


/*контейнер ajax-----VVVVVVVVVVVV--баланс для покупок---*/
    echo '
                                    <div class="container_balance_buy"> 
                                        '.round($row_users_data['balance_buy'], 2).'<span> руб.</span>
                                    </div>';
/*контейнер ajax-----AAAAAAAAAAAA-----*/
    echo'   
                                </div>
                            </ul>
                        </div>
                        
                        <div class="box_ul_elementimagetext_2-3_stroke_2_left_panel">
                            <ul id="ul_elementimagetext_3_stroke_2_left_panel">
                                <li><img src="../img/auth/home/minus_balance.png" height="12" width="12" title="Баланс для вывода"></li>
                                <li><p class="li_2_elementtext_2_stroke_2_left_panel">на вывод:</p></li>
                                <div class="div_3_elementtext_3_stroke_2_left_panel">';
/*контейнер ajax-----VVVVVVVVVVVV--баланс для вывода---*/
    echo '
                                    <div class="container_balance_withdrawal">
                                        '.round($row_users_data['balance_withdrawal'], 2, PHP_ROUND_HALF_DOWN).'<span> руб.</span>
                                    </div>';
/*контейнер ajax-----AAAAAAAAAAAA-----*/
    echo'                               
                                </div>
                            </ul>
                        </div>

                        <div class="box_ul_elementimagetext_4_stroke_2_left_panel"> 
                            <ul id="ul_elementimagetext_4_stroke_2_left_panel">
                                <li><img src="../img/auth/home/marketing_icon.png" height="12" width="12" title="Рекламный баланс"></li>
                                <li><p class="li_2_elementtext_2_stroke_2_left_panel">на рекламу:</p></li>
                                <div class="div_3_elementtext_4_stroke_2_left_panel">';
/*контейнер ajax-----VVVVVVVVVVVV--баланс для рекламы---*/
    echo '
                                    <div class="container_balance_advertising">
                                        '.round($row_users_data['balance_advertising'], 2, PHP_ROUND_HALF_DOWN).'<span> руб.</span>
                                    </div>';
/*контейнер ajax-----AAAAAAAAAAAA-----*/
    echo'                               
                                </div>
                            </ul>
                        </div>


                        <div class="box_ul_elementimagetext_5_stroke_2_left_panel"> 
                            <ul id="ul_elementimagetext_4_stroke_2_left_panel">
                                <li><img src="../img/auth/home/game_balance.png" height="12" width="12" title="Игровой баланс"></li>
                                <li><p class="li_2_elementtext_2_stroke_2_left_panel">на игры:</p></li>
                                <div class="div_3_elementtext_4_stroke_2_left_panel">';
/*контейнер ajax-----VVVVVVVVVVVV--игровой баланс---*/
    echo '
                                    <div class="container_balance_game">
                                        '.round($row_users_data['balance_game'], 2, PHP_ROUND_HALF_DOWN).'<span> руб.</span>
                                    </div>';
/*контейнер ajax-----AAAAAAAAAAAA-----*/
    echo'                               
                                </div>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="content_2_left_panel">
                    <div class="stroke_3_left_panel">
                        <div class="elementtext_1_stroke_3_left_panel">
                            Главные разделы
                        </div>
                        <a href="/my_cabinet">';

    if ($_SESSION['page'] == 'my_cabinet') echo '<div class="box_ul_text_menu_current">';
    else echo '<div class="box_ul_text_menu">';

    if ($_SESSION['page'] == 'my_cabinet') {                        
        echo
                                '<div class="markerleft_box_ul"></div>';
    }
    
    echo '
                                <ul id="ul_text_menu">
                                    <li>';


    if ($_SESSION['page'] != 'my_cabinet') {
        echo '
                                        <img class="mouse_tab" src="../img/auth/home/my_cabinet.png" height="18" width="18">
                                        <img class="mouse_tab_02" style="display: none" src="../img/auth/home/my_cabinet_02.png" height="18" width="18">';
    }
    else {
        echo '
                                        <img src="../img/auth/home/my_cabinet_02.png" height="18" width="18">';
    }


    echo '
                                    </li>
                                    <li><p class="text_text_menu">Мой кабинет</p></li>
                                </ul>
                            </div>
                        </a>
                        <a href="/my_field">';

    if ($_SESSION['page'] == 'my_field') echo '<div class="box_ul_text_menu_current">';
    else echo '<div class="box_ul_text_menu">';

    if ($_SESSION['page'] == 'my_field') {                      
        echo
                                '<div class="markerleft_box_ul"></div>';
    }

    echo '
                                <ul id="ul_text_menu">
                                    <li>';


    if ($_SESSION['page'] != 'my_field') {
        echo '
                                        <img class="mouse_tab" src="../img/auth/home/my_field.png" height="18" width="18">
                                        <img class="mouse_tab_02" style="display: none" src="../img/auth/home/my_field_02.png" height="18" width="18">';
    }
    else {
        echo '
                                        <img src="../img/auth/home/my_field_02.png" height="18" width="18">';
    }


    echo '</li>
                                    <li><p class="text_text_menu">Мой Найт-Сити</p></li>
                                </ul>
                            </div>
                        </a>
                        <a href="/market">';

    if ($_SESSION['page'] == 'market') echo '<div class="box_ul_text_menu_current">';
    else echo '<div class="box_ul_text_menu">';

    if ($_SESSION['page'] == 'market') {                        
        echo
                                '<div class="markerleft_box_ul"></div>';
    }

    echo '                                              
                                <ul id="ul_text_menu">
                                    <li>';


    if ($_SESSION['page'] != 'market') {
        echo '
                                        <img class="mouse_tab" src="../img/auth/home/market.png" height="18" width="18">
                                        <img class="mouse_tab_02" style="display: none" src="../img/auth/home/market_02.png" height="18" width="18">';
    }
    else {
        echo '
                                        <img src="../img/auth/home/market_02.png" height="18" width="18">';
    }


    echo '</li>
                                    <li><p class="text_text_menu">Системный рынок</p></li>
                                </ul>
                            </div>
                        </a>
                        <a href="/leveltab">';

    if ($_SESSION['page'] == 'leveltab') echo '<div class="box_ul_text_menu_current">';
    else echo '<div class="box_ul_text_menu">';

    if ($_SESSION['page'] == 'leveltab') {                      
        echo
                                '<div class="markerleft_box_ul"></div>';
    }

    echo '                          
                                <ul id="ul_text_menu">
                                    <li>';


    if ($_SESSION['page'] != 'leveltab') {
        echo '
                                        <img class="mouse_tab" src="../img/auth/home/leveltab.png" height="18" width="18">
                                        <img class="mouse_tab_02" style="display: none" src="../img/auth/home/leveltab_02.png" height="18" width="18">';
    }
    else {
        echo '
                                        <img src="../img/auth/home/leveltab_02.png" height="18" width="18">';
    }


    echo '</li>
                                    <li><p class="text_text_menu">Таблица уровней</p></li>
                                </ul>
                            </div>
                        </a>
                        <a href="/leadrace">';

    if ($_SESSION['page'] == 'leadrace') echo '<div class="box_ul_text_menu_current">';
    else echo '<div class="box_ul_text_menu">';

    if ($_SESSION['page'] == 'leadrace') {                      
        echo
                                '<div class="markerleft_box_ul"></div>';
    }

    echo '                          
                                <ul id="ul_text_menu">
                                    <li>';


    if ($_SESSION['page'] != 'leadrace') {
        echo '
                                        <img class="mouse_tab" src="../img/auth/home/leadrace.png" height="18" width="18">
                                        <img class="mouse_tab_02" style="display: none" src="../img/auth/home/leadrace_02.png" height="18" width="18">';
    }
    else {
        echo '
                                        <img src="../img/auth/home/leadrace_02.png" height="18" width="18">';
    }


    echo '</li>
                                    <li><p class="text_text_menu">Гонка лидеров</p></li>
                                </ul>
                            </div>
                        </a>
						<a href="/games">';

    if ($_SESSION['page'] == 'games') echo '<div class="box_ul_text_menu_current">';
    else echo '<div class="box_ul_text_menu">';

    if ($_SESSION['page'] == 'games') {                        
        echo
                                '<div class="markerleft_box_ul"></div>';
    }

    echo '                                              
                                <ul id="ul_text_menu">
                                    <li>';


    if ($_SESSION['page'] != 'games') {
        echo '
                                        <img class="mouse_tab" src="../img/auth/home/games.png" height="18" width="18">
                                        <img class="mouse_tab_02" style="display: none" src="../img/auth/home/games_02.png" height="18" width="18">';
    }
    else {
        echo '
                                        <img src="../img/auth/home/games_02.png" height="18" width="18">';
    }

    echo '</li>
                                    <li><p class="text_text_menu">Игры на деньги</p></li>


                                </ul>
                            </div>
                        </a>
                        <a href="/learnsec">';

    if ($_SESSION['page'] == 'learnsec') echo '<div class="box_ul_text_menu_current">';
    else echo '<div class="box_ul_text_menu">';

    if ($_SESSION['page'] == 'learnsec') {                      
        echo
                                '<div class="markerleft_box_ul"></div>';
    }

    echo '                          
                                <ul id="ul_text_menu">
                                    <li>';


    if ($_SESSION['page'] != 'learnsec') {
        echo '
                                        <img class="mouse_tab" src="../img/auth/home/learnsec.png" height="18" width="18">
                                        <img class="mouse_tab_02" style="display: none" src="../img/auth/home/learnsec_02.png" height="18" width="18">';
    }
    else {
        echo '
                                        <img src="../img/auth/home/learnsec_02.png" height="18" width="18">';
    }


    echo '</li>
                                    <li><p class="text_text_menu">Обучающий раздел</p></li>
                                </ul>
                            </div>
                        </a>';

                       /*<a href="/gmchat">';

    if ($_SESSION['page'] == 'gmchat') echo '<div id="box_ul_text_menu_last" class="box_ul_text_menu_current">';
    else echo '<div id="box_ul_text_menu_last" class="box_ul_text_menu">';

    if ($_SESSION['page'] == 'gmchat') {                        
        echo
                                '<div class="markerleft_box_ul"></div>';
    }

    echo '                          
                                <ul id="ul_text_menu">
                                    <li>';


    if ($_SESSION['page'] != 'gmchat') {
        echo '
                                        <img class="mouse_tab" src="../img/auth/home/gmchat.png" height="18" width="18">
                                        <img class="mouse_tab_02" style="display: none" src="../img/auth/home/gmchat_02.png" height="18" width="18">';
    }
    else {
        echo '
                                        <img src="../img/auth/home/gmchat_02.png" height="18" width="18">';
    }


    echo '</li>
                                    <li><p class="text_text_menu">Чат проекта</p></li>
                                </ul>
                            </div>
                        </a>/*_______BETA________*/
    echo '                                                                                    
                    </div>

                    <!-- разделение пунктов меню -->

                    <div class="stroke_3_left_panel">
                        <div class="elementtext_1_stroke_3_left_panel">
                            Операции с балансом
                        </div>
                        <a href="/pay/payin_money">';

    if ($_SESSION['page'] == 'pay/payin_money') echo '<div class="box_ul_text_menu_current">';
    else echo '<div class="box_ul_text_menu">';

    if ($_SESSION['page'] == 'pay/payin_money') {                       
        echo
                                '<div class="markerleft_box_ul"></div>';
    }

    echo '
                                <ul id="ul_text_menu">
                                    <li>';


    if ($_SESSION['page'] != 'pay/payin_money') {
        echo '
                                        <img class="mouse_tab" src="../img/auth/home/payin.png" height="18" width="18">
                                        <img class="mouse_tab_02" style="display: none" src="../img/auth/home/payin_02.png" height="18" width="18">';
    }
    else {
        echo '
                                        <img src="../img/auth/home/payin_02.png" height="18" width="18">';
    }


    echo '</li>
                                    <li><p class="text_text_menu">Пополнить баланс</p></li>
                                </ul>
                            </div>
                        </a>
                        <a href="/pay/payout_money">';

    if ($_SESSION['page'] == 'pay/payout_money') echo '<div class="box_ul_text_menu_current">';
    else echo '<div class="box_ul_text_menu">';

    if ($_SESSION['page'] == 'pay/payout_money') {                      
        echo
                                '<div class="markerleft_box_ul"></div>';
    }

    echo '
                                <ul id="ul_text_menu">
                                    <li>';


    if ($_SESSION['page'] != 'pay/payout_money') {
        echo '
                                        <img class="mouse_tab" src="../img/auth/home/payout.png" height="18" width="18">
                                        <img class="mouse_tab_02" style="display: none" src="../img/auth/home/payout_02.png" height="18" width="18">';
    }
    else {
        echo '
                                        <img src="../img/auth/home/payout_02.png" height="18" width="18">';
    }


    echo '</li>
                                    <li><p class="text_text_menu">Заказать выплату</p></li>
                                </ul>
                            </div>
                        </a>
                        <a href="/exchange_money">';

    if ($_SESSION['page'] == 'exchange_money') echo '<div id="box_ul_text_menu_last" class="box_ul_text_menu_current">';
    else echo '<div id="box_ul_text_menu_last" class="box_ul_text_menu">';

    if ($_SESSION['page'] == 'exchange_money') {                        
        echo
                                '<div class="markerleft_box_ul"></div>';
    }

    echo '
                                <ul id="ul_text_menu">
                                    <li>';


    if ($_SESSION['page'] != 'exchange_money') {
        echo '
                                        <img class="mouse_tab" src="../img/auth/home/exchange_money.png" height="18" width="18">
                                        <img class="mouse_tab_02" style="display: none" src="../img/auth/home/exchange_money_02.png" height="18" width="18">';
    }
    else {
        echo '
                                        <img src="../img/auth/home/exchange_money_02.png" height="18" width="18">';
    }


    echo '</li>
                                    <li><p class="text_text_menu">Обмена баланса</p></li>
                                </ul>
                            </div>
                        </a>                                                                
                    </div>

                    <!-- разделение пунктов меню -->

                    <div class="stroke_3_left_panel">
                        <div class="elementtext_1_stroke_3_left_panel">
                            Рекламный раздел
                        </div>
                        <a href="/surfsites">';

    if ($_SESSION['page'] == 'surfsites') echo '<div class="box_ul_text_menu_current">';
    else echo '<div class="box_ul_text_menu">';

    if ($_SESSION['page'] == 'surfsites') {                     
        echo
                                '<div class="markerleft_box_ul"></div>';
    }

    echo '
                                <ul id="ul_text_menu">
                                    <li>';


    if ($_SESSION['page'] != 'surfsites') {
        echo '
                                        <img class="mouse_tab" src="../img/auth/home/surfsites.png" height="18" width="18">
                                        <img class="mouse_tab_02" style="display: none" src="../img/auth/home/surfsites_02.png" height="18" width="18">';
    }
    else {
        echo '
                                        <img src="../img/auth/home/surfsites_02.png" height="18" width="18">';
    }


    echo '</li>
                                    <li><p class="text_text_menu">Сёрфинг сайтов</p></li>
                                </ul>
                            </div>
                        </a>
                        <a href="/addsurf">';

    if ($_SESSION['page'] == 'addsurf') echo '<div class="box_ul_text_menu_current">';
    else echo '<div class="box_ul_text_menu">';

    if ($_SESSION['page'] == 'addsurf') {                       
        echo
                                '<div class="markerleft_box_ul"></div>';
    }

    echo '
                                <ul id="ul_text_menu">
                                    <li>';


    if ($_SESSION['page'] != 'addsurf') {
        echo '
                                        <img class="mouse_tab" src="../img/auth/home/addsurf.png" height="18" width="18">
                                        <img class="mouse_tab_02" style="display: none" src="../img/auth/home/addsurf_02.png" height="18" width="18">';
    }
    else {
        echo '
                                        <img src="../img/auth/home/addsurf_02.png" height="18" width="18">';
    }


    echo '</li>
                                    <li><p class="text_text_menu">Добавить сайт в серфинг</p></li>
                                </ul>
                            </div>
                        </a>
                        <a href="/pay/payin_admoney">';

    if ($_SESSION['page'] == 'pay/payin_admoney') echo '<div id="box_ul_text_menu_last" class="box_ul_text_menu_current">';
    else echo '<div id="box_ul_text_menu_last" class="box_ul_text_menu">';

    if ($_SESSION['page'] == 'pay/payin_admoney') {                       
        echo
                                '<div class="markerleft_box_ul"></div>';
    }

    echo '                          
                                <ul id="ul_text_menu">
                                    <li>';


    if ($_SESSION['page'] != 'pay/payin_admoney') {
        echo '
                                        <img class="mouse_tab" src="../img/auth/home/admoney.png" height="18" width="18">
                                        <img class="mouse_tab_02" style="display: none" src="../img/auth/home/admoney_02.png" height="18" width="18">';
    }
    else {
        echo '
                                        <img src="../img/auth/home/admoney_02.png" height="18" width="18">';
    }


    echo '</li>
                                    <li><p class="text_text_menu">Рекламный баланс</p></li>
                                </ul>
                            </div>
                        </a>                                                                
                    </div>

                    <!-- разделение пунктов меню -->

                    <div class="stroke_3_left_panel">
                        <div class="elementtext_1_stroke_3_left_panel">
                            Рефереальная система
                        </div>
                        <a href="/partneract">';

    if ($_SESSION['page'] == 'partneract') echo '<div class="box_ul_text_menu_current">';
    else echo '<div class="box_ul_text_menu">';

    if ($_SESSION['page'] == 'partneract') {                        
        echo
                                '<div class="markerleft_box_ul"></div>';
    }

    echo '              
                                <ul id="ul_text_menu">
                                    <li>';


    if ($_SESSION['page'] != 'partneract') {
        echo '
                                        <img class="mouse_tab" src="../img/auth/home/partneract.png" height="18" width="18">
                                        <img class="mouse_tab_02" style="display: none" src="../img/auth/home/partneract_02.png" height="18" width="18">';
    }
    else {
        echo '
                                        <img src="../img/auth/home/partneract_02.png" height="18" width="18">';
    }


    echo '</li>
                                    <li><p class="text_text_menu">Партнерская программа</p></li>
                                </ul>
                            </div>
                        </a>
                        <a href="/my_refs">';

    if ($_SESSION['page'] == 'my_refs') echo '<div class="box_ul_text_menu_current">';
    else echo '<div class="box_ul_text_menu">';

    if ($_SESSION['page'] == 'my_refs') {                       
        echo
                                '<div class="markerleft_box_ul"></div>';
    }

    echo '  
                                <ul id="ul_text_menu">
                                    <li>';


    if ($_SESSION['page'] != 'my_refs') {
        echo '
                                        <img class="mouse_tab" src="../img/auth/home/my_refs.png" height="18" width="18">
                                        <img class="mouse_tab_02" style="display: none" src="../img/auth/home/my_refs_02.png" height="18" width="18">';
    }
    else {
        echo '
                                        <img src="../img/auth/home/my_refs_02.png" height="18" width="18">';
    }


    echo '</li>
                                    <li><p class="text_text_menu">Список рефералов</p></li>
                                </ul>
                            </div>
                        </a>
                        <a href="/admat">';

    if ($_SESSION['page'] == 'admat') echo '<div id="box_ul_text_menu_last" class="box_ul_text_menu_current">';
    else echo '<div id="box_ul_text_menu_last" class="box_ul_text_menu">';

    if ($_SESSION['page'] == 'admat') {                     
        echo
                                '<div class="markerleft_box_ul"></div>';
    }

    echo '
                                <ul id="ul_text_menu">
                                    <li>';


    if ($_SESSION['page'] != 'admat') {
        echo '
                                        <img class="mouse_tab" src="../img/auth/home/admat.png" height="18" width="18">
                                        <img class="mouse_tab_02" style="display: none" src="../img/auth/home/admat_02.png" height="18" width="18">';
    }
    else {
        echo '
                                        <img src="../img/auth/home/admat_02.png" height="18" width="18">';
    }


    echo '</li>
                                    <li><p class="text_text_menu">Рекламные материалы</p></li>
                                </ul>
                            </div>
                        </a>                                                                
                    </div>

                    <!-- разделение пунктов меню -->

                    <div class="stroke_3_left_panel">
                        <div class="elementtext_1_stroke_3_left_panel">
                            Полезные разделы
                        </div>';

    /*echo '
                        <a href="/free_chance">';

    if ($_SESSION['page'] == 'free_chance') echo '<div class="box_ul_text_menu_current">';
    else echo '<div class="box_ul_text_menu">';

    if ($_SESSION['page'] == 'free_chance') {                        
        echo
                                '<div class="markerleft_box_ul"></div>';
    }

    echo '                                              
                                <ul id="ul_text_menu">
                                    <li>';


    if ($_SESSION['page'] != 'free_chance') {
        echo '
                                        <img class="mouse_tab" src="../img/auth/home/free_chance.png" height="18" width="18">
                                        <img class="mouse_tab_02" style="display: none" src="../img/auth/home/free_chance_02.png" height="18" width="18">';
    }
    else {
        echo '
                                        <img src="../img/auth/home/free_chance_02.png" height="18" width="18">';
    }

    echo '</li>
                                    <li><!--<p class="text_text_menu">--><p class="text_text_menu_new">Бесплатная лотерея<sup style="color: #ef3434; margin-left: 5px;">+NEW</sup></p></li>


                                </ul>
                            </div>
                        </a>';*/

    echo
                        '<a href="/dailybonus">';

    if ($_SESSION['page'] == 'dailybonus') echo '<div class="box_ul_text_menu_current">';
    else echo '<div class="box_ul_text_menu">';

    if ($_SESSION['page'] == 'dailybonus') {                     
        echo
                                '<div class="markerleft_box_ul"></div>';
    }

    echo '
                                <ul id="ul_text_menu">
                                    <li>';


    if ($_SESSION['page'] != 'dailybonus') {
        echo '
                                        <img class="mouse_tab" src="../img/auth/home/dailybonus.png" height="18" width="18">
                                        <img class="mouse_tab_02" style="display: none" src="../img/auth/home/dailybonus_02.png" height="18" width="18">';
    }
    else {
        echo '
                                        <img src="../img/auth/home/dailybonus_02.png" height="18" width="18">';
    }


    echo '</li>
                                    <li><p class="text_text_menu">Ежедневный бонус</p></li>
                                </ul>
                            </div>
                        </a>
                        <a href="/contest_game">';

    if ($_SESSION['page'] == 'contest_game') echo '<div class="box_ul_text_menu_current">';
    else echo '<div class="box_ul_text_menu">';

    if ($_SESSION['page'] == 'contest_game') {                     
        echo
                                '<div class="markerleft_box_ul"></div>';
    }

    echo '
                                <ul id="ul_text_menu">
                                    <li>';


    if ($_SESSION['page'] != 'contest_game') {
        echo '
                                        <img class="mouse_tab" src="../img/auth/home/contest_game.png" height="18" width="20">
                                        <img class="mouse_tab_02" style="display: none" src="../img/auth/home/contest_game_02.png" height="18" width="20">';
    }
    else {
        echo '
                                        <img src="../img/auth/home/contest_game_02.png" height="18" width="20">';
    }


    echo '</li>
                                    <li>
                                        <p class="text_text_menu"> <!--<p class="text_text_menu_new">--> Конкурсы <!--<sup style="color: #ef3434; margin-left: 5px;">+NEW</sup>--> </p>
                                        

                                    </li>
                                </ul>
                            </div>
                        </a>
                        <a href="/prostats">';

    if ($_SESSION['page'] == 'prostats') echo '<div class="box_ul_text_menu_current">';
    else echo '<div class="box_ul_text_menu">';

    if ($_SESSION['page'] == 'prostats') {                     
        echo
                                '<div class="markerleft_box_ul"></div>';
    }

    echo '
                                <ul id="ul_text_menu">
                                    <li>';


    if ($_SESSION['page'] != 'prostats') {
        echo '
                                        <img class="mouse_tab" src="../img/auth/home/prostats.png" height="18" width="18">
                                        <img class="mouse_tab_02" style="display: none" src="../img/auth/home/prostats_02.png" height="18" width="18">';
    }
    else {
        echo '
                                        <img src="../img/auth/home/prostats_02.png" height="18" width="18">';
    }


    echo '</li>
                                    <li><p class="text_text_menu">Статистика проекта</p></li>
                                </ul>
                            </div>
                        </a>
                        <a href="/calc_profit">';

    if ($_SESSION['page'] == 'prostats') echo '<div id="box_ul_text_menu_last" class="box_ul_text_menu_current">';
    else echo '<div id="box_ul_text_menu_last" class="box_ul_text_menu">';

    if ($_SESSION['page'] == 'prostats') {                     
        echo
                                '<div class="markerleft_box_ul"></div>';
    }


    if ($_SESSION['page'] == 'calc_profit') {                     
        echo
                                '<div class="markerleft_box_ul"></div>';
    }
    
    echo '                          
                                <ul id="ul_text_menu">
                                    <li>';


    if ($_SESSION['page'] != 'calc_profit') {
        echo '
                                        <img class="mouse_tab" src="../img/auth/home/calc_profit.png" height="18" width="18">
                                        <img class="mouse_tab_02" style="display: none" src="../img/auth/home/calc_profit_02.png" height="18" width="18">';
    }
    else {
        echo '
                                        <img src="../img/auth/home/calc_profit_02.png" height="18" width="18">';
    }

if ( !isset($elementtext_2_stroke_1_center_panel) ) {
    $elementtext_2_stroke_1_center_panel = false;
}
    echo '</li>
                                    <li><p class="text_text_menu">Калькулятор дохода</p></li>
                                </ul>
                            </div>
                        </a>                                                                
                    </div>
                </div>
            </div><!--

        -->				
			<div class="centerleft_panel">
				<div class="center_panel">
					<div class="stroke_1_center_panel">
						<div class="elementtext_1_stroke_1_center_panel">
							Кабинет пользователя
						</div>
						<div class="elementtext_2_stroke_1_center_panel">
							'.$elementtext_2_stroke_1_center_panel.'
						</div>
						'.$div_elementbutton_3_stroke_1_center_panel.'
					</div>
					<div class="stroke_2_center_panel">
                        <div class="elementtext_1_stroke_2_center_panel">
                            <a href="/"><img class="icon_e2s2cp" src="../img/auth/home/icon_subheader.png" width="15px" height="14px"></a>
						  '.$stroke_2_center_panel.'
                        </div>
					</div>					
				<!--

		-->
	';
}

function bottom_auth($toogle = 'exist_footer', $block_popunder_toogle = false) {
	MessageShow();

	if ($toogle == 'exist_footer') {
	   echo '
                    <footer class="footer">
                    © '.date("Y").' Cyberpunk-Money - All Rights Reserved. 
                    </footer>';
    }
    echo '  
				</div>
			</div><!--
			
		-->
		</div>
	</div>
		</body>
		'.$block_popunder.'
		</html>';
}


//-------------llllllllllllllllllllllll------------функция для отображения страницы
?>