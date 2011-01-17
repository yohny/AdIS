<?php
header('Content-type: application/javascript; charset=UTF-8');

if (!isset($_GET['rekl']) || !is_numeric($_GET['rekl']))
    exit("//neplatný/chýbajúci parameter");

try
{
    $db = new Database();
    //vytiahne pozadovanu reklamu
    if (!$reklama = $db->getReklamaByPK($_GET['rekl']))
        exit("//požadovaná reklama bola zmazaná, nový HTML kód ziskate z Ad-IS servra");
    //overenie requestu
    if(!isset($_SERVER['HTTP_REFERER']) || !$user = $db->getUserByReferer($_SERVER['HTTP_REFERER']))
        exit("//neplatný zdroj požiadavky");
    if($user->id != $reklama->userId)
        exit ('//nemôžete zobrazovať túto reklamu, overte správnosť kódu');
    //vytiahne nahodny banner pre danu reklamu
    if (!$banner = $db->getRandBannerForReklama($reklama))
        exit("//chyba získavania banneru");
    //vytiahne adresu na presmerovanie
    if (!$web = User::getWebByPK($banner->userId))
        exit("//chyba získavania webovej adresy banneru");
    //zapise zobrazenie do DB
    $zobr = new Zobrazenie(null, $reklama->userId, $reklama->id, $banner->userId, $banner->id);
    $zobr->save($db);
    setcookie("adis_rekl_{$_GET['rekl']}",$db->conn->insert_id);
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
//TODO javascript upravit aby checkoval ci uspesne sa nacital obrazok a az tak zobrazil link
//moze aj kontrolovat ci sa nastavilo cookie?