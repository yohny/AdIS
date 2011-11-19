<?php
/**
 * obsah pre "chyba"
 *
 * @version    1.0
 * @package    AdIS
 * @subpackage templates
 * @author     Ján Neščivera <jan.nescivera@gmail.com>
 */


Context::getInstance()->getResponse()->setHeading('chyba');

$h4 = 'Došlo k chybe na servri alebo stránka na ktorej ste klikli na
    reklamný banner má chybný kód banneru a preto Vás nie je možné presmerovať
    do cieľa reklamy. Ospravedlňujeme sa spôsobené nepríjemnosti';

$msq = 'unknown error';
if(isset($_GET['msg']))
    $msg = $_GET['msg'];

echo '<h4>'.$h4.'</h4>';
echo $msg;
?>