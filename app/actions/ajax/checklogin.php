<?php
Context::getInstance()->getResponse()->setHeaderContentType('text/plain');

if (!isset($_GET['login']))
{
    echo 'Nekompletne data';
    return;
}

if (User::isLoginUnique($_GET['login']))
    echo "<span class='g'>Váš login je vporiadku.</span>";
else
    echo "<span class='r'>Neplatný login! Zvoľte iný.</span>";
?>
