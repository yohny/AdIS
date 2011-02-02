<?php
if(!isset($_POST['login']) || !isset($_POST['heslo']) || !isset($_POST['csrf_token']))
{
    echo 'nekompletne data';
    return;
}
if($_POST['csrf_token'] != Context::getInstance()->getCsrfToken())
{
    echo 'CSRF fail!';
    return;
}

$user =  Context::getInstance()->getDatabase()->getUserByCredentials( $_POST['login'], $_POST['heslo']);
if ($user != null)
{
    $_SESSION['user'] = $user;
    $message = "Úspešne ste sa prihlásili!";
}
else
    $message = "Chyba pri prihlasovaní. / Neplatné prihlasovacie údaje!";

Context::getInstance()->getResponse()->setFlash($message);
Context::getInstance()->getResponse()->redirect = '/';
?>