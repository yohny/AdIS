<?php 
session_start();

$login = $_POST['login'];
$heslo = $_POST['heslo'];

require '../base/Database.php';
try
{
    $db = new Database();
}
catch (Exception $ex)
{
    $_SESSION['flash'] = $ex->getMessage();
    $referer = $_SERVER['HTTP_REFERER'];
    header("Location: $referer");
}

$user = $db->getUserByCredentials($login, $heslo);

if($user!=null)
{
  $_SESSION['user'] = $user;
  $message = "Úspešne ste sa prihlásili!";
}
else
  $message = "Chyba pri prihlasovaní. / Neplatné prihlasovacie údaje!";

$_SESSION['flash'] = $message;
$referer = $_SERVER['HTTP_REFERER'];
header("Location: $referer");
?>