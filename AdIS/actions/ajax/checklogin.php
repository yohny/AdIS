<?php

if (!isset($_GET['login']))
{
    echo 'Nekompletne data';
    return;
}

try
{
    if (User::isLoginUnique($_GET['login']))
        echo "<span class='g'>Váš login je vporiadku.</span>";
    else
        echo "<span class='r'>Neplatný login! Zvoľte iný.</span>";
}
catch (Exception $ex)
{
    echo $ex->getMessage();
}
?>
