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
    //zapise zobrazenie do DB
    $zobr = new Zobrazenie(null, null, $reklama->userId, $reklama->id, $banner->userId, $banner->id, null);
    $zobr->save($db);
    $view = $db->conn->insert_id;
}
catch (Exception $ex)
{
    exit("//" . $ex->getMessage());
}
//<a href=”http://www.bbc.co.uk” onclick=”x=new Image();x.src=’track.py’;setTimeout(’location=\’’+this.href+’\’’,100);return false;”>BBC</a>
//or
//<a href=”http://www.bbc.co.uk” onclick=”x=new XMLHttpRequest();x.open(’POST’,’track.py’,false);x.onreadystatechange=function() { if (x.readyState>1)location=this.href };x.send(’’);”>BBC</a>
//<a href="..." ping="...">   - PING not supported by browsers (Firefox only?)
?>
if(typeof adis_container_<?php echo $reklama->id; ?> == 'undefined' && typeof adis_banner_<?php echo $banner->id; ?> == 'undefined')
{
    document.write("<a id=\"adis_container_<?php echo $reklama->id; ?>\" style=\"margin:0;padding:0;border-style:none;width:<?php echo $reklama->velkost->sirka; ?>px;height:<?php echo $reklama->velkost->vyska; ?>px;display:inline-block;vertical-align:text-bottom;text-decoration:none;\"><\/a>");
    var adis_container_<?php echo $reklama->id; ?> = document.getElementById("adis_container_<?php echo $reklama->id; ?>");
    adis_container_<?php echo $reklama->id; ?>.innerHTML = 'loading...';
    var adis_banner_<?php echo $banner->id; ?> = document.createElement('img');
    adis_banner_<?php echo $banner->id; ?>.style.margin = '0';
    adis_banner_<?php echo $banner->id; ?>.style.padding = '0';
    adis_banner_<?php echo $banner->id; ?>.style.borderStyle = 'none';
    adis_banner_<?php echo $banner->id; ?>.alt = "banner_<?php echo $banner->id; ?>";
    adis_banner_<?php echo $banner->id; ?>.onload = function(){
        adis_container_<?php echo $reklama->id; ?>.removeChild(adis_container_<?php echo $reklama->id; ?>.childNodes[0]);
        adis_container_<?php echo $reklama->id; ?>.appendChild(this);
        adis_container_<?php echo $reklama->id; ?>.href = <?php echo "\"http://{$_SERVER["HTTP_HOST"]}/doKlik?zobra=$reklama->userId&amp;rekl=$reklama->id&amp;inzer=$banner->userId&amp;bann=$banner->id&amp;view=$view\";"; ?>
    };
    adis_banner_<?php echo $banner->id; ?>.onerror = function(){
        adis_container_<?php echo $reklama->id; ?>.innerHTML = 'Ad-IS: banner loading error';
    };
    adis_banner_<?php echo $banner->id; ?>.src = <?php echo "\"http://{$_SERVER["HTTP_HOST"]}/getBanner?id=$banner->id&view=$view\";\n"; ?>
}