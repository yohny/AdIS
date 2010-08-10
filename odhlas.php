<?php 
session_start();

if($_POST['action']=="Odhlásiť")
{
  session_destroy();
  $message = "Boli ste odhlásený.";
}
else
  $message = "Chybná požiadavka";

$_SESSION['flash'] = $message;

$referer = $_SERVER['HTTP_REFERER'];
header("Location: $referer");
?>