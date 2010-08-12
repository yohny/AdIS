<?php
if(!isset ($_GET['login']))
    exit();

$login = $_GET['login'];
require '../datab_con.php';
/* @var $conn mysqli */

$query = "SELECT COUNT(*) AS count FROM users WHERE login='$login'";
/* @var $result mysqli_result */
$result = $conn->query($query);
$conn->close();

if ($result->fetch_object()->count>0)
    echo "<span class='r'>Neplatný login! Zvoľte iný.</span>";
else
    echo "<span class='g'>Váš login je vporiadku.</span>";

?>
