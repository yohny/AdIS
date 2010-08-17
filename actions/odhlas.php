<?php
session_start();

if ($_POST['action'] == "Odhlásiť")
{
    session_unset();
    $message = "Boli ste odhlásený.";
}
else
    $message = "Chybná požiadavka";

$_SESSION['flash'] = $message;

//$referer = $_SERVER['HTTP_HOST']; //po odhlaseni presmeruje na hlavnu stranku
$referer = $_SERVER['HTTP_REFERER'];
header("Location: $referer");
?>