<?php
if (!isset($_POST['zmaz']) || !is_numeric($_POST['zmaz']) || !isset($_POST['csrf_token']))
{
    echo 'Neplatne data';
    return;
}
if($_POST['csrf_token'] != Context::getInstance()->getCsrfToken())
{
    echo 'CSRF fail!';
    return;
}

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
header("Location: " . (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "/"));
?>
