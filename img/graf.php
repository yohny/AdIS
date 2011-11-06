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
require_once '../app/lib/Autoloader.class.php';
Autoloader::registerModel();
Autoloader::registerPChart();

session_name('adis_session');
session_start();

if (!isset($_SESSION['user']) || !isset($_SESSION['stats']))
{
    header("HTTP/1.1 403 Forbidden");
    exit();
}

$stats = $_SESSION['stats'];
unset($_SESSION['stats']);

$dates = array();$clicks = array();$views = array();
foreach ($stats as $stat)
{
    $dates[] = date_format(new DateTime($stat->cas), 'd.m.Y');
    $clicks[] = $stat->kliky;
    $views[] = $stat->zobrazenia;
}

$myData = new pData();
//kliky
$myData->addPoints(array_reverse($clicks), "clicks");
$myData->setSerieDescription("clicks", "Kliknutia");
$myData->setSerieWeight('clicks', 1);
$myData->setSerieOnAxis("clicks", 0);
//zobrazenia
$myData->addPoints(array_reverse($views), "views");
$myData->setSerieDescription("views", "Zobrazenia");
$myData->setSerieWeight('views', 1);
$myData->setSerieOnAxis("views", 0);
//hodnotova suradnicova os
$myData->setAxisPosition(0, AXIS_POSITION_LEFT);
$myData->setAxisName(0, "Počet");
$myData->setAxisUnit(0, "");
//datumy
$myData->addPoints(array_reverse($dates), "dates");
$myData->setSerieDescription("dates", "Dátum");
$myData->setAbscissa("dates");
//farebna paleta
$myData->setPalette('clicks', array("R" => 0, "G" => 0, "B" => 0, "Alpha" => 50));
$myData->setPalette('views', array("R" => 255, "G" => 255, "B" => 255, "Alpha" => 50));

$myPicture = new pImage(710, 250, $myData, TRUE); //posledny param TRUE - transparent background
//scale - mierka a pravitko/mreza na pozadi grafu
$myPicture->setGraphArea(35, 20, 700, 220);
$myPicture->setFontProperties(array("R" => 255, "G" => 255, "B" => 255, "FontName" => "./Ubuntu-B.ttf", "FontSize" => 8));
$Settings = array("Pos" => SCALE_POS_LEFTRIGHT, "Mode" => SCALE_MODE_START0,
    "GridR" => 255, "GridG" => 255, "GridB" => 255, "GridAlpha" => 50,
    "TickR" => 255, "TickG" => 255, "TickB" => 255,
    "CycleBackground" => FALSE, "XMargin" => 25, "YMargin" => 20);
$myPicture->drawScale($Settings);
//samotny priebeh hodnot
$myPicture->setShadow(TRUE);
$myPicture->setFontProperties(array("R" => 255, "G" => 255, "B" => 255, "FontName" => "./Ubuntu-B.ttf", "FontSize" => 10));
$Config = array("DisplayValues" => TRUE, "DisplayColor" => DISPLAY_AUTO);
$myPicture->drawLineChart($Config);
//legenda
$Config = array("FontR" => 255, "FontG" => 255, "FontB" => 255, "FontName" => "./Ubuntu-B.ttf", "FontSize" => 9,
    "Margin" => 6, "Alpha" => 50, "BoxSize" => 7, "Style" => LEGEND_NOBORDER, "Mode" => LEGEND_HORIZONTAL);
$myPicture->drawLegend(50, 5, $Config);
//out
$myPicture->stroke();
?>