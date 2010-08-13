<?php
if(!isset($_POST['old']) || !isset($_POST['new']))
    exit('Nekompletne data');

session_start();
require '../base/secure.php';

$user = $_SESSION['user'];
$old = $_POST['old'];
$new = $_POST['new'];

require '../base/datab_con.php';
/* @var $conn mysqli */

$query = "SELECT COUNT(*) AS count FROM users WHERE login='$user' AND heslo=MD5('$old')";
/* @var $result mysqli_result */
$result = $conn->query($query);

if ($result->fetch_object()->count==1)
{
    $query = "UPDATE users SET heslo=MD5('$new') WHERE login='$user'";
    $conn->query($query);
    $resp = array('success' => true,'message' => 'Heslo zmenené.');
}
else
    $resp = array('success' => false,'message' => 'Neplatné staré heslo!');

echo json_encode($resp);

$conn->close();
?>

