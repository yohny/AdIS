<?php
Context::getInstance()->getResponse()->setHeading('chyba');

$h4 = 'Došlo k chybe na serveri alebo stránka na ktorej ste klikli na
    relamný banner má chybný kód banneru a preto Vás nie je možné presmerovať
    do cieľa reklamy. Ospravedlňujeme sa spôsobené nepríjemnosti';

$msq = 'unknown error';
if(isset ($_GET['msg']))
    $msg = $_GET['msg'];

echo '<h4>'.$h4.'</h4>';
echo $msg;
?>