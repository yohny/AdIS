<?php
if(!isset($_POST['old']) || !isset($_POST['new']))
    exit();

session_start();
require '../secure.php';

$user = $_SESSION['user'];
$old = $_POST['old'];
$new = $_POST['new'];

require '../datab_con.php';

$query = "SELECT * FROM users WHERE login='$user' AND heslo=MD5('$old')";
$result = mysql_query($query) or die('Zlyhalo query!');

if (mysql_numrows($result)==1)
{
    $query = "UPDATE users SET heslo=MD5('$new') WHERE login='$user'";
    mysql_query($query) or die('Zlyhalo query!');
    $resp = array('success' => true,'message' => 'Heslo zmenené.');
    echo json_encode($resp);
}
else
{
    $resp = array('success' => false,'message' => 'Neplatné staré heslo!');
    echo json_encode($resp);
}
mysql_close($conn);
?>

