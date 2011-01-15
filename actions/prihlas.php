<?php
if(!isset($_POST['login']) || !isset($_POST['heslo']))
{
    echo 'nekompletne data';
    return;
}

try
{
    $user = User::getByCredentials( $_POST['login'], $_POST['heslo']);
    if ($user != null)
    {
        Context::getInstance()->setUser($user);
        $message = "Úspešne ste sa prihlásili!";
    }
    else
        $message = "Chyba pri prihlasovaní. / Neplatné prihlasovacie údaje!";
}
catch (Exception $ex)
{
    $message = $ex->getMessage();
}
Context::getInstance()->getResponse()->setFlash($message);
header("Location: /");
?>