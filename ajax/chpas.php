<?php
header('Content-type: application/json; charset=UTF-8');

if(!isset($_POST['old']) || !isset($_POST['new']))
    exit('Nekompletne data');

require '../base/model/User.php'; //pred session_start
session_start();
require '../base/secure.php';
require '../base/Database.php';
try
{
    $db = new Database();
}
catch (Exception $ex)
{
    exit(json_encode(array('success' => false,'message' => 'Nepodarilo sa napojiť na DB.')));
}

/* @var $user User */
$user = $_SESSION['user'];
$old = $_POST['old'];
$new = $_POST['new'];

if ($user->setPassword($old, $new, $db))
    $resp = array('success' => true,'message' => 'Heslo zmenené.');
else
    $resp = array('success' => false,'message' => 'Neporarilo sa zmeniť heslo!(nesprávne?)');

echo json_encode($resp);
?>

