<?php
//error handler function
function customError($errno, $errstr)
{
    header("Location: http://localhost/AdIS/klikerror.php?msg=$errstr");
    exit();
}
set_error_handler("customError"); //druhy optional param je error level, default je E_ALL|E_STRICT, tj vsetky chyby


$zobr_id = $_GET['zobra'];
$rekl_id = $_GET['rekl'];
$inze_id = $_GET['inzer'];
$bann_id = $_GET['bann'];
$web = $_GET['redir'];
//kvoli rychlejsiemu redirectu sa adresa posiela ako argument a netaha z DB

if(!isset($_COOKIE['voted']))
{
    setcookie("voted","voted", time()+10);  //platnost cookie 60 sek

    require '../base/Database.php';
    try
    {
        $db = new Database();
    }
    catch (Exception $ex)
    {
        header("Location: $web");
    }
    $db->saveKlik(new Klik(null, $zobr_id, $rekl_id, $inze_id, $bann_id));
}
header("Location: $web");
?>
