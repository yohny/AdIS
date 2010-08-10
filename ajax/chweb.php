<?php
if(!isset ($_POST['web']))
    exit();

session_start();
require '../secure.php';

$user = $_SESSION['user'];
$web = $_POST['web'];

if(!filter_var($web, FILTER_VALIDATE_URL))
{
    $resp = array('success' => false,'message' => 'Neplatná webová adresa!');
    echo json_encode($resp);
    exit();
}

require '../datab_con.php';
$query = "UPDATE users SET web='$web' WHERE login='$user'";
mysql_query($query) or die('Zlyhalo query!');
mysql_close($conn);

$resp = array('success' => true,'message' => 'WWW adresa zmenená.!');
echo json_encode($resp);
?>