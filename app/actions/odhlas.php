<?php
/**
 * odhlasenie
 *
 * @version    1.0
 * @package    AdIS
 * @subpackage actions
 * @author     Ján Neščivera <jan.nescivera@gmail.com>
 *
 */

if(!isset($_POST['action'], $_POST['csrf_token']))
    throw new Exception("Nekompletné údaje!");
if($_POST['csrf_token'] != Context::getInstance()->getCsrfToken())
    throw new Exception("Chyba - CSRF!");

if ($_POST['action'] == "logout")
{
    session_unset();
    //session_destroy(); -nemoze potom nastavit flash
    $message = "Boli ste odhlásený.";
}
else
    $message = "Chybná požiadavka";

Context::getInstance()->getResponse()->setFlash($message);
Context::getInstance()->getResponse()->redirect = '/';
?>