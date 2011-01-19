<?php
function __autoload($className)
{
    if(file_exists('classes/core/'.$className.'.php'))
        require_once 'classes/core/'.$className.'.php';
    if(file_exists('classes/model/'.$className.'.php'))
        require_once 'classes/model/'.$className.'.php';
    if(file_exists('classes/model/base/'.$className.'.php'))
        require_once 'classes/model/base/'.$className.'.php';
}

session_name('adis_session');
//session_set_cookie_params(10);
session_start();

$request = Context::getInstance()->getRequest();

if (!$request->fileExists)
{
    header("HTTP/1.1 404 Not Found");
    Context::getInstance()->getResponse()->content = "{$_SERVER['REQUEST_URI']} nenájdené!";
    Context::getInstance()->getResponse()->error = true;
}
elseif(!$request->isPublic && !Context::getInstance()->getUser())
{
    header("HTTP/1.1 403 Forbidden");
    Context::getInstance()->getResponse()->content = "Nepovolený pristup!";
    Context::getInstance()->getResponse()->error = true;
}
else //vsetko ok
{
    ob_start();
    require_once $request->getUri();
    Context::getInstance()->getResponse()->content = ob_get_clean();
}

header(Context::getInstance()->getResponse()->getHeaderContentType());
if ($request->hasTemplate)
    require 'templates/layout.php';
else
    echo Context::getInstance()->getResponse();
?>