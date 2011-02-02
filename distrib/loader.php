<?php
require_once '../app/lib/Autoloader.class.php';
Autoloader::registerCore();
Autoloader::registerModel();

$file = substr($_SERVER['REDIRECT_URL'], 1).'.php';
if(file_exists($file))
    require_once $file;
else
    header("HTTP/1.1 404 Not Found");
?>
