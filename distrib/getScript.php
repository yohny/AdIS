<?php
header('Content-type: application/javascript; charset=UTF-8');

if (!isset($_GET['rekl']) || !is_numeric($_GET['rekl']))
    exit("//neplatný/chýbajúci parameter");

//TODO checking ci request prisiel zo servra nejakeho zobrazovatela
//HTTP_REFFERER porovnat s DB zobrazovatelov, aj getBanner aj doKlik

try
{
    require_once '../classes/core/Database.php';
    require_once '../classes/model/base/Event.php';
    require_once '../classes/model/base/BanRek.php';
    require_once '../classes/model/Zobrazenie.php';
    require_once '../classes/model/Velkost.php';
    require_once '../classes/model/Banner.php';
    require_once '../classes/model/Reklama.php';
    $db = new Database();
    //zisti parametre pozadovanej reklamy
    $reklama = $db->getReklamaByPK($_GET['rekl']);
    if (!$reklama)
        exit("//požadovaná reklama bola zmazaná, nový HTML kód ziskate z Ad-IS servra");
    //vytiahne nahodny banner pre danu reklamu
    $banner = $db->getRandBannerForReklama($reklama);
    if (!$banner)
        exit("//chyba získavania banneru");
    //vytiahne adresu na presmerovanie
    $web = $db->getUserWebByPK($banner->userId);
    if (!$web)
        exit("//chyba získavania webovej adresy banneru");
    //zapise zobrazenie do DB
    $zobr = new Zobrazenie(null, $reklama->userId, $reklama->id, $banner->userId, $banner->id);
    $zobr->save($db); //prida zobrazenie do DB
}
catch (Exception $ex)
{
    exit("//" . $ex->getMessage());
}

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
document.write("<img height=\""+adisVyska+"\" width=\""+adisSirka+"\" alt=\"banner\" src=\"http://<?php echo $_SERVER["HTTP_HOST"]; ?>/getBanner?id="+adisBannId+"&rand="+adisRand+"\">");
document.write("</a>");