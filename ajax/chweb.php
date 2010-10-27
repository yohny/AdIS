<?php
header('Content-type: application/json');

if(!isset ($_POST['web']))
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
    exit(json_encode(array('success' => false,'message' => $ex->getMessage())));
}

/* @var $user User */
$user = $_SESSION['user'];
$web = $_POST['web'];

if($user->setWeb($db->conn, $web))
    $resp = array('success' => true,'message' => 'WWW adresa zmenená.!');
else
    $resp = array('success' => false,'message' => 'Neplatná webová adresa!');

echo json_encode($resp);
?>