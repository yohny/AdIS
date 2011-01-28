<?php
if (!isset($_POST['user']))
{
    echo 'nekompletne data';
    return;
}
$user = $_POST['user'];
if (!isset($user['login']) || !isset($user['heslo']) || !isset($user['web']) || !isset($user['skupina']) || !isset($user['captcha']))
{
    echo 'nekompletne data';
    return;
}

require_once 'classes/captcha/securimage.php';
$captcha = new Securimage();

if(!$captcha->check($user['captcha']))
    $message = "Neplatná captcha!";
elseif (!User::validUrl($user['web']))
    $message = "Neplatná webová adresa!";
elseif ($user['heslo'] != $user['heslo2'])
    $message = "Nezhodujúce sa heslo!";
elseif (!preg_match('/^[a-zA-Z\d]{4,10}$/', $user['login']))
    $message = "Neplatný login!";
else
{
    try
    {
        if (!User::isLoginUnique($user['login']))
            $message = "Váš login NIE JE unikátny, zvoľte iný.";
        elseif(!User::isWebUnique($user['web']))
            $message = "Váš web už bol zaregistrovaný.";
        else
        {
            if (User::create($user['login'], $user['heslo'], $user['web'], $user['skupina']))
                $message = "Registrácia úspešná.";
            else
                $message = "Registrácia neúspešná.";
        }
    }
    catch (Exception $ex)
    {
        $message = $ex->getMessage();
    }
}
$_SESSION['registrator'] = $user;
Context::getInstance()->getResponse()->setFlash($message);
header("Location: " . (isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:"/"));
?>