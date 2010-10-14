<?php
if(!isset ($_FILES['userfile']) || !isset ($_POST['velkost']) || !isset ($_POST['kategorie']))
    exit("Nekompletne data");

require '../base/model/User.php'; //pred session_start
session_start();
require '../base/secure.php';
$user = $_SESSION['user']; /* @var $user User */
require '../base/Database.php';
$db = new Database();

$userfile = $_FILES['userfile'];
$kategorie = $_POST['kategorie'];
$message = "";

$velkost = $db->getVelkostById($_POST['velkost']);
if(!$velkost)
    exit ("Nepodarilo sa ziskat velkost");

$uploadname = $userfile['name'];                                //len meno bez cesty -od verzie x.x uz netreba basename() lebo ['name'] uz obsahuje nazov bez cesty
$uploadname = stripslashes($uploadname);                        //odstrani lomitka
$uploadname = preg_replace('/[\s+\'+]/', '_', $uploadname);     //nahradi medzery a ine nepovolene symboly podtrznikmi
$uploadname = preg_replace('/_+/', '_', $uploadname);           //nahradi viacero podtrznikov jednym
$uploadname = mb_strtolower($uploadname, "UTF-8");
$notallowed = array("ľ","š","č","ť","ž","ý","á","í","é","ú","ä","ó","ô","ň","ĺ","ŕ","ř");  //nahradi nepovolene znaky
$allowed = array("l","s","c","t","z","y","a","i","e","u","a","o","o","n","l","r","r");
$uploadname = str_replace($notallowed, $allowed, $uploadname);
$uploadname = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $uploadname);
$uploadname = $_SESSION['user']."_".$velkost->sirka."x".$velkost->vyska."_".$uploadname;

if($userfile['size']==0)
    $message .= "Prázdny súbor!<br>";
if($userfile['size']>20000) //limit 20KB
    $message .= "Príliš veľký súbor! (max. 20KB)<br>";
if(strlen($uploadname)>50)
    $message .= "Príliš dlhý názov súboru! (max. ".(50 - strlen($_SESSION['user']) - 2 - strlen($velkost->sirka."x".$velkost->vyska))." znakov)<br>";
//file['type'] vyhodnocuje len na zaklade pripony (tj hocakemu suboru dam priponu jpg a upne ho)
//a nie na zaklade hlavicky suboru ako getimagesize
$info = getimagesize($userfile['tmp_name']);
if ($info[2] != 1 && $info[2] != 2 && $info[2] != 3) //1=gif,2=jpg,3=png
    $message .= "Nepodporovaný súbor! (iba .gif, .jpg, .png)<br>";
if ($info[0] != $velkost->sirka || $info[1] != $velkost->vyska) //[0]-sirka,[1]-vyska
    $message .= "Nesprávne rozmery banneru! ($velkost->nazov je $velkost->sirka x $velkost->vyska)<br>";
if($db->bannerExists($user->id, $velkost->id))
    $message .= "Už máte banner typu $velkost->nazov!<br>";

if($message=="") //banner je OK
{
    if (move_uploaded_file($userfile['tmp_name'], '../upload/'.$uploadname))
    {
        $banner = new Banner(null, $user->id, $velkost, $uploadname);
        if($db->saveBanner($banner, $kategorie))
            $message = "Banner bol úspešne uložený.";
        else
            $message = "Nepodarilo sa uložiť banner.";
    }
    else
        $message = "Zlyhalo uploadovanie súboru!";
}

$_SESSION['flash'] = $message;
$referer = $_SERVER['HTTP_REFERER'];
header("Location: $referer");
?>