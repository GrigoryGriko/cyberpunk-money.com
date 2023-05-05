<?php
/*
Гет данные которые получаем в случае успешной оплаты и можем использовать:

m_operation_id=120902924 - Айди операции
m_operation_date=11.01.2016%2006:21:21 - Дата заказа
m_operation_pay_date=11.01.2016%2006:21:31 - дата оплаты
m_orderid=1 - ордер айди (номер чека в нашем магазине)
m_amount=0.01 - сумма
m_curr=RUB - рубли
m_status=success - успешная ли оплата

------------------

Обязательно сделать проверку, есть ли в нашей базе такой order ID, если есть - останавливаем скрипт,
если нету - добавляем order id в базу, добавляем туда же дату \ время оплаты счета,
сумму которую купили, успешно или нет (были ли ошибки какие-то). Далее, начисляем игроку его плюшки,
тому кто привел игрока начисляем бонус, выводим сообщение об успешном пополнении счета.

*/
if ($_GET['m_operation_id'] and $_GET['m_amount'] and $_GET['m_orderid'] ) {



		echo "Вы успешно пополнили счет на <b>".$_GET['m_amount']."</b> руб.<br>";
		echo "Сохраните эту информацию, до поступления денег на Ваш счет:<br><br>";
		echo "ID операции: ".$_GET['m_operation_id']."<br>";
		echo "Сумма руб.: ".$_GET['m_amount']."<br>";
		echo "Номер чека : ".$_GET['m_orderid']."<br>";
		echo "Дата оплаты: ".$_GET['m_operation_date']."<br>";
		echo "ID пользователя: ".$_GET['m_params']['reference']['user_id']."<br>";
		echo "Тип баланса: ".$_GET['m_params']['reference']['balance_type']."<br>";
		echo "<hr>";
		echo '<a href="/my_field">Перейти в раздел покупок</a>';

}
else MessageSend('Критическая ошибка платежа. Обратитесь в тех. поддержку', '/payin_money');
?>