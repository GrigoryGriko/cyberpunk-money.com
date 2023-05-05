<?php

if (!$_SESSION['id']) {
	top_guest('Главная', 'all/ticketsstyle');
	echo '
		<div class="title_content"><h1>Помощь</h1></div>
		<div class="v_faq">Часто задаваемые вопросы:</div>
		<p class="v_question">Регистрация и начало работы</p>
		<ul id="v_list">
			<li class="li_faq"> <!-- Клас приписан, для того, чтобы функции query не действовали на остальные элементы li --!>
				<a>Что мне необходимо, чтобы начать играть?</a>
				<div id="v_answer" class="class_answer">Вам необходимо потратить 10 секунд на регистрацию</div>
			</li>

			<li class="li_faq">
				<a>Есть ли начальный бонус?</a>
				<div id="v_answer" class="class_answer">Да, в размере 0.10 игровых копеек.</div>
			</li>

			<li class="li_faq"> 
				<a>Зачем подтверждать электронную почту?</a>
				<div id="v_answer" class="class_answer">В целях безопасности, это единственный возможный способ восстановить пароль, если Вы его забудете.</div>
			</li>
			<li class="li_faq">
				<a>Можно ли регистрировать несколько аккаунтов?</a>
				<div id="v_answer" class="class_answer">Допускается регистрация только одного аккаунта для одного компьютера, на один номер телефона, на одну семью. Если несколько членов семьи играют в системе, они должны использовать один аккаунт.</div>
			</li> 
		</ul>

		<p class="v_question">Вопросы по работе сайта</p>
		<ul id="v_list">
			<li class="li_faq">
				<a>Как определяется победитель в играх?</a>
				<div id="v_answer" class="class_answer">Ответ на этот вопросы смотрите на странице <a href="game-rules">правил игр</a>.</div>
			</li>

			<li class="li_faq">
				<a>Каковы размеры минимальной и максимальной ставки?</a>
				<div id="v_answer" class="class_answer">Минимальная ставка составляет 0.01 очко. Лимита по максимуму для ставок нет.</div>
			</li>

			<li class="li_faq"> 
				<a>Возможно ли отключить чат?</a>
				<div id="v_answer" class="class_answer">Да, чат можно отключить, перейдя в настройки и откройте вкладку "Настройки сайта".</div>
			</li>
			<li class="li_faq">
				<a>Возможно ли не видеть сообщения конкретного пользователя?</a>
				<div id="v_answer" class="class_answer">Да, для этого Вам нужно добавить его в чёрный список, перейдя в настройки и откройте вкладку "Настройки сайта".</div>
			</li>
			<li class="li_faq">
				<a>Можно у вас занять денег?</a>
				<div id="v_answer" class="class_answer">Нет, мы не одалживаем денег и не кредитуем пользователей, где и у кого можно одолжить также не знаем.</div>
			</li>
			<li class="li_faq">
				<a>Возможно ли изменить в настройках ответ на секретный вопрос/e-mail?</a>
				<div id="v_answer" class="class_answer">Нет, это невозможно. Утрата ответа на секретный вопрос/e-mail ящика равна утрате контроля над аккаунтом.</div>
			</li>
			<li class="li_faq">
				<a>Каковы размеры минимального и максимального размера счета?</a>
				<div id="v_answer" class="class_answer">Минимальная сумма для размера счета равна 0.01 очков. Ограничения по максимальному размеру счета нет.</div>
			</li>
			<li class="li_faq">
				<a>Мне не начислили положенный выигрыш, или начислили, но не по правилам, что делать?</a>
				<div id="v_answer" class="class_answer">Ознакомьтесь с правилами начисления выигрышей в выбранной Вами игре на странице правил игр, также не забывайте о комиссии сервиса с каждого выигрыша.</div>
			</li>
			<li class="li_faq">
				<a>Какая комиссия сервиса?</a>
				<div id="v_answer" class="class_answer">Комиссия сервиса зависит от типа игры, ознакомится с комиссиями Вы можете на странице <a href="game-rules">правил игр</a>.</div>
			</li>
			<li class="li_faq">
				<a>Что такое возврат комиссии (рефбек)?</a>
				<div id="v_answer" class="class_answer">Это автоматический возврат реферальной комиссии в зависимости от уровня в системе.</div>
			</li>
			<li class="li_faq">
				<a>Я заметил(а) баг, что делать и что я за это получу?</a>
				<div id="v_answer" class="class_answer">Напишите в службу поддержки с указанием браузера, даты, описания бага и желательно скриншотом бага. При подтверждении наличия бага Вы получете бонус на свой игровой счёт.</div>
			</li>
			<li class="li_faq">
				<a>Можно зарегистрировать еще один аккаунт?</a>
				<div id="v_answer" class="class_answer">Нет, это нарушает <a href="rules">правила системы</a>.</div>
			</li>						
		</ul>

		<p class="v_question">Финансовые вопросы</p>
		<ul id="v_list">
			<li class="li_faq">
				<a>Каковы минимальная и максимальная сумма для вывода средств?</a>
				<div id="v_answer" class="class_answer">Ограничения по минимуму составляет 25 очков. Ограничения по максимуму для вывода средств нет (в пределах, разумеется, Вашего баланса на игровом счете).</div>
			</li>
			<li class="li_faq">
				<a>Как и когда производятся выплаты?</a>
				<div id="v_answer" class="class_answer">Выплаты производятся, как правило, раз в сутки на платежную систему, которую Вы выбрали при заказе выплаты. Для получения средств у Вас должны быть указаны платежные реквизиты в профиле и не должно быть нарушений.</div>
			</li>
			<li class="li_faq">
				<a>Средства не поступили на счёт, что делать?</a>
				<div id="v_answer" class="class_answer">Может быть задержка платежной системы, подождите 30-60 минут, если всё же деньги не поступили - напишите в службу поддержки, указав дату платежа, сумму, реквизиты, с которых производилась оплата и способ пополнения.</div>
			</li>
			<li class="li_faq">
				<a>Есть ли процент и какой на ввод и вывод средств?</a>
				<div id="v_answer" class="class_answer">Да, это 3 или 4% на ввод и вывод средств в зависимости от платежной системы.</div>
			</li>
			<li class="li_faq">
				<a>Что значит статус платежа "В обработке"?</a>
				<div id="v_answer" class="class_answer">Это значит, что платеж отправлен на обработку, которая может занимать до 2х часов.</div>
			</li>
		</ul>

		<p class="v_question">Вопросы по партнёрской программе, рефералам</p>
		<ul id="v_list">
			<li class="li_faq">
				<a>Что нужно, для участия в партнёрской программе?</a>
				<div id="v_answer" class="class_answer">Вам нужно всего лишь зарегистрироваться и получить коды промо материалов в разделе "Мои рефералы" личного кабинета.</div>
			</li>
			<li class="li_faq">
				<a>Какой процент партнёрских отчислений и как он определяется?</a>
				<div id="v_answer" class="class_answer">До 50%* дохода системы в зависимости от партнёрского уровня, который определяется исходя из оборота игровой валюты привлечённых Вами игроков. Подробные значения и информацию смотрите на странице <a href="partn-levels">уровней партнёров</a>Что такое бонус партнёрам?.</div>
			</li>
			<li class="li_faq">
				<a>Что такое бонус партнёрам?</a>
				<div id="v_answer" class="class_answer">Это ежедневный бонус 3 партнёрам, определяющийся оборотом игровой валюты привлечённых им игроков.</div>
			</li>
			<li class="li_faq">
				<a>Что такое биржа рефералов?</a>
				<div id="v_answer" class="class_answer">Это возможность продать Ваших рефералов другим пользователям и получить за это деньги.</div>
			</li>
			<li class="li_faq">
				<a>Какая комиссия биржи рефералов?</a>
				<div id="v_answer" class="class_answer">Комиссия биржи рефералов составляет 5% от стоимости продаваемого реферала и снимается с продавца.</div>
			</li>
			<li class="li_faq">
				<a>Можно ли изменить реферера пользователя?</a>
				<div id="v_answer" class="class_answer">Нет, это невозможно. За предложение игроку системы зарегистрироваться "под себя" партнёрский аккаунт и аккаунт игрока будут заблокированы без возможности возврата средств.</div>
			</li>
			<li class="li_faq">
				<a>Как и когда производятся выплаты партнёрам?</a>
				<div id="v_answer" class="class_answer">Выплаты заработанных средств производятся как и игрокам - раз в сутки на платежную систему, которую Вы выбрали при заказе выплаты. Для получения у Вас должны быть указаны платежные реквизиты в профиле и не должно быть нарушений.</div>
			</li>
		</ul>



<script type="text/javascript">
	$(document).ready(function() {
		

		$(".li_faq").click(function() {
			d = $(this);
			e = $(this).closest(".li_faq").find("#v_answer");
			var speed = 450;
    		var originalMaxheight = 0;
    		var hoverMaxheight = 70;
    		var originalPaddingTop = 0;
    		var hoverPaddingTop = 12;
    		var originalPaddingBottom = 0;
    		var hoverPaddingBottom = 12;  		
	    if (e.is(":visible")) {
	    	d.css("background", "url(img/tickets/arrow_text_close.png) no-repeat");
	    	e.animate({
	    		"max-height" : originalMaxheight, 
	    		"padding-top": originalPaddingTop,
	    		"padding-bottom": originalPaddingBottom
	    	}, speed);
	    	e.hide(1);

		}
		else if (!e.is(":visible")) {

		    $(".class_answer").hide(1);
		    $(".class_answer").css("max-height", originalMaxheight); 
	    	$(".class_answer").css("padding-top", originalPaddingTop);
	    	$(".class_answer").css("padding-bottom", originalPaddingBottom);
			$(".li_faq").css("background", "url(img/tickets/arrow_text_close.png) no-repeat");

			d.css("background", "url(img/tickets/arrow_text_open.png) no-repeat");
			e.show();
	    	e.animate({
	    	"max-height" : hoverMaxheight, 
	    	"padding-top": hoverPaddingTop,
	    	"padding-bottom": hoverPaddingBottom
	    	}, speed);
	    }
		});
	});
</script>
		';
	bottom_guest();
}
else if($_SESSION['id']) {
	top_auth('Главная');
	bottom_auth();
}

else {
	exit('Непредвиденная ошибка');
}

?>


		
	


