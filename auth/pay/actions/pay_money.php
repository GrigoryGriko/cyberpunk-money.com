<?php

header("Content-Type: text/html; charset=utf-8");  // стандартная установка кодировки

$db->Query("SELECT MAX(`id`) AS `id` FROM `history_money_payin`");
$numrows = $db->NumRows();
if ( !empty($numrows) ) {
    $as_u = $db->FetchAssoc();
    $as_u['id'] += $as_u['id'];
}
else {
    $as_u['id'] = 0;
}

function pay_event_freekassa ($curr = 0) {
    global $_POST;
    global $_SESSION;
    global $as_u;

    if ($_POST['sum_payin']) {
        if ( iconv_strlen($_POST['sum_payin']) < 61 ) {
    
            $merchant_id = '323489';
            $secret_word = 'p8myo6sy';
            $order_id = time().'fk'.$_SESSION['id'];
            $order_amount = $_POST['sum_payin'];

            if ($curr != 0) { //система платежа
                $currency = $curr;  
            }
            $sign = md5($merchant_id.':'.$order_amount.':'.$secret_word.':'.$order_id);

            if ($_POST['ad_balance'] == 1) {
                $balance_type = 'balance_advertising';
            }
            else if ($_POST['ad_balance'] == -1) {
                $balance_type = 'balance_game';
            }
            else {
                $balance_type = 'balance_buy';
            }

            $_SESSION['M_AMOUNT'] = $_POST['sum_payin']; //из-за того, что по какой-то причине не все данные после оплаты передаюся, приходится импровизировать


            echo '
                <html>
                <head>
                <script type="text/javascript">
                function doLogin() {
                    document.form_payin.submit();
                }
                </script>
                </head>
                <body onLoad="doLogin();">
                    <form style="display: flex; justify-content: center; align-items: center; height: 500px;" name="form_payin" method="get" action="http://www.free-kassa.ru/merchant/cash.php">
                        <input type="hidden" name="oa" value="'.$order_amount.'">
                        <input type="hidden" name="m" value="'.$merchant_id.'">
                        <input type="hidden" name="o" value="'.$order_id.'">
                        <input type="hidden" name="s" value="'.$sign.'">
                        <input type="hidden" name="lang" value="ru">';

            if ($curr != 0) {    //система платежа
                echo '
                        <input type="hidden" name="i" value="'.$currency.'">';
            }

            echo '

                        <input type="hidden" name="us_user_id" value="'.$_SESSION['id'].'">
                        <input type="hidden" name="us_balance_type" value="'.$balance_type.'">
                        <input type="submit" style="width: 50%; height: 100px; background: #FF9000; color: #fff; font-size: 20px; font-weight: 700; border: none; cursor: pointer;" class="payeer-btn2" name="m_process" value="ПЕРЕХОД НА СТРАНИЦУ ОПЛАТЫ">
                    </form>
                </body>
                </html>';
        }
        else {
            MessageSend('Вы указали слишком большю сумму (сумма не должна превышать 60 символов)', '/pay/payin_money');
        }
    }
    else {
        MessageSend('Укажите сумму платежа', '/pay/payin_money');
    }
}

$payment_currency = array(0, 150, 180, 80, 45, 63, 116, 147, 163, 164, 172, 168, 82, 83, 84, 132);

if ($_POST['payin_sys_1']) {    //Payeer
    if ($_POST['sum_payin']) {
        if ( iconv_strlen($_POST['sum_payin']) < 61 ) {     
            //---обработчик оплаты
            $m_shop = '1417992751';
            $m_orderid = $as_u['id'];
            $m_amount = number_format($_POST['sum_payin'], 2, '.', '');
            $m_curr = 'RUB';
            $m_desc = base64_encode('Пополнение баланса');
            $m_key = 'RGqiFNoGX2jlxmqe';

            $arHash = array(
               $m_shop,
               $m_orderid,
               $m_amount,
               $m_curr,
               $m_desc
            );

            if ($_POST['ad_balance'] == 1) {
                $arParams = array(
                    'reference' => array(
                        'user_id' => ''.$_SESSION['id'].'',
                        'balance_type' => 'balance_advertising',
                   ),
                );
            }
            else if ($_POST['ad_balance'] == -1) {
                $arParams = array(
                    'reference' => array(
                        'user_id' => ''.$_SESSION['id'].'',
                        'balance_type' => 'balance_game',
                   ),
                );
            }
            else {
                $arParams = array(
                    'reference' => array(
                        'user_id' => ''.$_SESSION['id'].'',
                        'balance_type' => 'balance_buy',
                    ),
                );
            }
            

            $key = md5('01041998ch'.$m_orderid);

            $m_params = @urlencode(base64_encode(openssl_encrypt(json_encode($arParams), 'AES-256-CBC', $key, OPENSSL_RAW_DATA)));

            $arHash[] = $m_params;

            $arHash[] = $m_key;

            $sign = strtoupper(hash('sha256', implode(':', $arHash)));

            echo '


            <html>
            <head>
            <script type="text/javascript">
            function doLogin() {
                document.form_payin.submit();
            }
            </script>
            </head>
            <body onLoad="doLogin();">
                <form style="display: flex; justify-content: center; align-items: center; height: 500px;" name="form_payin" method="post" action="https://payeer.com/merchant/">
                    <input type="hidden" name="m_shop" value="'.$m_shop.'">
            <input type="hidden" name="m_orderid" value="'.$m_orderid.'">
            <input type="hidden" name="m_amount" value="'.$m_amount.'">
            <input type="hidden" name="m_curr" value="'.$m_curr.'">
            <input type="hidden" name="m_desc" value="'.$m_desc.'">
            <input type="hidden" name="m_sign" value="'.$sign.'">

            <input type="hidden" name="m_params" value="'.$m_params.'">
            <input type="hidden" name="m_cipher_method" value="AES-256-CBC">
                    <input type="submit" style="width: 50%; height: 100px; background: #FF9000; color: #fff; font-size: 20px; font-weight: 700; border: none; cursor: pointer;" class="payeer-btn2" name="m_process" value="ПЕРЕХОД НА СТРАНИЦУ ОПЛАТЫ">
                </form>
            </body>
            </html>';
    
        }
        else {
            MessageSend('Вы указали слишком большю сумму (сумма не должна превышать 60 символов)', '/pay/payin_money');
        }
    }
    else {
        MessageSend('Укажите сумму платежа', '/pay/payin_money');
    }
}


