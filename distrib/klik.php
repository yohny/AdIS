<?php
//error handler function
function customError($errno, $errstr)
{
    header("Location: http://localhost/AdIS/klikerror.php?msg=$errstr");
    exit();
}
set_error_handler("customError"); //druhy optional param je error level, default je E_ALL|E_STRICT, tj vsetky chyby


$zobr_id = $_GET['zobra'];
$rekl_id = $_GET['rekla'];
$inze_id = $_GET['inzer'];
$bann_id = $_GET['banne'];
$web = $_GET['redir'];
//kvoli rychlejsiemu redirectu sa adresa posiela ako argument a netaha z DB
//$result = mysql_query("SELECT web FROM users WHERE id=$inze_id") or die('Zlyhalo query!');
//$row = mysql_fetch_array($result);

if(!isset($_COOKIE['voted']))
{
    setcookie("voted","voted", time()+30);  //platnost cookie 30 sek

    require '../base/datab_con.php';
    /* @var $conn mysqli */
    $conn->query("INSERT INTO kliky VALUES(NULL, NOW(), $zobr_id, $inze_id, $rekl_id, $bann_id)");
    $conn->close();
}
header("Location: $web");
?>
