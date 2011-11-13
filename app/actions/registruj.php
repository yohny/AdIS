<?php
/**
 * registracia
 *
 * @version    1.0
 * @package    AdIS
 * @subpackage actions
 * @author     Ján Neščivera <jan.nescivera@gmail.com>
 *
 */

if (!isset($_POST['user']) || !isset($_POST['csrf_token']))
    throw new Exception("Nekompletné údaje!");
if($_POST['csrf_token'] != Context::getInstance()->getCsrfToken())
    throw new Exception("Chyba - CSRF!");

$user = $_POST['user'];
if (!isset($user['login']) || !isset($user['heslo']) || !isset($user['web']) || !isset($user['skupina']) || !isset($user['captcha']))
    throw new Exception("Nekompletné registračné udaje!");

Autoloader::registerCaptcha();
$captcha = new Securimage();

if(!$captcha->check($user['captcha']))
    $message = "Neplatná captcha!";
elseif ($resp = User::validateInput($user))
    $message = $resp;
else
{
        if (User::create($user['login'], $user['heslo'], $user['web'], $user['skupina']))
            $message = "Registrácia úspešná.";
        else
            $message = "Registrácia neúspešná.";
}

$_SESSION['registrator'] = $user;
Context::getInstance()->getResponse()->setFlash($message);

Context::getInstance()->getResponse()->redirect = '/registracia';
?>