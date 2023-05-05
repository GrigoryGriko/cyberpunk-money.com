<?php
	usleep(50000);
	global $db;
	$db->Query("SELECT * FROM `users_data` WHERE `uid` = '$_SESSION[id]'");
	$row_users_data = $db->FetchAssoc();

//обновление балансов
	echo '
		<div>
			<div class="container_balance_buy">	
				'.round($row_users_data['balance_buy'], 2).'<span> руб.</span>
			</div>
			<div class="container_balance_withdrawal">
				'.round($row_users_data['balance_withdrawal'], 2).'<span> руб.</span>
			</div>
			<div class="container_balance_advertising">
				'.round($row_users_data['balance_advertising'], 2).'<span> руб.</span>
			</div>
		</div>
		';
?>