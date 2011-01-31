<?php
require_once './classes/Autoloader.class.php';
spl_autoload_register('Autoloader::loadCore');
spl_autoload_register('Autoloader::loadModel');

session_name('adis_session');
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