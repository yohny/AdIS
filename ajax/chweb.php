<?php
header('Content-type: application/json; charset=UTF-8');

if (!isset($_POST['web']))
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
    exit(json_encode(array('success' => false, 'message' => 'Nepodarilo sa napojiť na DB.')));
}

/* @var $user User */
$user = $_SESSION['user'];
$web = $_POST['web'];

if (!filter_var($web, FILTER_VALIDATE_URL))
    $resp = array('success' => false, 'message' => 'Neplatná webová adresa!');
elseif ($user->setWeb($web, $db))
    $resp = array('success' => true, 'message' => 'WWW adresa zmenená.');
else
    $resp = array('success' => false, 'message' => 'Nepodarilo sa zmeniť web!');

echo json_encode($resp);
?>