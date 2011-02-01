<?php
require_once '../classes/Autoloader.class.php';
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
    header("HTTP/1.1 500 DB error");
    exit();
}

if (!$fileContent = @file_get_contents('../upload/' . $banner->filename))
{
    header("HTTP/1.1 500 File error");
    exit();
}
$img = imagecreatefromstring($fileContent);
$watermark = imagecreate(imagesx($img), 15);
imagecolorallocate($watermark, 0, 0, 0); //black - first color becomes background
$white = imagecolorallocate($watermark, 255, 255, 255);
if(!imagettftext($watermark, 10, 0, imagesx($watermark) - 40, 12, $white, './Ubuntu-B.ttf', 'Ad-IS'))
{
    $zobrazenie->delete($db);
    header("HTTP/1.1 500 TTF error");
    exit();
}
imagecopymerge($img, $watermark, 0, imagesy($img) - imagesy($watermark), 0, 0, imagesx($watermark), imagesy($watermark), 50);
header("Content-type: image/png");
imagepng($img);
imagedestroy($img);
?>
