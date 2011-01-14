<?php
if(!isset($_POST['zmaz']))
    exit('Nekompletne data');

require '../base/model/User.php'; //pred session_start
session_start();
require '../base/secure.php';
$user = $_SESSION['user']; /* @var $user User */
require '../base/Database.php';
try
{
    $db = new Database();
}
catch (Exception $ex)
{
    $_SESSION['flash'] = $ex->getMessage();
    $referer = $_SERVER['HTTP_REFERER'];
    header("Location: $referer");
    exit();
}


if($user->kategoria=='inzer') //maze banner
{
    $banner = $db->getBannerByPK($_POST['zmaz']);
    if(!$banner || $banner->userId!=$user->id)
        $message = 'Nemôžete zmazať tento banner!';
    else
    {
        if($banner->delete($db))
            $message = "Banner '$banner->filename' zmazaný!";
        else
            $message = "Banner '$banner->filename' sa nepodarilo zmazať!";
    }        
}
if($user->kategoria=='zobra') //maze reklamu
{
    $reklama = $db->getReklamaByPK($_POST['zmaz']);
    if(!$reklama || $reklama->userId!=$user->id)
        $message = 'Nemôžete zmazať túto reklamu!';
    else
    {
        if($reklama->delete($db))
            $message = "Relama '$reklama->name' zmazaná!";
        else
            $message = "Reklamu '$reklama->name' sa nepodarilo zmazať!";
    }
}

$_SESSION['flash'] = $message;
$referer = $_SERVER['HTTP_REFERER'];
header("Location: $referer");
?>
