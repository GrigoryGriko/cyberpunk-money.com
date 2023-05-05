<?php
//Если получать контент html отсюда, то при вводе значения в input данные не меняются, отправляется изначальное value.
//вместо class нужн онаписать id в блоке добавдяемого контента и блоке изменяемого контента
echo '	
	<div>
		<div id="container_captcha">	
			<div class="box_captcha_image_12">
				<div class="box_c_i_12">
					<div class="captcha_image_1">
		           		<img class="style_image--captcha" src="../guest/resource/captcha_text.php" width="280px" height="65" alt="капча">
		           	</div>
		            <div class="captcha_image_2">
		            	<img class="style_image--captcha" src="../guest/resource/captcha.php" width="280px" height="65" alt="капча">
		            </div>
		        </div>
					<input class="input_captcha" type="number" placeholder="Введите любое число" id="captcha" onfocus="this.className=\'onfocus\'" onblur="this.className=\'onblur\'">

					<button class="button_captcha" onclick="post_query(\'mine_shop/actions/surf_grab_captcha\', \'captcha_confirm\', \'captcha\')">Подтвердить просмотр</button>
			</div>
		</div>
	</div>
';
?>