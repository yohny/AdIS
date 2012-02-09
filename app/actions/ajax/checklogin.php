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

Context::getInstance()->getResponse()->setHeaderContentType('application/json');

if (!isset($_GET['login']))
    throw new Exception("Nekompletné údaje!");

if (User::isLoginUnique($_GET['login']))
    $resp = array('success' => true, 'message' => 'Váš login je vporiadku.');
else
    $resp = array('success' => false, 'message' => 'Neplatný login! Zvoľte iný.');

echo json_encode($resp);
?>
