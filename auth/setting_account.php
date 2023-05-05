<?php
    header("Content-Type: text/html; charset=utf-8");  // стандартная установка кодировки
?>
<?php
top_auth('Настройки аккаунта', 'auth/style/setting_accountstyle');
?>

<script type='text/javascript'>
	$(document).on("mouseenter", ".button_c_2_b_II_2_b_1_002_l_c_b_a", function() {
    	$(".button_c_2_b_II_2_b_1_002_l_c_b_a").css({"background" : "#fff", "color": "#529DCE"});
    	setTimeout( function() {
			$("#img_send_mail").hide();
    		$("#img_send_mail_02").show();
		}, 100);  	
    });
    $(document).on("mouseleave", ".button_c_2_b_II_2_b_1_002_l_c_b_a", function() {
    	$(".button_c_2_b_II_2_b_1_002_l_c_b_a").css({"background" : "rgba(0, 0, 0, 0.11)", "color" : "#fff"});
    	setTimeout( function() {
			$("#img_send_mail_02").hide();
    		$("#img_send_mail").show();
		}, 100);		
    });

/*--------button_set_name-------VVVVVVVVVVVVVVVVVVV--------------------*/
	$(document).on("mouseenter", "#set_parametr", function() {
		setTimeout( function() {
			$("#setting_icon_button").hide();
			$("#setting_icon_button_02").show();
		}, 100);
	});
	$(document).on("mouseleave", "#set_parametr", function() {
		setTimeout( function() {
			$("#setting_icon_button_02").hide();
			$("#setting_icon_button").show();
		}, 100);
	});
/*---------button_set_name---AAAAAAAAAAAAAAAAAAAAAAA--------------*/
/*---------refresh_avatar---VVVVVVVVVVVVVVVVVVVVVVV--------------*/
    function refresh_setting_account() {
		$.get("ajax/ajax_setting_account", function(data) {	//функция получает данные data с файла по директории
			data = $(data);
			$(".borders_avatar").html( $("#container_current_avatar", data ).html() );
			$(".borders_avatar_top_auth").html( $("#container_current_avatar", data ).html() );
            $(".image_overflow").html( $("#container_current_avatar", data ).html() );
		});
	};
/*---------refresh_avatar---AAAAAAAAAAAAAAAAAAAAAAA--------------*/

	$(document).on("change", "input[type=file]", function() { 
		var directory_image = $('input[type=file]').val();
		var name_image = directory_image.split("\\").pop();  	/* чтобы вывести обратную косую черту, необходимо ввести два данных знака*/

		if (name_image == '') {
			$("#container_name_file").html('<img src="../img/auth/setting_account/choose_file.png" width="20px" height="20px"><div id="contain_name_file">Выбрать файл</div>');
		}
		else {
			$("#container_name_file").html(name_image);
		}
	});

	function refresh_change_payment_password() {
		setTimeout( function() {
			$.get("ajax/ajax_setting_account", function(data2) {	//функция получает данные data с файла по директории
				data2 = $(data2);
				$("#form_change_payment_password").html( $("#container_form_change_payment_password", data2 ).html() );	
			});
		}, 100);
	};

	

/*--------------появление капчи--------VVVVVVVVVVVVVV----------------*/
	function ajax_button_change_password_failsuccess() {
		$.get("ajax/ajax_fail_change_password", function(data) {	//функция получает данные data с файла по директории
		data = $(data);
		$("#button_change_password_ajax").html( $("#button_change_password", data).html() );	//извлечение конкретных балансов из одного файла
		});
	};
	function field_captcha_show() {
		$(".blackout").show();
	};
	function field_captcha_hide() {
		$(".blackout").hide();
	};
