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

if (!filter_var($user['web'], FILTER_VALIDATE_URL))
    $message = "Neplatná webová adresa!";
elseif ($user['heslo'] != $user['heslo2'])
    $message = "Nezhodujúce sa heslo!";
else
{
    try
    {
        if (User::isLoginUnique($user['login']))
        {
            if (User::create($user['login'], $user['heslo'], $user['web'], $user['skupina']))
                $message = "Registrácia úspešná.";
            else
                $message = "Registrácia neúspešná.";
        }
        else
            $message = "Váš login NIE JE unikátny, zvoľte iný.";
    }
    catch (Exception $ex)
    {
        $message = $ex->getMessage();
    }
}
$_SESSION['registrator'] = $user;
Context::getInstance()->setFlash($message);
header("Location: " . $_SERVER['HTTP_REFERER']);
?>