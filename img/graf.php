<?php
require_once '../classes/model/Statistika.php';
require_once '../classes/model/User.php';
session_name('adis_session');
session_start();

if(!isset($_SESSION['user']) || !isset ($_SESSION['stats']))
{
    unset($_SESSION['stats']);
    header("HTTP/1.1 403 Forbidden");
    exit();
}

$stats = $_SESSION['stats'];
unset($_SESSION['stats']);

require_once '../classes/pChart/pData.class.php';
require_once '../classes/pChart/pDraw.class.php';
require_once '../classes/pChart/pImage.class.php';

//priprava dat
$data = array();
/* @var $stat Statistika */
foreach ($stats as $stat)
{
    if(array_key_exists($stat->__toString(), $data))//ak uz taky datum ma svoje podpole
    {
        $data[$stat->__toString()]['clicks']+=$stat->kliky;
        $data[$stat->__toString()]['views']+=$stat->zobrazenia;
    }
    else //ak este nema
    {
        $data[$stat->__toString()]=array();
        $data[$stat->__toString()]['clicks']=$stat->kliky;
        $data[$stat->__toString()]['views']=$stat->zobrazenia;
    }
}
$dates = array_keys($data);
function clicks($n){ return $n['clicks'];}
$clicks = array_map("clicks", $data);
function views($n){ return $n['views'];}
$views = array_map("views", $data);

$myData = new pData();
$myData->addPoints(array_reverse($clicks),"clicks");//kliky
$myData->setSerieDescription("clicks","Kliknutia");
$myData->setSerieOnAxis("clicks",0);

$myData->addPoints(array_reverse($views),"views");//zobra
$myData->setSerieDescription("views","Zobrazenia");
$myData->setSerieOnAxis("views",0);

$myData->addPoints(array_reverse($dates),"Dátum"); //suradnicova os
$myData->setAbscissa("Dátum");

$myData->setAxisPosition(0,AXIS_POSITION_LEFT);
$myData->setAxisName(0,"Počet");
$myData->setAxisUnit(0,"");

$myPicture = new pImage(700,250,$myData);
//$Settings = array("R"=>170, "G"=>183, "B"=>87, "Dash"=>1, "DashR"=>190, "DashG"=>203, "DashB"=>107);
//$myPicture->drawFilledRectangle(0,0,700,250,$Settings);

//$Settings = array("StartR"=>219, "StartG"=>231, "StartB"=>139, "EndR"=>1, "EndG"=>138, "EndB"=>68, "Alpha"=>50);
//$myPicture->drawGradientArea(0,0,700,250,DIRECTION_VERTICAL,$Settings);
//
//$myPicture->drawRectangle(0,0,700,250,array("R"=>0,"G"=>0,"B"=>0));

//$myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>50,"G"=>50,"B"=>50,"Alpha"=>20));

//$myPicture->setFontProperties(array("R"=>0,"G"=>0,"B"=>0,"FontName"=>"./Ubuntu-B.ttf","FontSize"=>14));
//$TextSettings = array("Align"=>TEXT_ALIGN_MIDDLEMIDDLE, "R"=>255, "G"=>255, "B"=>255);
//$myPicture->drawText(350,25,"Sumárny graf",$TextSettings);

$myPicture->setShadow(FALSE);
$myPicture->setGraphArea(40,30,680,215);
$myPicture->setFontProperties(array("R"=>0,"G"=>0,"B"=>0,"FontName"=>"./Ubuntu-B.ttf","FontSize"=>8));

$Settings = array("Pos"=>SCALE_POS_LEFTRIGHT
, "Mode"=>SCALE_MODE_FLOATING
, "LabelingMethod"=>LABELING_ALL
, "GridR"=>100, "GridG"=>100, "GridB"=>100, "GridAlpha"=>50, "TickR"=>0, "TickG"=>0, "TickB"=>0, "TickAlpha"=>50, "LabelRotation"=>0, "CycleBackground"=>1, "DrawXLines"=>1, "DrawSubTicks"=>1, "SubTickR"=>255, "SubTickG"=>0, "SubTickB"=>0, "SubTickAlpha"=>50, "DrawYLines"=>ALL);
$myPicture->drawScale($Settings);

$myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>50,"G"=>50,"B"=>50,"Alpha"=>10));

$Config = "";
$myPicture->drawLineChart($Config);

$Config = array("FontR"=>0, "FontG"=>0, "FontB"=>0, "FontName"=>"./Ubuntu-B.ttf", "FontSize"=>8, "Margin"=>6, "Alpha"=>30, "BoxSize"=>5, "Style"=>LEGEND_NOBORDER, "Mode"=>LEGEND_HORIZONTAL);
$myPicture->drawLegend(400,12,$Config);

$myPicture->stroke();

unset($_SESSION['flash']);
?>
