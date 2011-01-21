<?php
function customError($errno, $errstr) //error handler function
{
    header("Location: http://{$_SERVER["HTTP_HOST"]}/klikerror?msg=$errstr");
    exit();
}
set_error_handler("customError");

//v error handleri nepozna externe premenne - neda sa pouzit na mazanie
//a ak je zadefinovany tak zbehne aj pre prikazy so @

//TODO pekne by tiez bol aj daky tracking spravit (cookie tracking PK kliknutí) aby sa
//nejaký cas nedalo klikat na tu istu "konstalaciu" tj nie len voted, ale aby tam bolo pole
//id klikov za poslednych X sekund a na zaklade toho by sa povedalo ze nemoze klikat napr na danej stranke
//resp na dany banner aj na inych strankach
//lebo bz v cookie stacili id zobrazovatelov a reklam

$zobr_id = $_GET['zobra'];
$rekl_id = $_GET['rekl'];
$inze_id = $_GET['inzer'];
$bann_id = $_GET['bann'];
$view_id = $_GET['view'];

try
{
    $db = new Database();
    if (!isset($_SERVER['HTTP_REFERER']) || !$user = $db->getUserByReferer($_SERVER['HTTP_REFERER']))
        trigger_error("Neplatný zdroj (referer).");
    if ($user->id != $_GET['zobra'])
        trigger_error("Neplatný zdroj (parameter).");
    if (!$zobrazenie = $db->getZobrazenieByPK($_GET['view']))
        trigger_error("Neplatné zobrazenie.");
    if ($zobrazenie->zobraId != $_GET['zobra'] || $zobrazenie->reklamaId != $_GET['rekl']
            || $zobrazenie->inzerId != $_GET['inzer'] || $zobrazenie->bannerId != $_GET['bann'])
        trigger_error("Kolízia parametrov.");
    if(!$inzer = $db->getUserByPK($inze_id))
        trigger_error("Chyba získavania cieľovej URL adresy.");
    //samotny klik
    if (!isset($_COOKIE['voted']) && !$zobrazenie->isClicked())
    {
        setcookie("voted", "voted", time() + 10);  //platnost cookie 10 sek
        $klik = new Klik(null, null, $zobr_id, $rekl_id, $inze_id, $bann_id);
        $klik->save($db);
    }
    //NOTE pocet zobrazeni s 'clicked' nie je ten isty ako pocet kliknuti, lebo k nastaveniu
    //clicked dojde vzdy aj ked sa nezapise klik (kvoli cookie)
    if (!$zobrazenie->isClicked())
        $zobrazenie->setClicked($db);
//    else                  //bez erroru nemusi vediet o tom user, staci ze sa nezapocita
//        trigger_error("Opakovane klikanie");
}
catch (Exception $ex)
{
    trigger_error($ex->getMessage());
}
header("Location: http://$inzer->web");
?>
