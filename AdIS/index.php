<?php
//var_dump($_SERVER);
function __autoload($className)
{
    if(file_exists('classes/model/'.$className.'.php'))
        require_once 'classes/model/'.$className.'.php';
    if(file_exists('classes/core/'.$className.'.php'))
        require_once 'classes/core/'.$className.'.php';
}

session_start();

$request = Context::getInstance()->getRequest();

if (!$request->fileExists)
{
    header("HTTP/1.1 404 Not Found");
    Context::getInstance()->getResponse()->content = $_SERVER['REQUEST_URI'] . ' not found';
}
elseif(!$request->isPublic && !Context::getInstance()->getUser())
{
    header("HTTP/1.1 403 Forbidden");
    Context::getInstance()->getResponse()->content = 'Nepovolený pristup';
}
else
{
    ob_start();
    require_once $request->getUri();
    Context::getInstance()->getResponse()->content = ob_get_clean();
}

if (!$request->hasTemplate)
    echo Context::getInstance()->getResponse();
else
    require 'templates/layout.php';
?>