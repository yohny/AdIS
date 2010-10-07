<?php
require '../base/model/User.php';
session_start();

$user = $_POST['user'];
if(!isset($user['login']) || !isset($user['heslo']) || !isset($user['web']) || !isset($user['skupina']))
    exit ('nekompletne data');

if(!filter_var($user['web'], FILTER_VALIDATE_URL))
    $message = "Neplatná webová adresa!";
elseif ($user['heslo']!=$user['heslo2'])
    $message = "Nezhodujúce sa heslo!";
else
{
    require '../base/Database.php';
    $db = new Database();
    if($db->isLoginUnique($user['login']))
    {
        if($db->addUser($user['login'],$user['heslo'],$user['web'],$user['skupina']))
            $message = "Registrácia úspešná.";
        else
            $message = "Registrácia neúspešná.";
    }
    else
        $message = "Váš login NIE JE unikátny, zvoľte iný.";
}

$_SESSION['registrator'] = $user;
$_SESSION['flash'] = $message;
$referer = $_SERVER['HTTP_REFERER'];
header("Location: $referer");
?>