<?php
mb_internal_encoding("utf-8");
require_once '../app/lib/Autoloader.class.php';
Autoloader::registerCore();
Autoloader::registerModel();

$file = '.'.$_SERVER['REDIRECT_URL'].'.php'; //REDIRECT_URL zacina s '/'
if(file_exists($file))
    require_once $file;
else
    header("HTTP/1.1 404 Not Found");
?>
