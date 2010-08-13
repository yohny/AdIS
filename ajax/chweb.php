<?php
if(!isset ($_POST['web']))
    exit('Nekompletne data');

session_start();
require '../base/secure.php';

$user = $_SESSION['user'];
$web = $_POST['web'];

if(!filter_var($web, FILTER_VALIDATE_URL))
{
    $resp = array('success' => false,'message' => 'Neplatná webová adresa!');
    exit(json_encode($resp));
}

require '../base/datab_con.php';
/* @var $conn mysqli */

$query = "UPDATE users SET web='$web' WHERE login='$user'";
$conn->query($query);
$conn->close();

$resp = array('success' => true,'message' => 'WWW adresa zmenená.!');
echo json_encode($resp);
?>