<?php
if (!isset($_POST['user']) || !isset($_POST['csrf_token']))
{
    echo 'nekompletne data';
    return;
}
if($_POST['csrf_token'] != Context::getInstance()->getCsrfToken())
{
    echo 'CSRF fail!';
    return;
}

$user = $_POST['user'];
if (!isset($user['login']) || !isset($user['heslo']) || !isset($user['web']) || !isset($user['skupina']) || !isset($user['captcha']))
{
    echo 'nekompletne data';
    return;
}

Autoloader::registerCaptcha();
$captcha = new Securimage();

if(!$captcha->check($user['captcha']))
    $message = "Neplatná captcha!";
elseif ($resp = User::validateInput($user))
    $message = $resp;
else
{
        if (User::create($user['login'], $user['heslo'], $user['web'], $user['skupina']))
            $message = "Registrácia úspešná.";
        else
            $message = "Registrácia neúspešná.";
}

$_SESSION['registrator'] = $user;
Context::getInstance()->getResponse()->setFlash($message);

Context::getInstance()->getResponse()->redirect = '/registracia';
?>