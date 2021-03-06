<?php
/**
 * front kontroller
 *
 * @version    1.0
 * @package    AdIS
 * @subpackage distribution
 * @author     Ján Neščivera <jan.nescivera@gmail.com>
 *
 */

mb_internal_encoding("utf-8");
date_default_timezone_set('Europe/Bratislava');

require_once '../app/lib/Autoloader.class.php';
Autoloader::registerCore();
Autoloader::registerModel();

define('BASE_DIR',__DIR__."/..");

$url = preg_replace("/\?.*$/", "", $_SERVER['REQUEST_URI']); //odstranenie query stringy
$file = '.'.$url.'.php'; //REQUEST_URI zacina s '/'
if(is_readable($file))
    require $file;
else
    header("HTTP/1.1 404 Not Found");
?>
