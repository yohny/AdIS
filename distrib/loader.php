<?php
function __autoload($className)
{
    if(file_exists('../classes/core/'.$className.'.php'))
        require_once '../classes/core/'.$className.'.php';
    if(file_exists('../classes/model/'.$className.'.php'))
        require_once '../classes/model/'.$className.'.php';
    if(file_exists('../classes/model/base/'.$className.'.php'))
        require_once '../classes/model/base/'.$className.'.php';
}

$file = substr($_SERVER['REDIRECT_URL'], 1).'.php';
if(file_exists($file))
    require_once $file;
else
    header("HTTP/1.1 404 Not Found");
?>
