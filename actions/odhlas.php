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
//$referer = http_build_url($referer, array("path" => "/fero"), HTTP_URL_STRIP_PATH | HTTP_URL_STRIP_QUERY); //not default part of PHP
//$referer = preg_replace('/^.*\/([\w^\/]+\.php)$/', 'index.php', $referer);
header("Location: $referer");
?>