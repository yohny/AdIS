<?php
if(!isset ($_GET['login']))
    exit();

$login = $_GET['login'];
require '../base/Database.php';
try
{
    $db = new Database();
}
catch (Exception $ex)
{
    exit($ex->getMessage());
}

if(User::isLoginUnique($login,$db))
    echo "<span class='g'>Váš login je vporiadku.</span>";
else
    echo "<span class='r'>Neplatný login! Zvoľte iný.</span>";
?>
