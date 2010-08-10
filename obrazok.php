<?php
if(!isset ($_GET['id']))
    exit();
 
require 'datab_con.php';
$result = mysql_query("SELECT path FROM bannery WHERE id={$_GET['id']}") or die();
mysql_close($conn);
$image = mysql_fetch_array($result);
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
$img = imagecreatefromstring(file_get_contents($image['path'])); //autodetekcia typu

//zobrazenie
header("Content-type: image/png");
imagepng($img); 
imagedestroy($img);
?>
