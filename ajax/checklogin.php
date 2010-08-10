<?php
if(!isset ($_GET['login']))
    exit();

$login = $_GET['login'];
require '../datab_con.php';
$query = "SELECT * FROM users WHERE login='$login'";
$result = mysql_query($query) or die('Zlyhalo query!');

if (mysql_numrows($result)>0)
    echo "<span class='r'>Neplatný login! Zvoľte iný.</span>";
else
    echo "<span class='g'>Váš login je vporiadku.</span>";

?>
