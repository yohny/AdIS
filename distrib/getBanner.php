<?php
if (!isset($_GET['id']) || !is_numeric($_GET['id']))
{
    header("HTTP/1.1 403 Forbidden");
    exit();
}

//TODO checking ci request prisiel zo servra nejakeho zobrazovatela
//ak hej overit ci te zobrazovatel ma reklamu do ktorej sa "vojde" tento banner,
//ak hej tak checknut ci v nej ma zobrazany tento banner
// - neda sa len cez db bo moze mat viacero navstevnikov zobrazenu jeho stranku, vymysliet cookie
//napr ak by getScript nastavil cookie s PK zobrazenia a potom cez to by sa checkovalo

try
{
    $db = new Database();
    //overenie requestu
    if(!isset($_SERVER['HTTP_REFERER']) || !$user = $db->getUserByReferer($_SERVER['HTTP_REFERER']))
    {
        header("HTTP/1.1 403 Forbidden");
        exit();
    }
    //vytiahne banner z DB
    $banner = $db->getBannerByPK($_GET['id']);
    if (!$banner)
        exit();
}
catch (Exception $ex)
{
    header("HTTP/1.1 500 Error");
    exit();
}

/* $info = getimagesize($image); //[0]-width,[1]-height,[2]-type,[3]-height+width from img tags
  switch($info[2])
  {
  case 1: $img = imagecreatefromgif("$image");
  break;
  case 2: $img = imagecreatefromjpeg("$image");
  break;
  case 3: $img = imagecreatefrompng("$image");
  break;
  } */
$img = imagecreatefromstring(file_get_contents('../upload/' . $banner->filename));

$watermark = imagecreate(imagesx($img), 15);
imagecolorallocate($watermark, 0, 0, 0); //black - first color becomes background
$white = imagecolorallocate($watermark, 255, 255, 255);
imagettftext($watermark, 10, 0, imagesx($watermark) - 40, 12, $white, '../img/Ubuntu-B.ttf', 'Ad-IS');
imagecopymerge($img, $watermark, 0, imagesy($img) - imagesy($watermark), 0, 0, imagesx($watermark), imagesy($watermark), 50);

//zobrazenie
header("Content-type: image/png");
imagepng($img);
imagedestroy($img);
?>
