<?php
/**
 * prihlasenie
 *
 * @version    1.0
 * @package    AdIS
 * @subpackage actions
 * @author     Ján Neščivera <jan.nescivera@gmail.com>
 *
 */

if(!isset($_POST['login']) || !isset($_POST['heslo']) || !isset($_POST['csrf_token']))
    throw new Exception("Nekompletné údaje!");
if($_POST['csrf_token'] != Context::getInstance()->getCsrfToken())
    throw new Exception("Chyba - CSRF!");

$user =  Context::getInstance()->getDatabase()->getUserByCredentials($_POST['login'], $_POST['heslo']);
if ($user != null)
{
    $_SESSION['user'] = $user;
    $user->setLoginTimeNow();
    $message = "Úspešne ste sa prihlásili!";
}
else
    $message = "Chyba - neplatné prihlasovacie údaje!";

Context::getInstance()->getResponse()->setFlash($message);
Context::getInstance()->getResponse()->redirect = '/';
?>