/*Список валют  80 179 63 45 150 180 116 147 163 172, 173 168 164 79 82 84 132 83 70*/
//сбер, мастеркард, киви,  яндекс день, адванс кэш, эксмо, Bitcoin, Litecoin, Ethereum, Monero Ripple, Dogecoin DOGE, DASH, Альфа-банк RUR, Мобильный Платеж Мегафон, Мобильный Платеж МТС, Мобильный Платеж Tele2, Мобильный Платеж Билайн, PayPal

else if ($_POST['payin_sys_2']) pay_event_freekassa($payment_currency[0]);    //free-kassa
else if ($_POST['payin_sys_3']) pay_event_freekassa($payment_currency[1]);
else if ($_POST['payin_sys_4']) pay_event_freekassa($payment_currency[2]);
else if ($_POST['payin_sys_5']) pay_event_freekassa($payment_currency[3]);
else if ($_POST['payin_sys_6']) pay_event_freekassa($payment_currency[4]);
else if ($_POST['payin_sys_7']) pay_event_freekassa($payment_currency[5]);
else if ($_POST['payin_sys_8']) pay_event_freekassa($payment_currency[6]);
else if ($_POST['payin_sys_9']) pay_event_freekassa($payment_currency[7]);
else if ($_POST['payin_sys_10']) pay_event_freekassa($payment_currency[8]);
else if ($_POST['payin_sys_11']) pay_event_freekassa($payment_currency[9]);
else if ($_POST['payin_sys_12']) pay_event_freekassa($payment_currency[10]);
else if ($_POST['payin_sys_13']) pay_event_freekassa($payment_currency[11]);
else if ($_POST['payin_sys_14']) pay_event_freekassa($payment_currency[12]);
else if ($_POST['payin_sys_15']) pay_event_freekassa($payment_currency[13]);
else if ($_POST['payin_sys_16']) pay_event_freekassa($payment_currency[14]);
else if ($_POST['payin_sys_17']) pay_event_freekassa($payment_currency[15]);
    
else MessageSend('Внутренняя ошибка платежной системы MRX_130', '/pay/payin_money');
/*-----------------------код внизу для примера------------------------------*/
/*

else if ($Page == 'payment' and $Module == 'freekassa_pay' and $Param['pay'] == 'money') {
    if ( !is_numeric($_POST['pred_m_amount']) ) MessageSend('Введите число', '/payment/freekassa_pay');
    else if ( $_POST['pred_m_amount'] <= 0.01) MessageSend('Сумма должна быть больше 0.01', '/payment/freekassa_pay');
    else if ( iconv_strlen($_POST['pred_m_amount']) > 60) MessageSend('Дюже много цифр', '/payment/freekassa_pay');
    else {
        //---обработчик оплаты
        $merchant_id = '60784';
        $secret_word = 'y48zd7vi'; //secretword2 - wziftj4i
        $order_id = time().'fk'.$_SESSION['id'];
        $order_amount = $_POST['pred_m_amount'];
        $sign = md5($merchant_id.':'.$order_amount.':'.$secret_word.':'.$order_id);
        main_top_auth('Пополнение', 'payment');
        MessageShow(); // функция показа уведомлений

        $_SESSION['M_AMOUNT'] = $_POST['pred_m_amount']; //из-за того, что по какой-то причине не все данные после оплаты передаюся, приходится импровизировать


        echo '<div class="expander">
        <form method="get" action="http://www.free-kassa.ru/merchant/cash.php">
        <input type="hidden" name="oa" value="'.$order_amount.'">
        <input type="hidden" name="m" value="'.$merchant_id.'">
        <input type="hidden" name="o" value="'.$order_id.'">
        <input type="hidden" name="s" value="'.$sign.'">
        <input type="hidden" name="lang" value="ru">
        <input type="submit" class="payeer-btn2" name="pay" value="НАЖМИТЕ ЭТУ КНОПКУ ДЛЯ ПРОДОЛЖЕНИЯ ОПЛАТЫ">
        </form>
        </div>';

        main_bottom_auth('payment'); 
    }
} //////////////--------------Обработчик запроса на оплату закончен, осталось сделать файл оповещения (Загрузить логотип)
else {
    MessageSend('Error 404', '/');
}*/

?>