/*--------------Появление капчи--------AAAAAAAAAAAAAAA----------------*/
/*--------------Загрузка аватара--------VVVVVVVVVVVVVV----------------*/
    $(document).on("click", "#upload", function() {
	    var file_data = $('#sortpicture').prop('files')[0];
	    var form_data = new FormData();
	    form_data.append('file', file_data);
    	$.ajax({
            url: '/mine_shop/actions/upload_avatar_setting',	/*название файла без расширения*/
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
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
    	setTimeout(function() {
    		$("#container_name_file").html('<img src="../img/auth/setting_account/choose_file.png" width="20px" height="20px"><div id="contain_name_file">Выбрать файл</div>');   
    		refresh_setting_account();        
        }, 1000);		/*обновление страницы происходит раньше, чем делается запрос в бд*/
	});
/*--------------Загрузка аватара--------AAAAAAAAAAAAAAAAAA----------------*/

</script>

<?php
$db->Query("SELECT * FROM `users` WHERE `id` = '$_SESSION[id]'");
$row_users = $db->NumRows(); //$NumRows подсчитывает число строк предыдущего запроса. Функция создана в файле _class.db.php

if ( !empty($row_users) ) {		
	$assoc_users = $db->FetchAssoc();

	echo '
		<div class="box_setting_account">
			<div class="content_box_setting_account">
				<div class="leftside_c_b_s_a cbsa">';
	if ($assoc_users['isCONFIRM_email'] == 1) {
		echo '
				<div class="block_1_leftside_c_b_s_a lcbsa">

						<div class="content_b_1_l_c_b_a">
							<p>Подтверждение E-mail</p>

							<div class="block_dynamic_distance"></div>

							<p><img class="img_P_content_b_1_l_c_b_a" src="../img/auth/setting_account/succes_check.png" width="13px" height="13px">Ваш E-mail успешно подтвержден!</p>
						</div>
					</div>';
	}
	else {
		echo '
				<div class="block_1_002_leftside_c_b_s_a lcbsa">
					<div class="block_II_1_block_1_002_l_c_b_a">
						<div class="content_b_II_1_b_1_002_l_c_b_a">
							<p>Подтверждение E-mail</p>

							<div class="block_dynamic_distance"></div>
						</div>
					</div>
					<div class="container_block_1_002_l_c_b_a">
						<div class="block_II_2_block_1_002_l_c_b_a">
							<p>
								Пожалуйста подтвердите свой E-mail адрес, подтверждение необходимо для улучшения 
								безопасности Вашего аккаунта, препятсвует действиям мошенников и позволяет восстановить утерянный пароль.
								 Так же не подтвердив свой E-mail, Вы не сможете заказывать выплаты заработанных средств из проекта.
							</p>

							<div class="content_2_b_II_2_b_1_002_l_c_b_a">
								<input class="input_c_2_b_II_2_b_1_002_l_c_b_a size_input_button" type="text" id="email" value="'.$assoc_users['email'].'" readonly>
                                <input type="hidden" id="request" value="1">

								<button class="button_c_2_b_II_2_b_1_002_l_c_b_a size_input_button" onclick="post_query(\'mine_shop/actions/request_setting_account\', \'confirm_account\', \'request\')";>
                                

									<img id="img_send_mail" class="icon_button" src="../img/auth/setting_account/send_mail.png" width="15px" height="11px">
									<img id="img_send_mail_02" class="icon_button" style="display: none;" src="../img/auth/setting_account/send_mail_02.png" width="15px" height="11px">
									Отправить сообщение
								</button>
							</div>

							<p class="p_b_II_2_b_1_002_l_c_b_a">
								Нажите кнопку "Отправить сообщение", после чего на указанный e-mail адрес придет сообщение, зайдите на свой 
								почтовый ящик и перейдите по ссылке из полученного письма. Проверяйте папку СПАМ, если письма нет.
							</p>


						</div>
					</div>
					<div class="container_2_block_1_002_l_c_b_a">	
						<div class="line_c_2_b_1_002_l_c_b_a"></div>

						<p>
							Мы не производим каких-либо рассылок на E-mail адреса участников проекта, 
							а так же не передаем Ваши данные третьим лицам.
						</p>
					</div>
				</div>';	
	}

	echo '	
					<div class="block_2_leftside_c_b_s_a lcbsa">
						<h1>Изменение пароля: </h1>
						<div class="box_input">
							<input class="input_write input_button_style" id="current_password" type="password" placeholder="Введите текущий пароль">
							<input class="input_write input_button_style" id="new_password" type="password" placeholder="Введите новый пароль">
							<input class="input_write input_button_style" id="confirm_password" type="password" placeholder="Подтвердите новый пароль">

							<div id="button_change_password_ajax">
								<button id="set_parametr_1" class="set_button_blue input_button_style" type="submit" onclick="ajax_button_change_password_failsuccess(); post_query(\'mine_shop/actions/request_setting_account\', \'change_password\', \'current_password*+*new_password*+*confirm_password\');">
									<img id="setting_icon_button_2" class="icon_button" src="../img/auth/setting_account/setting_icon_button.png" style="display: block" width="15px" height="15px">
									<img id="setting_icon_button_2_02" class="icon_button" src="../img/auth/setting_account/setting_icon_button_02.png" style="display: none" width="15px" height="15px">
									Изменить пароль
								</button>
							</div>	
						</div>
					</div>
					<div id="form_change_payment_password" class="block_3_leftside_c_b_s_a lcbsa">';

	if ( !$assoc_users['payment_password']) {
		echo '
						<div id="set_paypass" class="box_expansion">
							<h1>Платёжный пароль:</h1>
                            
                            <p class="p_set_paypass">
                                <b>Обязательно устанавливайте платежный пароль!</b> Платежный пароль служит для защиты Ваших средств от мошенников, которые в результате различных махинаций могут завладеть Вашим аккаунтом.<br>
                                <br>
                                Установите платёжный пароль, введя <b>любую комбинацию из 6-30 символов</b>, которую Вы не сможете забыть, после чего при каждой попытке вывода средств из проекта система будет запрашивать платежный пароль для осуществления выплаты.
                            </p>

							<div class="box_input">
								<input class="input_write input_button_style" id="account_password" type="password" placeholder="Введите ваш пароль от аккаунта">
								<input class="input_write input_button_style" id="payment_password" type="password" placeholder="Введите платежный пароль">
								<input class="input_write input_button_style" id="confirm_payment_password" type="password" placeholder="Подтвердите платежный пароль">

								<div id="button_change_password_ajax">
									<button id="set_parametr_1" class="set_button_blue input_button_style" type="submit" onclick="post_query(\'mine_shop/actions/request_setting_account\', \'set_payment_password\', \'account_password*+*payment_password*+*confirm_payment_password\'); refresh_change_payment_password();">
										<img id="setting_icon_button_2" class="icon_button" src="../img/auth/setting_account/setting_icon_button.png" style="display: block" width="15px" height="15px">
										Установить платежный пароль
									</button>
								</div>	
							</div>
						</div>';				
	}
	else {
		echo '
						<div id="reset_paypass" class="box_expansion">
							<h1>Платёжный пароль</h1>
							<p class="p_reset_paypass">
								<b>Платежный пароль уже установлен!</b> Если вы забыли пароль, то мы можем Сбросить
								его для Вас. Для этого нажмите на кнопку ниже, после чего на Ваш почтовый
								ящик поступит письмо с инструкцией по сбросу платёжного пароля.
							</p>
							<div class="box_input">
								<div id="button_change_password_ajax">
									<input id="confirm_request" type="hidden" value="1">

									<button id="set_parametr_1" class="set_button_blue input_button_style set_paypass" type="submit" onclick="post_query(\'mine_shop/actions/request_setting_account\', \'reset_payment_password\', \'confirm_request\');">
										<img id="setting_icon_button_2" class="icon_button" src="../img/auth/setting_account/set_paypass_icon_button.png" style="display: block" width="18px" height="13px">
										Сбросить платежный пароль
									</button>
								</div>	
							</div>
						</div>';
	}

	echo '
					</div>
				</div>
				<div class="rightside_c_b_s_a cbsa">
					<div class="block_1_rightside_c_b_s_a lcbsa">
						<div class="content_b1rcbsa">
							<h1>Изменение аватарки:</h1>
							<div class="borders_avatar">';

		if ($assoc_users['upload_avatar'] == 1) {
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
		}
		else {
			echo '	
								<img src="../img/auth/home/avatar.png">';
		}

		echo '	
							</div>

							<div class="line"></div>

							<div class="file-upload">
								<label>
									<input id="sortpicture" type="file" name="sortpic">
									<span id="container_name_file">
										<img src="../img/auth/setting_account/choose_file.png" width="19px" height="16px">
										<div id="contain_name_file">Выбрать файл</div>
									</span>
								</label>
							</div>
							<button id="upload" class="button_file" type="submit">
								<img src="../img/auth/setting_account/upload_file.png" width="18px" height="15px">	
								<div id="upload_text">Обновить аватарку</div>
							</button>';		/*http://qaru.site/questions/16377/styling-an-input-type-file-button*/

	echo '				</div>
					</div>
					<div class="block_2_rightside_c_b_s_a lcbsa">
						<h1>Установка имени:</h1>
						<div class="box_input">
							<input class="input_write input_button_style" id="user_name" type="text" placeholder="Введите Ваше Имя" value="'.$assoc_users['Name'].'">
							<button id="set_parametr" class="set_button input_button_style" type="submit" onclick="post_query(\'mine_shop/actions/request_setting_account\', \'set_name\', \'user_name\');">
								<img id="setting_icon_button" class="icon_button" src="../img/auth/my_cabinet/setting_icon_button.png" style="display: block" width="15px" height="15px">
								<img id="setting_icon_button_02" class="icon_button" src="../img/auth/my_cabinet/setting_icon_button_02.png" style="display: none" width="15px" height="15px">
								Установить имя
							</button>
						</div>
					</div>
				</div>
			</div>
			
		</div>';

	echo '
		<div class="blackout" style = "display: none">
			<div class="captcha_window">

				<div class="captcha_image_1">
	               		<img class="style_image--captcha" src="../guest/resource/captcha_text.php" width="280px" height="65" alt="капча">
               	</div>
                <div class="captcha_image_2">
                	<img class="style_image--captcha" src="../guest/resource/captcha.php" width="280px" height="60" alt="капча">
                </div>

				<p class="type_captcha"><input class="input_captcha" type="number" placeholder="Введите капчу (число от 0 до 5)" id="captcha" onfocus="this.className=\'onfocus\'" onblur="this.className=\'onblur\'"></p>

				<button id="button_captcha" onclick="post_query(\'mine_shop/actions/request_setting_account\', \'change_password\', \'current_password*+*new_password*+*confirm_password*+*captcha*+*captcha_on\'); field_captcha_hide();">Подтвердить</button>

			</div>
		</div>';
	bottom_auth();
}
?>