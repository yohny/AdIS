<?php
header('Content-type: application/javascript; charset=UTF-8');

if(!isset ($_GET['rekl']))
    exit("//chýba parameter 'rekl'");

if (!preg_match('/[1-9][0-9]*/', $_GET['rekl']))
    exit("//neplatný parameter 'rekl'");

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
$reklama = $db->getReklamaByPK($_GET['rekl']);
if(!$reklama)
    exit("//požadovaná reklama bola zmazaná, nový HTML kód ziskate z Ad-IS servra");

//vytiahne nahodny banner pre danu reklamu
$banner = $db->getRandBannerForReklama($reklama);
if(!$banner)
    exit("//chyba získavania banneru");

$web = $db->getUserWebByPK($banner->userId);
if(!$web)
    exit("//chyba získavania webovej adresy banneru");

$zobr = new Zobrazenie(null, $reklama->userId, $reklama->id, $banner->userId, $banner->id);
$zobr->save($db); //prida zobrazenie do DB

echo "var adisZobrId = $reklama->userId;\n";
echo "var adisReklId = $reklama->id;\n";
echo "var adisInzeId = $banner->userId;\n";
echo "var adisBannId = $banner->id;\n";
echo "var adisSirka = {$reklama->velkost->sirka};\n";
echo "var adisVyska = {$reklama->velkost->vyska};\n";
echo "var adisRedir = \"$web\";\n";

//<a href=”http://www.bbc.co.uk” onclick=”x=new Image();x.src=’track.py’;setTimeout(’location=\’’+this.href+’\’’,100);return false;”>BBC</a>
//or
//<a href=”http://www.bbc.co.uk” onclick=”x=new XMLHttpRequest();x.open(’POST’,’track.py’,false);x.onreadystatechange=function() { if (x.readyState>1)location=this.href };x.send(’’);”>BBC</a>

//<a href="..." ping="...">   - PING not supported by browsers (Firefox only?)
?>
document.write("<a href=\"http://<?php echo $_SERVER["HTTP_HOST"]; ?>/doKlik?zobra="+adisZobrId+"&rekl="+adisReklId+"&inzer="+adisInzeId+"&bann="+adisBannId+"&redir="+adisRedir+"\">");
document.write("<img height=\""+adisVyska+"\" width=\""+adisSirka+"\" alt=\"banner\" src=\"http://<?php echo $_SERVER["HTTP_HOST"]; ?>getBanner?id="+adisBannId+"&rand="+adisRand+"\">");
document.write("</a>");