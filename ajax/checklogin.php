<?php
if(!isset ($_GET['login']))
    exit();

$login = $_GET['login'];
require '../base/Database.php';
$db = new Database();

if($db->isLoginUnique($login))
    echo "<span class='g'>Váš login je vporiadku.</span>";
else
    echo "<span class='r'>Neplatný login! Zvoľte iný.</span>";
?>
