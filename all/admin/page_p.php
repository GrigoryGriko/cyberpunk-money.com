<?php
    header("Content-Type: text/html; charset=utf-8");  // стандартная установка кодировки
?>
<?php
    /*if ($_SESSION['ADMIN_LOGIN_IN']) {*/
        /*function __autoload($name){ include("classes/_class.".$name.".php");}
        $config = new config;

        $db = new db($config->HostDB, $config->UserDB, $config->PassDB, $config->BaseDB);*/

        echo '<a href="/">На главную</a><br>Hello word. <a href="/page_a_list_users">Перейти в список новых пользователей</a>';

        echo '<br><a href="/">На главную</a><br>Запросы на выплаты <a href="/page_a">Перейти в статистику пользователей</a><br>';
        echo '<br><a href="/page_p_quest"><button type="submit" style="width: 500px; height: 30px; background-color: #929292; border: 1px #000 solid; color: #fff; cursor: pointer;">ПЕРЕЙТИ В ЗАПРОСЫ НА ВЫПЛАТЫ ПОД ВОПРОСОМ</button></a><br>';
        echo '<br><br>';

/*выводить все со статусом 0*/
        $db->Query("SELECT * FROM `history_money_payout` WHERE `id` != 1 AND `bot` = 0 AND `status` = 0 AND `off` = 0"); //
        $numrows_h_m_p = $db->NumRows();
        if ( !empty($numrows_h_m_p) ) {
            $n = 0;
            echo '<div style="border: 1px solid">Системное уведомление: ';
                MessageShow();
            echo '</div>';

            echo '  <br>1761, 1764, 1782, 2299<br>

                    <a href="/request?make_payout=1"><button type="submit" style="width: 150px; height: 50px; background-color: green; border: 1px #000 solid; color: #fff; cursor: pointer;">Сделать выплаты</button></a>';

            echo '

            <table border="1" width="100%" style="border-collapse: collapse;">
                <tr>
                    <th>id</th> <th>uid</th> <th>email</th> <th>money_withdrawn(сколько выводит)</th> <th>payment_system</th> <th>account_wallet</th> <th>date</th> <th>balance_buy</th> <th>payin_money</th> <th>payout_money</th> <th>money_earn_refs</th> <th>date_reg</th> <th>off</th> <th>uid</th>
                </tr>';
            while( $as_h_m_p = $db->FetchAssoc() ) {
                echo '
                    
                <tr>
                        <td>'.$as_h_m_p['id'].'</td> <td>'.$as_h_m_p['uid'].'</td> <td>'.$as_h_m_p['email'].'</td> <td>'.$as_h_m_p['money_withdrawn'].'</td> <td>'.$as_h_m_p['payment_system'].'</td> <td>'.$as_h_m_p['account_wallet'].'</td> <td>'.$as_h_m_p['date'].'</td> <td>'.$as_h_m_p['balance_buy'].'</td> <td>'.$as_h_m_p['payin_money'].'</td> <td>'.$as_h_m_p['payout_money'].'</td> <td>'.$as_h_m_p['money_earn_refs'].'</td> <td>'.$as_h_m_p['date_reg'].'</td> <td>'.$as_h_m_p['off'].'<td>'.$as_h_m_p['uid'].'</td>
                        
                        <td><a href="/request?denied=1&id_list='.$as_h_m_p['id'].'"><button type="submit" style="width: 80px; height: 30px; background-color: red; border: 1px #000 solid; color: #fff; cursor: pointer;">Отказ</button></a>
                        </form></td>
                        <td><a href="/request?quest=1&id_list='.$as_h_m_p['id'].'"><button type="submit" style="width: 80px; height: 30px; background-color: yellow; border: 1px #000 solid; color: #000; cursor: pointer;">Сомнение</button></a>
                        </form></td>
                </tr>';
            }
            echo '
                
            </table>';
        }
        else {
            echo 'Запросов на выплаты нет';
        }

    /*}
    else {
        exit(header('Location: /login_a'));
    }*/
?>