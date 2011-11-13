<?php
/**
 * AJAX overenie unikatnosti loginu
 *
 * @version    1.0
 * @package    AdIS
 * @subpackage actions
 * @author     Ján Neščivera <jan.nescivera@gmail.com>
 *
 */

Context::getInstance()->getResponse()->setHeaderContentType('text/plain');

if (!isset($_GET['login']))
    throw new Exception("Nekompletné údaje!");

if (User::isLoginUnique($_GET['login']))
    echo "<span class='g'>Váš login je vporiadku.</span>";
else
    echo "<span class='r'>Neplatný login! Zvoľte iný.</span>";
?>
