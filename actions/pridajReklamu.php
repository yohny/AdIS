<?php
if(!isset ($_POST['meno']) || !isset ($_POST['velkost']) || !isset ($_POST['kategorie']))
    exit("Nekompletne data");

require '../base/model/User.php'; //pred session_start
session_start();
require '../base/secure.php';
$user = $_SESSION['user']; /* @var $user User */
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
    exit();
}

$meno = $_POST['meno'];
$kategorie = $_POST['kategorie'];
$message = "";

$velkost = $db->getVelkostByPK($_POST['velkost']);
if(!$velkost)
    exit ("Nepodarilo sa ziskat velkost");

if(strlen($meno)>50)
    $message .= "Príliš dlhý názov! (max. 50 znakov)<br>";
if($user->hasReklamaOfSize($velkost, $db))
    $message .= "Už máte reklamu typu $velkost->nazov!<br>";

if($message=="")
{
    $reklama = new Reklama(null, $user->id, $velkost, $meno);
    if($reklama->save($kategorie, $db))
        $message = "Reklama bola úspešne uložená.";
    else
        $message = "Nepodarilo sa uložiť reklamu.";
}

$_SESSION['flash'] = $message;
$referer = $_SERVER['HTTP_REFERER'];
header("Location: $referer");
?>