<?php
if(!isset ($_GET['id']))
    exit();

if (!preg_match('/[1-9][0-9]*/', $_GET['id']))
    exit();
 
require '../base/Database.php';
$db = new Database();
$banner = $db->getBannerById($_GET['id']);

/*$info = getimagesize($image); //[0]-width,[1]-height,[2]-type,[3]-height+width from img tags
switch($info[2]) 
{
  case 1: $img = imagecreatefromgif("$image");
          break;
  case 2: $img = imagecreatefromjpeg("$image");
          break;
  case 3: $img = imagecreatefrompng("$image");
          break;
}*/
$img = imagecreatefromstring(file_get_contents('../upload/'.$banner->filename));

//zobrazenie
header("Content-type: image/png");
imagepng($img); 
imagedestroy($img);
?>
