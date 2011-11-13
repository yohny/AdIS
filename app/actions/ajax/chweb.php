<?php
/**
 * AJAX zmena webu
 *
 * @version    1.0
 * @package    AdIS
 * @subpackage actions
 * @author     Ján Neščivera <jan.nescivera@gmail.com>
 *
 */

Context::getInstance()->getResponse()->setHeaderContentType('application/json');

if (!isset($_POST['web']) || !isset($_POST['csrf_token']))
    throw new Exception("Nekompletné údaje!");
if($_POST['csrf_token'] != Context::getInstance()->getCsrfToken())
    throw new Exception("Chyba - CSRF!");

if (!User::validUrl($_POST['web']))
    $resp = array('success' => false, 'message' => 'Neplatná webová adresa!');
elseif(!User::isWebUnique($_POST['web']))
    $resp = array('success' => false, 'message' => 'Tento web už je registrovaný!');
elseif (Context::getInstance()->getUser()->setWeb($_POST['web']))
    $resp = array('success' => true, 'message' => 'WWW adresa zmenená.');
else
    $resp = array('success' => false, 'message' => 'Nepodarilo sa zmeniť web!');

echo json_encode($resp);
?>
