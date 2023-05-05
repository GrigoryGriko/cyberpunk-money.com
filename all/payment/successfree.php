<?php /*
if(empty($GLOBALS['SysValue'])) exit(header("Location: /"));

if(isset($_REQUEST['MERCHANT_ORDER_ID'])){
$order_metod="freekassa";
$success_function=true; 
$crc = 0; 
$my_crc = 0;
$inv_id = $_REQUEST['MERCHANT_ORDER_ID'];
}


*/
if ($_REQUEST['intid'] and $_SESSION['M_AMOUNT'] and $_REQUEST['MERCHANT_ORDER_ID']) {
	$query = mysqli_query($CONNECT, "SELECT `m_operation_id` FROM `payments` WHERE `m_operation_id` = $_REQUEST[intid]");
	if ( mysqli_num_rows($query) ) MessageSend('Критическая ошибка платежа', '/payment');

	else {


		echo "Вы успешно пополнили счет на <b>".$M_AMOUNT."</b> руб.<br>";
		echo "Сохраните эту информацию, до поступления денег на Ваш счет:<br><br>";
		echo "ID операции: ".$_REQUEST['intid']."<br>";
		echo "Сумма руб.: ".$M_AMOUNT."<br>";
		echo "Номер чека : ".$_REQUEST['MERCHANT_ORDER_ID']."<br>";
		echo "Дата оплаты: ".date("Y-m-d H:i:s")."<br>";
		echo "<hr>";
		echo '<a href="/market">Перейти в магазин</a>';

	}
}
else MessageSend('Критическая ошибка платежа', '/payment');
?>