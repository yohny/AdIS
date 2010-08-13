<?php
header('Content-type: text/javascript; charset=utf-8');

if(!isset ($_GET['rekl']))
    exit("//no 'rekl' parameter");

if (!preg_match('/[1-9][0-9]*/', $_GET['rekl']))
    exit("//invalid 'rekl' parameter (must be integer)");

$rekl_id = $_GET['rekl'];

require '../base/datab_con.php';
/* @var $conn mysqli */

//zisti parametre pozadovanej reklamy
/* @var $result mysqli_result */
$result = $conn->query("SELECT velkost,user,sirka,vyska FROM reklamy JOIN velkosti ON (reklamy.velkost=velkosti.id) WHERE reklamy.id=$rekl_id");
if($conn->affected_rows==0)
    exit("//requested ad was deleted, get new code from AdIS");

$reklama = $result->fetch_object();

//vytiahne nahodny banner s tymito parametrami
$result = $conn->query("SELECT DISTINCT bannery.id, bannery.user, web
    FROM bannery
    JOIN kategoria_banner ON (bannery.id=kategoria_banner.banner)
    JOIN users ON (bannery.user=users.id)
    WHERE bannery.velkost=$reklama->velkost
    AND kategoria_banner.kategoria IN (SELECT kategoria FROM kategoria_reklama WHERE reklama=$rekl_id)
    ORDER BY RAND() LIMIT 1");
$banner = $result->fetch_object();
$conn->close();

echo "var zobr_id = $reklama->user;\n";
echo "var rekl_id = $rekl_id;\n";
echo "var inze_id = $banner->user;\n";
echo "var bann_id = $banner->id;\n";
echo "var sirka = $reklama->sirka;\n";
echo "var vyska = $reklama->vyska;\n";
echo "var redir = \"$banner->web\";\n";

//<a href=”http://www.bbc.co.uk” onclick=”x=new Image();x.src=’track.py’;setTimeout(’location=\’’+this.href+’\’’,100);return false;”>BBC</a>
//or
//<a href=”http://www.bbc.co.uk” onclick=”x=new XMLHttpRequest();x.open(’POST’,’track.py’,false);x.onreadystatechange=function() { if (x.readyState>1)location=this.href };x.send(’’);”>BBC</a>

//<a href="..." ping="...">   - PING not supported by browsers (Firefox only?)

// TODO skusit prerobit pomocou $_SERVER["HTTP_HOST"] abz bolo lahko portovatelne (aj reklamy.php)
?>
document.write("<a href=\"http://localhost/AdIS/distrib/klik.php?zobra="+zobr_id+"&rekla="+rekl_id+"&inzer="+inze_id+"&banne="+bann_id+"&redir="+redir+"\">");
document.write("<img height=\""+vyska+"\" width=\""+sirka+"\" alt=\"banner\" src=\"http://localhost/AdIS/distrib/obrazok.php?id="+bann_id+"\">");
document.write("</a>");