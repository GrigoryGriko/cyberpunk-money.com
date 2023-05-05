<?php
    header("Content-Type: text/html; charset=utf-8");  // стандартная установка кодировки
?>
<?php
	if ($_POST['admlogin_f']) {
		if ($_POST['login'] == 'rea') {
			if ($_POST['password'] == '27011996r') {
				$_SESSION['ADMIN_LOGIN_IN'] = 1;

				setcookie('user_a', $_POST['login'], strtotime('+30 days'), '/');
			    setcookie('password_a', $_POST['password'], strtotime('+30 days'), '/');

				go('page_a');
			}
			else {
				message('Пароль неверен', false, false, 'info');
			}
		}
		else {
			message('Логин неверен', false, false, 'info');
		}
	}
	else {
		message('Пустой запрос', false, false, 'info');
	}
?>