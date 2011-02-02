<?php
if(!isset($_POST['action']) || !isset($_POST['csrf_token']))
{
    echo 'Nekompletne data';
    return;
}
if($_POST['csrf_token'] != Context::getInstance()->getCsrfToken())
{
    echo 'CSRF fail!';
    return;
}

if ($_POST['action'] == "logout")
{
    session_unset();
    //session_destroy(); -nemoze potom nastavit flash
    $message = "Boli ste odhlásený.";
}
else
    $message = "Chybná požiadavka";

Context::getInstance()->getResponse()->setFlash($message);
Context::getInstance()->getResponse()->redirect = '/';
?>