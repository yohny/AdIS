<?php
function customError($errno, $errstr) //error handler function
{
    header("Location: http://{$_SERVER["HTTP_HOST"]}/klikerror?msg=$errstr");
    exit();
}
set_error_handler("customError");

//TODO checking ci request prisiel zo servra nejakeho zobrazovatela
//ak hej tak overit ci ma ten zobrazovatel momentalne zobrazene to co je aj v kliku


$zobr_id = $_GET['zobra'];
$rekl_id = $_GET['rekl'];
$inze_id = $_GET['inzer'];
$bann_id = $_GET['bann'];
$web = $_GET['redir'];//kvoli rychlejsiemu redirectu sa adresa posiela ako argument a netaha z DB

if(!isset($_COOKIE['voted']))
{
    setcookie("voted","voted", time()+10);  //platnost cookie 10 sek
    try
    {
        $db = new Database();
        $klik = new Klik(null, $zobr_id, $rekl_id, $inze_id, $bann_id);
        $klik->save($db);
    }
    catch (Exception $ex)
    {
        trigger_error($ex->getMessage());
    }
}
header("Location: http://$web");
?>
