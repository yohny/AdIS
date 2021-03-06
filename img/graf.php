<?php
/**
 * generator obrazku grafu
 *
 * @version    1.0
 * @package    AdIS
 * @subpackage images
 * @author     Ján Neščivera <jan.nescivera@gmail.com>
 *
 */

mb_internal_encoding("utf-8");
date_default_timezone_set('Europe/Bratislava');

require_once '../app/lib/Autoloader.class.php';
Autoloader::registerModel();
Autoloader::registerPChart();

session_name('adis_session');
session_set_cookie_params(0, "/", "", false, true);
session_start();

if (!isset($_SESSION['user']))
{
    header("HTTP/1.1 401 Unauthorized");
    exit();
}

if(isset($_GET["imageMap"]))//image map requeste
{
    $myPicture = new pImage(710, 250);//dummy object to be able to call dumpImageMap
    $myPicture->dumpImageMap("imagemap_for_graph");
}
elseif(!isset($_SESSION['stats']))//image requested, session with data must be sat
{
   header("HTTP/1.1 500 No stats data");
   exit();
}

$stats = $_SESSION['stats'];
unset($_SESSION['stats']);

$dates = array();$clicks = array();$views = array();

/* @var $stat Statistika */
foreach ($stats as $stat)
{
    $dates[] = $stat->den->format("d.m.Y");
    $clicks[] = $stat->kliky;
    $views[] = $stat->zobrazenia;
}

$myData = new pData();
//kliky
$myData->addPoints($clicks, "clicks");
$myData->setSerieDescription("clicks", "Kliknutia");
$myData->setSerieWeight('clicks', 1.5);
//zobrazenia
$myData->addPoints($views, "views");
$myData->setSerieDescription("views", "Zobrazenia");
$myData->setSerieWeight('views', 1.5);
//hodnotova suradnicova os
$myData->setAxisName(0, "Počet");
//datumy
$myData->addPoints($dates, "dates");
$myData->setAbscissa("dates");
//farebna paleta
$myData->setPalette('clicks', array("R" => 0, "G" => 0, "B" => 0, "Alpha" => 50));
$myData->setPalette('views', array("R" => 255, "G" => 255, "B" => 255, "Alpha" => 50));

$myPicture = new pImage(710, 250, $myData, TRUE); //posledny param TRUE - transparent background
//scale - mierka a pravitko/mreza na pozadi grafu
$myPicture->setGraphArea(35, 20, 700, 220);
$myPicture->setFontProperties(array("R" => 255, "G" => 255, "B" => 255, "FontName" => "./Ubuntu-B.ttf", "FontSize" => 8));
$myPicture->initialiseImageMap("imagemap_for_graph", IMAGE_MAP_STORAGE_SESSION);
$Settings = array("Pos" => SCALE_POS_LEFTRIGHT, "Mode" => SCALE_MODE_START0,
    "GridR" => 255, "GridG" => 255, "GridB" => 255, "GridAlpha" => 50,
    "TickR" => 255, "TickG" => 255, "TickB" => 255,
    "CycleBackground" => FALSE, "XMargin" => 25, "YMargin" => 20);
$myPicture->drawScale($Settings);
//samotny priebeh hodnot
$myPicture->setShadow(TRUE);
$myPicture->setFontProperties(array("R" => 255, "G" => 255, "B" => 255, "FontName" => "./Ubuntu-B.ttf", "FontSize" => 10));
$Config = array("DisplayColor" => DISPLAY_AUTO,"RecordImageMap" => TRUE);
$myPicture->drawLineChart($Config);
 $myPicture->drawPlotChart(array("PlotBorder"=>TRUE,"BorderSize"=>1)   ); //'points' chart on top
//legenda
$Config = array("FontR" => 255, "FontG" => 255, "FontB" => 255, "FontName" => "./Ubuntu-B.ttf", "FontSize" => 9,
    "Margin" => 6, "Alpha" => 50, "BoxSize" => 7, "Style" => LEGEND_NOBORDER, "Mode" => LEGEND_HORIZONTAL);
$myPicture->drawLegend(50, 5, $Config);
//out
$myPicture->stroke();
?>