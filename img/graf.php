<?php
require_once '../classes/model/Statistika.php';
require_once '../classes/model/User.php';
session_name('adis_session');
session_start();

if (!isset($_SESSION['user']) || !isset($_SESSION['stats']))
{
    header("HTTP/1.1 403 Forbidden");
    exit();
}

$stats = $_SESSION['stats'];
unset($_SESSION['stats']);

require_once '../classes/pChart/pData.class.php';
require_once '../classes/pChart/pDraw.class.php';
require_once '../classes/pChart/pImage.class.php';

//priprava dat - uz netreba bo v jednom riadku uz je suma vsetkych
//$data = array();
///* @var $stat Statistika */
//foreach ($stats as $stat)
//{
//    if(array_key_exists($stat->__toString(), $data))//ak uz taky datum ma svoje podpole
//    {
//        $data[$stat->__toString()]['clicks']+=$stat->kliky;
//        $data[$stat->__toString()]['views']+=$stat->zobrazenia;
//    }
//    else //ak este nema
//    {
//        $data[$stat->__toString()]=array();
//        $data[$stat->__toString()]['clicks']=$stat->kliky;
//        $data[$stat->__toString()]['views']=$stat->zobrazenia;
//    }
//}
//$dates = array_keys($data);
//function clicks($n){ return $n['clicks'];}
//$clicks = array_map("clicks", $data);
//function views($n){ return $n['views'];}
//$views = array_map("views", $data);
$dates = array();
$clicks = array();
$views = array();
foreach ($stats as $stat)
{
    $dates[] = $stat->__toString();
    $clicks[] = $stat->kliky;
    $views[] = $stat->zobrazenia;
}

$myData = new pData();
$myData->addPoints(array_reverse($clicks), "clicks"); //kliky
$myData->setSerieDescription("clicks", "Kliknutia");
$myData->setSerieWeight('clicks', 1);//hrubka
$myData->setSerieOnAxis("clicks", 0);

$myData->addPoints(array_reverse($views), "views"); //zobra
$myData->setSerieDescription("views", "Zobrazenia");
$myData->setSerieWeight('views', 1);
$myData->setSerieOnAxis("views", 0);

//$myData->setAxisColor(0,array("R"=>200,"G"=>200,"B"=>200));
//$myData->setAxisColor(0,array("R"=>200,"G"=>200,"B"=>200));

$myData->addPoints(array_reverse($dates), "dates");
$myData->setSerieDescription("dates", "Dátum");
$myData->setAbscissa("dates"); //dates nalepime na suradnicovu os

$myData->setAxisPosition(0, AXIS_POSITION_LEFT);
$myData->setAxisName(0, "Počet");
$myData->setAxisUnit(0, "");

$myPicture = new pImage(710, 260, $myData, TRUE); //posledny param TRUE - transparent background - neide
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
$myPicture->setGraphArea(35, 30, 700, 225);
$myPicture->setFontProperties(array("R" => 255, "G" => 255, "B" => 255, "FontName" => "./Ubuntu-B.ttf", "FontSize" => 8));

$Settings = array("Pos" => SCALE_POS_LEFTRIGHT
    , "Mode" => SCALE_MODE_FLOATING
    , "LabelingMethod" => LABELING_ALL
    , "GridR" => 255, "GridG" => 255, "GridB" => 255, "GridAlpha" => 50
    , "TickR" => 255, "TickG" => 255, "TickB" => 255, "TickAlpha" => 50
    , "LabelRotation" => 0, "CycleBackground" => 1, "DrawXLines" => 1, "DrawSubTicks" => 0
    , "SubTickR" => 255, "SubTickG" => 0, "SubTickB" => 0, "SubTickAlpha" => 50, "DrawYLines" => ALL);
$myPicture->drawScale($Settings);

//$myPicture->setShadow(TRUE, array("X" => 1, "Y" => 1, "R" => 50, "G" => 50, "B" => 50, "Alpha" => 10));

$Config = array("DisplayValues"=>TRUE, "DisplayColor"=>DISPLAY_AUTO);
$myPicture->drawLineChart($Config);

$Config = array("FontR" => 255, "FontG" => 255, "FontB" => 255, "FontName" => "./Ubuntu-B.ttf", "FontSize" => 9
    , "Margin" => 6, "Alpha" => 50, "BoxSize" => 5, "Style" => LEGEND_NOBORDER, "Mode" => LEGEND_HORIZONTAL);
$myPicture->drawLegend(50, 12, $Config);

$myPicture->stroke();
?>
