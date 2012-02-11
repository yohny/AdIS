<?php
/**
 * generator javascriptu
 *
 * @version    1.0
 * @package    AdIS
 * @subpackage distribution
 * @author     Ján Neščivera <jan.nescivera@gmail.com>
 *
 */

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
    //zapise zobrazenie do DB
    $zobr = new Zobrazenie(null, new DateTime(), $reklama->userId, $reklama->id, $banner->userId, $banner->id);
    $zobr->save($db);
    $view = $db->insert_id;
}
catch (Exception $ex)
{
    exit("//" . $ex->getMessage());
}
//<a href=”http://www.bbc.co.uk” onclick=”x=new Image();x.src=’track.py’;setTimeout(’location=\’’+this.href+’\’’,100);return false;”>BBC</a>
//or
//<a href=”http://www.bbc.co.uk” onclick=”x=new XMLHttpRequest();x.open(’POST’,’track.py’,false);x.onreadystatechange=function() { if (x.readyState>1)location=this.href };x.send(’’);”>BBC</a>
//<a href="..." ping="...">   - PING not supported by browsers (Firefox only?)

//variable scope separation - http://www.howtocreate.co.uk/tutorials/javascript/functions uplne dole
?>
(function () {
    var container_id = 1;
    while(document.getElementById("adis_container_"+container_id))
        container_id++;
    document.write("<a id=\"adis_container_"+container_id+"\" style=\"margin:0;padding:0;border-style:none;width:<?php echo $reklama->velkost->sirka; ?>px;height:<?php echo $reklama->velkost->vyska; ?>px;display:inline-block;vertical-align:text-bottom;text-decoration:none;\"><\/a>");
    var adis_container = document.getElementById("adis_container_"+container_id);
    adis_container.innerHTML = 'loading...';
    var adis_banner = document.createElement('img');
    adis_banner.style.margin = '0';
    adis_banner.style.padding = '0';
    adis_banner.style.borderStyle = 'none';
    adis_banner.alt = "banner_<?php echo $banner->id; ?>";
    adis_banner.onload = function(){
        adis_container.removeChild(adis_container.childNodes[0]);
        adis_container.appendChild(this);
        adis_container.href = <?php echo "\"http://{$_SERVER["HTTP_HOST"]}/doKlik?zobra=$reklama->userId&rekl=$reklama->id&inzer=$banner->userId&bann=$banner->id&view=$view\";"; ?>
    };
    adis_banner.onerror = function(){
        adis_container.innerHTML = "Ad-IS: banner loading error";
    };
    adis_banner.src = <?php echo "\"http://{$_SERVER["HTTP_HOST"]}/getBanner?id=$banner->id&view=$view\";\n"; ?>
})();