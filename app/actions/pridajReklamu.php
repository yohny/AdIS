<?php
if (Context::getInstance()->getUser()->kategoria != 'zobra')
{
    echo "Nepovolený prístup";
    return;
}
if (!isset($_POST['meno']) ||
    !isset($_POST['velkost']) ||
    !isset($_POST['kategorie']) ||
    !is_numeric($_POST['velkost']) ||
    !isset($_POST['csrf_token']) ||
    empty($_POST['meno']) ||
    empty($_POST['kategorie']))
{
    echo "Neplatné údaje";
    return;
}
if($_POST['csrf_token'] != Context::getInstance()->getCsrfToken())
{
    echo 'CSRF fail!';
    return;
}

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