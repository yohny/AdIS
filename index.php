<?php
/**
 * main front controller of AdIS application, app entry point
 *
 * @version    1.0
 * @package    AdIS
 * @author     Ján Neščivera <jan.nescivera@gmail.com>
 *
 */

mb_internal_encoding("utf-8");
date_default_timezone_set('Europe/Bratislava');

require_once './app/lib/Autoloader.class.php';
Autoloader::registerCore();
Autoloader::registerModel();

session_name('adis_session');
session_set_cookie_params(0, "/", "", false, true);
session_start();

define('BASE_DIR',__DIR__);
define('ACTIONS_DIR', BASE_DIR.'/app/actions');
define('TEMPLATES_DIR', BASE_DIR.'/app/templates');

//try autologin first
if(isset($_COOKIE['neodhlasovat']))
{
	setcookie('neodhlasovat', $_COOKIE['neodhlasovat'], time()+10*24*3600, '/', null, false, true);//extend cookie validity
	if(!Context::getInstance()->getUser())
	{
		User::autoLogin($_COOKIE['neodhlasovat']);
	}
}

$request = Context::getInstance()->getRequest();
if (!$request->getUri())
{
    header("HTTP/1.1 404 Not Found");
    Context::getInstance()->getResponse()->content = "{$_SERVER['REQUEST_URI']} nenájdené!";
    Context::getInstance()->getResponse()->error = true;
}
elseif(!$request->isPublic && !Context::getInstance()->getUser())
{
	header("HTTP/1.1 401 Unauthorized");
	Context::getInstance()->getResponse()->content = "Nepovolený pristup!";
	Context::getInstance()->getResponse()->error = true;
}
elseif($request->isExpired())
{
    //header("HTTP/1.1 408 Request Timeout"); //produces error in browser
	session_unset();
    session_regenerate_id();
	setcookie ('neodhlasovat', '', 1, '/', null, false, true);
    Context::getInstance()->getResponse()->content = "Boli ste odhlásený kvôli neaktivite vyše ".Config::getInactivityLimit()." sekúnd!";
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
        header("HTTP/1.1 500 Internal Server Error");
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