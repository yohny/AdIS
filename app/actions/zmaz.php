<?php
/**
 * odstranenie banneru/reklamy
 *
 * @version    1.0
 * @package    AdIS
 * @subpackage actions
 * @author     Ján Neščivera <jan.nescivera@gmail.com>
 *
 */

if (!isset($_POST['zmaz']) || !is_numeric($_POST['zmaz']) || !isset($_POST['csrf_token']))
    throw new Exception("Neplatné údaje!");
if($_POST['csrf_token'] != Context::getInstance()->getCsrfToken())
    throw new Exception("Chyba - CSRF!");

if (Context::getInstance()->getUser()->kategoria == 'inzer') //maze banner
{
    $object = Context::getInstance()->getDatabase()->getBannerByPK($_POST['zmaz']);
    $notAllowedMsg = 'Nemôžete zmazať tento banner!';
    $okMsg = "Banner '$object' zmazaný!";
    $failMsg = "Banner '$object' sa nepodarilo zmazať!";
}
elseif (Context::getInstance()->getUser()->kategoria == 'zobra') //maze reklamu
{
    $object = Context::getInstance()->getDatabase()->getReklamaByPK($_POST['zmaz']);
    $notAllowedMsg = 'Nemôžete zmazať túto reklamu!';
    $okMsg = "Relama '$object' zmazaná!";
    $failMsg = "Reklamu '$object' sa nepodarilo zmazať!";
}
else
{
    $object = null;
    $notAllowedMsg = 'Nemôžete zmazať tento objekt!';
}

if (!$object || $object->userId != Context::getInstance()->getUser()->id)
    $message = $notAllowedMsg;
else
    $message = $object->delete()?$okMsg:$failMsg;

Context::getInstance()->getResponse()->setFlash($message);
Context::getInstance()->getResponse()->redirect = Context::getInstance()->getUser()->kategoria == 'inzer'?'/bannery':'/reklamy';
?>
