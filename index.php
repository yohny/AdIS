<?php
/**
 * main front controller of AdIS application, app entry point
 *
 * @version    1.0
 * @package    AdIS
 * @author     Ján Neščivera <jan.nescivera@gmail.com>
 *
 * @todo rafactor to trully use one front controller (at least for /distrib use this one too, form /img its optional)
 * problem with image generation (in getBanner of /distrib and in /img)
 * @todo add unit tests
 */

mb_internal_encoding("utf-8");
date_default_timezone_set('Europe/Bratislava');

require_once './app/lib/Autoloader.class.php';
Autoloader::registerCore();
Autoloader::registerModel();

session_name('adis_session');
session_start();

define('TEMPLATES_DIR', Config::getBaseDir().'/app/templates/');
$request = Context::getInstance()->getRequest();

if (!$request->fileExists)
{
    header("HTTP/1.1 404 Not Found");
    Context::getInstance()->getResponse()->content = "{$_SERVER['REQUEST_URI']} nenájdené!";
    Context::getInstance()->getResponse()->error = true;
}
elseif(!$request->isPublic && !Context::getInstance()->getUser())
{
    header("HTTP/1.1 401 Not authorized");
    Context::getInstance()->getResponse()->content = "Nepovolený pristup!";
    Context::getInstance()->getResponse()->error = true;
}
else //vsetko ok
{
    ob_start();
    try
    {
        require_once $request->getUri();
        Context::getInstance()->getResponse()->content = ob_get_contents();
    }
    catch(Exception $ex)
    {
        Context::getInstance()->getResponse()->content = $ex->getMessage();
        Context::getInstance()->getResponse()->error = true;
    }
    ob_end_clean();
}

//posle header
header("Content-type: ".Context::getInstance()->getResponse()->getHeaderContentType());

//redirect sa nastavuje az na konci, tj ak bola chyba nebude nastaveny
if(Context::getInstance()->getResponse()->redirect)
    header("Location: ".Context::getInstance()->getResponse()->redirect);
elseif(!$request->hasTemplate)
    echo Context::getInstance()->getResponse();
else
    require TEMPLATES_DIR.'/layout.php';
?>