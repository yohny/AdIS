<?php
header('Content-type: text/javascript; charset=utf-8');

if(!isset ($_GET['rekl']))
    exit("//no 'rekl' parameter");

if (!preg_match('/[1-9][0-9]*/', $_GET['rekl']))
    exit("//invalid 'rekl' parameter (must be integer)");

require '../base/Database.php';
try
{
    $db = new Database();
}
catch (Exception $ex)
{
    exit("//".$ex->getMessage());
}

//zisti parametre pozadovanej reklamy
$reklama = $db->getReklamaById($_GET['rekl']);
if(!$reklama)
    exit("//requested ad was deleted, get new code from Ad-IS");

//vytiahne nahodny banner pre danu reklamu
$banner = $db->getBannerForReklama($reklama);
if(!$banner)
    exit("//could not retrieve banner from Ad-IS server");

$web = $db->getWebById($banner->userId);
if(!$web)
    exit("//could not retrieve web address for banner");

$zobr = new Zobrazenie(null, $reklama->userId, $reklama->id, $banner->userId, $banner->id);
$db->saveZobrazenie($zobr); //prida zobrazenie do DB

echo "var zobr_id = $reklama->userId;\n";
echo "var rekl_id = $reklama->id;\n";
echo "var inze_id = $banner->userId;\n";
echo "var bann_id = $banner->id;\n";
echo "var sirka = {$reklama->velkost->sirka};\n";
echo "var vyska = {$reklama->velkost->vyska};\n";
echo "var redir = \"$web\";\n";

//<a href=”http://www.bbc.co.uk” onclick=”x=new Image();x.src=’track.py’;setTimeout(’location=\’’+this.href+’\’’,100);return false;”>BBC</a>
//or
//<a href=”http://www.bbc.co.uk” onclick=”x=new XMLHttpRequest();x.open(’POST’,’track.py’,false);x.onreadystatechange=function() { if (x.readyState>1)location=this.href };x.send(’’);”>BBC</a>

//<a href="..." ping="...">   - PING not supported by browsers (Firefox only?)

// TODO skusit prerobit pomocou $_SERVER["HTTP_HOST"] aby bolo lahko portovatelne (aj reklamy.php)
// nepouzitelne na podpriecinok localhostu lebo vrati len 'localhost'
// avsak na adis.stkpo.sk bude lebo vrati 'adis.stkpo.sk'
?>
document.write("<a href=\"http://localhost/AdIS/distrib/klik.php?zobra="+zobr_id+"&rekl="+rekl_id+"&inzer="+inze_id+"&bann="+bann_id+"&redir="+redir+"\">");
document.write("<img height=\""+vyska+"\" width=\""+sirka+"\" alt=\"banner\" src=\"http://localhost/AdIS/distrib/banner.php?id="+bann_id+"&rand="+rand+"\">");
document.write("</a>");