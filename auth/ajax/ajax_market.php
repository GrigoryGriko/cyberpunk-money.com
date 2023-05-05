<?php
	usleep(50000);
global $db;
$db->Query("SELECT `tourmaline`, `topaz`, `emerald`, `diamond` FROM `users_data` WHERE `uid` = $_SESSION[id]");
$users_data = $db->NumRows();
if ( !empty($users_data) ) {
	$assoc_users_data = $db->FetchAssoc();
}


echo '
	<div>
		<div class="container_amount_tourmaline">
			<img src="../img/auth/my_field/tourmaline.png" width="20px" height="20px">	
			'.round($assoc_users_data['tourmaline'], 2).'
		</div>
		<div class="container_amount_topaz">
			<img src="../img/auth/my_field/topaz.png" width="20px" height="20px">		
			'.round($assoc_users_data['topaz'], 2).'
		</div>
		<div class="container_amount_emerald">
			<img src="../img/auth/my_field/emerald.png" width="20px" height="20px">		
			'.round($assoc_users_data['emerald'], 2).'
		</div>
		<div class="container_amount_diamond">
			<img src="../img/auth/my_field/diamond.png" width="20px" height="20px">	
			'.round($assoc_users_data['diamond'], 2).'
		</div>
	</div>
	';
?>