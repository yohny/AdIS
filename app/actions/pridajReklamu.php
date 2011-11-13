<?php
/**
 * pridanie reklamy
 *
 * @version    1.0
 * @package    AdIS
 * @subpackage actions
 * @author     Ján Neščivera <jan.nescivera@gmail.com>
 *
 */

if (Context::getInstance()->getUser()->kategoria != 'zobra')
    throw new Exception("Nepovolený prístup!");
if (!isset($_POST['meno']) || !isset($_POST['velkost']) ||
    !isset($_POST['kategorie']) || !is_numeric($_POST['velkost']) ||
    !isset($_POST['csrf_token']) || empty($_POST['meno']) ||
    empty($_POST['kategorie']))
    throw new Exception("Neplatné údaje!");
if($_POST['csrf_token'] != Context::getInstance()->getCsrfToken())
    throw new Exception("Chyba - CSRF!");

$velkost = Context::getInstance()->getDatabase()->getVelkostByPK($_POST['velkost']);
if (!$velkost)
    $message = "Nepodarilo sa získať veľkost";
else
    $message = Reklama::checkAd($_POST['meno'], $velkost);

if (!$message)
{
    $reklama = new Reklama(null, Context::getInstance()->getUser()->id, $velkost, $_POST['meno']);
    if ($reklama->save($_POST['kategorie']))
        $message = "Reklama bola úspešne uložená.";
    else
        $message = "Nepodarilo sa uložiť reklamu.";
}

Context::getInstance()->getResponse()->setFlash($message);
Context::getInstance()->getResponse()->redirect = '/reklamy';
?>