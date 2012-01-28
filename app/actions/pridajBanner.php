<?php
/**
 * pridanie banneru
 *
 * @version    1.0
 * @package    AdIS
 * @subpackage actions
 * @author     Ján Neščivera <jan.nescivera@gmail.com>
 *
 */

if(Context::getInstance()->getUser()->kategoria!='inzer')
    throw new Exception("Nepovolený prístup!");
if (!isset($_FILES['userfile']) || !isset($_POST['velkost']) ||
    !isset($_POST['kategorie']) || !is_numeric($_POST['velkost']) ||
    !isset($_POST['csrf_token']) || empty($_FILES['userfile']) ||
    empty($_POST['kategorie']))
    throw new Exception("Neplatné údaje!");
if($_POST['csrf_token'] != Context::getInstance()->getCsrfToken())
    throw new Exception("Chyba - CSRF!");

$velkost = Context::getInstance()->getDatabase()->getVelkostByPK($_POST['velkost']);
if (!$velkost)
    $message = "Nepodarilo sa získať veľkost";
else
    $message = Banner::checkFile($_FILES['userfile'], $velkost);

if (!$message) //banner je OK
{
    $uploadname = Banner::createFilename($_FILES['userfile']['name'], $velkost);
    if (is_uploaded_file($_FILES['userfile']['tmp_name']) && move_uploaded_file($_FILES['userfile']['tmp_name'], Config::getUploadDir().$uploadname))
    {
        $banner = new Banner(null, Context::getInstance()->getUser()->id, $velkost, $uploadname);
        if ($banner->save($_POST['kategorie']))
            $message = "Banner bol úspešne uložený.";
        else
        {
            $message = "Nepodarilo sa uložiť banner.";
            unlink(Config::getUploadDir().$uploadname);
        }
    }
    else
        $message = "Zlyhalo uploadovanie súboru!";
}

Context::getInstance()->getResponse()->setFlash($message);
Context::getInstance()->getResponse()->redirect = '/bannery';
?>