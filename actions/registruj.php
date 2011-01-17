<?php
if (!isset($_POST['user']))
{
    echo 'nekompletne data';
    return;
}
$user = $_POST['user'];
if (!isset($user['login']) || !isset($user['heslo']) || !isset($user['web']) || !isset($user['skupina']))
{
    echo 'nekompletne data';
    return;
}

//FIXME web musi byt tiez unique (aby sa na zaklade neho dal jednoznacne urcit user pri distrib)
//bez lomitka na konci pouzit regexp namiesto filtra
if (!User::validUrl($user['web']))
    $message = "Neplatná webová adresa!";
elseif ($user['heslo'] != $user['heslo2'])
    $message = "Nezhodujúce sa heslo!";
elseif (!preg_match('/^\w{4-10}$/', $user['login']))
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