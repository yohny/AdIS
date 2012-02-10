<?php
/**
 * generator obrazku banneru
 *
 * @version    1.0
 * @package    AdIS
 * @subpackage images
 * @author     Ján Neščivera <jan.nescivera@gmail.com>
 *
 */

require_once '../app/lib/Autoloader.class.php';
Autoloader::registerCore();
Autoloader::registerModel();

define('BASE_DIR',__DIR__."/..");

if (!isset($_GET['id']) || !is_numeric($_GET['id']))
{
    header("HTTP/1.1 403 Forbidden");
    exit();
}

try
{
    $db = new Database();
    if(!$banner = $db->getBannerByPK($_GET['id']))
    {
        header("HTTP/1.1 404 Not Found");
        exit();
    }
}
catch (Exception $ex)
{
    header("HTTP/1.1 500 Error: exception");
    exit();
}

if(!$img = $banner->getImgWithWatermark())
{
    header("HTTP/1.1 500 Image error");
    exit();
}
header("Content-type: image/png");
imagepng($img);
imagedestroy($img);
?>
