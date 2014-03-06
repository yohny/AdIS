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

if(!isset($_POST['login'], $_POST['heslo'], $_POST['csrf_token']))
    throw new Exception("Nekompletné údaje!");
if($_POST['csrf_token'] != Context::getInstance()->getCsrfToken())
    throw new Exception("Chyba - CSRF!");

$user =  Context::getInstance()->getDatabase()->getUserByCredentials($_POST['login'], $_POST['heslo']);
if ($user != null)
{
    $_SESSION['user'] = $user;
    $user->setLoginTimeNow();
	if($_POST['neodhlasovat'] === "neodhlasovat")
	{
		$user->requestsExpire = false;
		setcookie('neodhlasovat', $user->id.'|'.md5($user->getLogin()), time()+10*24*3600, '/', null, false, true);
	}
	$message = "Úspešne ste sa prihlásili!";
}
else
    $message = "Chyba - neplatné prihlasovacie údaje!";

Context::getInstance()->getResponse()->setFlash($message);
Context::getInstance()->getResponse()->redirect = '/';
?>