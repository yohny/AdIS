<?php
/**
 * poskytnutie banneru
 *
 * @version    1.0
 * @package    AdIS
 * @subpackage distribution
 * @author     Ján Neščivera <jan.nescivera@gmail.com>
 *
 */

if (!isset($_GET['id'], $_GET['view']) || !ctype_digit($_GET['id']) || !ctype_digit($_GET['view']))
{
    header("HTTP/1.1 403 Zle parametre");
    exit();
}

try
{
    $db = new Database();
    //overenie requestu
    if (!isset($_SERVER['HTTP_REFERER']) || !$user = $db->getUserByReferer($_SERVER['HTTP_REFERER']))
    {
        header("HTTP/1.1 403 Zly zdroj");
        exit();
    }
    if (!$zobrazenie = $db->getZobrazenieByPK($_GET['view']))
    {
        header("HTTP/1.1 403 Neexist. zobr.");
        exit();
    }
    if ($zobrazenie->bannerId != $_GET['id'] || $zobrazenie->zobraId != $user->id || $zobrazenie->isClicked())
    {
        header("HTTP/1.1 403 Neplatne");
        exit();
    }
    //vytiahne banner z DB
    if (!$banner = $db->getBannerByPK($_GET['id']))
    {
        header("HTTP/1.1 500 Error");
        $zobrazenie->delete($db);
        exit();
    }
    //v error handleri nepozna externe premenne - neda sa pouzit na mazanie
    //a ak je zadefinovany tak zbehne aj pre prikazy so @
}
catch (Exception $ex)
{
    header("HTTP/1.1 500 Error: exception");
    exit();
}

if(!$img = $banner->getImgWithWatermark())
{
    $zobrazenie->delete($db);
    header("HTTP/1.1 500 Image error");
    exit();
}
header("Content-type: image/png");
imagepng($img);
imagedestroy($img);
?>
