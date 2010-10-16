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

$referer = $_SERVER['HTTP_REFERER'];
$referer = str_replace(basename($referer), 'index.php', $referer);
header("Location: $referer");
?>