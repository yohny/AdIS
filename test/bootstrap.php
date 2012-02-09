<?php
/**
 * bootstrap for unit tests
 *
 * @version    1.0
 * @package    AdIS
 * @subpackage UnitTests
 * @author     Ján Neščivera <jan.nescivera@gmail.com>
 *
 * @todo add unit tests
 */

mb_internal_encoding("utf-8");
date_default_timezone_set('Europe/Bratislava');

session_name('adis_session');
session_start();

define('BASE_DIR',__DIR__.'/..');
define('ACTIONS_DIR', BASE_DIR.'/app/actions');
define('TEMPLATES_DIR', BASE_DIR.'/app/templates');

require_once BASE_DIR.'/app/lib/Autoloader.class.php';
Autoloader::registerAll();
?>
