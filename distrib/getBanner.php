<?php
header("Content-type: image/png");
if (!isset($_GET['id']) || !is_numeric($_GET['id']) || !isset($_GET['view']) || !is_numeric($_GET['view']))
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
        header("HTTP/1.1 403 Neexistujuce");
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
    header("HTTP/1.1 500 DB error");
    exit();
}

if (!$fileContent = @file_get_contents('../upload/' . $banner->filename))
{
    $zobrazenie->delete($db);
    header("HTTP/1.1 500 File error");
    exit();
}
$img = imagecreatefromstring($fileContent);
$watermark = imagecreate(imagesx($img), 15);
imagecolorallocate($watermark, 0, 0, 0); //black - first color becomes background
$white = imagecolorallocate($watermark, 255, 255, 255);
if(!imagettftext($watermark, 10, 0, imagesx($watermark) - 40, 12, $white, '../img/Ubuntu-B.ttf', 'Ad-IS'))
{
    $zobrazenie->delete($db);
    header("HTTP/1.1 500 TTF error");
    exit();
}
imagecopymerge($img, $watermark, 0, imagesy($img) - imagesy($watermark), 0, 0, imagesx($watermark), imagesy($watermark), 50);
imagepng($img);
imagedestroy($img);
?>
