<?php
require_once './app/lib/Autoloader.class.php';
Autoloader::registerCore();
Autoloader::registerModel();

session_name('adis_session');
session_start();

define('TEMPLATES_DIR', './app/templates');
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
    try
    {
        ob_start();
        require_once $request->getUri();
        Context::getInstance()->getResponse()->content = ob_get_clean();
    }
    catch(Exception $ex)
    {
        Context::getInstance()->getResponse()->content = $ex->getMessage();
        Context::getInstance()->getResponse()->error = true;
    }
}

header(Context::getInstance()->getResponse()->getHeaderContentType());
if ($request->hasTemplate)
    require TEMPLATES_DIR.'/layout.php';
else
    echo Context::getInstance()->getResponse();
?>