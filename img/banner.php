<?php
require_once '../app/lib/Autoloader.class.php';
Autoloader::registerCore();
Autoloader::registerModel();

session_name('adis_session');
session_start();

if (!isset($_SESSION['user']) || !isset($_GET['id']) || !is_numeric($_GET['id']))
{
    header("HTTP/1.1 403 Forbidden");
    exit();
}

try
{
    $db = new Database();
    if(!$banner = $db->getBannerByPK($_GET['id']))
    {
        header("HTTP/1.1 404 No banner");
        exit();
    }
    if($banner->userId != $_SESSION['user']->id)
    {
        header("HTTP/1.1 403 Wrong user");
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
