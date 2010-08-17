<?php
$nadpis = "Upload banneru";
require '../base/left.php';
require '../base/secure.php';

if(!isset ($_FILES['userfile']) || !isset ($_POST['velkost']) || !isset ($_POST['kategorie']))
    exit("Nekompletne data");

$uploaddir = '../upload/';   // Relative path from this file
$userfile = $_FILES['userfile'];
$velkost = $_POST['velkost'];
$kategorie = $_POST['kategorie'];
$message = "";

require '../base/datab_con.php';
/* @var $conn mysqli */

$query = "SELECT * FROM velkosti WHERE id=$velkost";     //ziskanie rozmerov

/* @var $result mysqli_result */
$result = $conn->query($query);
if($conn->affected_rows!=1)
    exit ("Nepodarilo sa ziskat velkost");

$row = $result->fetch_object();
$sirka = $row->sirka;
$vyska = $row->vyska;
$typ = $row->nazov;

//vytvorime meno bez cesty pre upload suboru
$uploadname = basename($userfile['name']);       //len meno bez cesty -od verzie x.x uz netreba basename() lebo ['name'] uz obsahuje nazov bez cesty
$uploadname = stripslashes($uploadname);        //odstrani lomitka 
$uploadname = preg_replace('/[\s+\'+]/', '_', $uploadname);       //nahradi medzery a ine nepovolene symboly podtrznikmi
$uploadname = preg_replace('/_+/', '_', $uploadname);           //nahradi viacero podtrznikov jednym
$uploadname = mb_strtolower($uploadname, "UTF-8");
$notallowed = array("ľ","š","č","ť","ž","ý","á","í","é","ú","ä","ó","ô","ň","ĺ","ŕ","ř");  //nahradi nepovolene znaky
$allowed = array("l","s","c","t","z","y","a","i","e","u","a","o","o","n","l","r","r");
$uploadname = str_replace($notallowed, $allowed, $uploadname);
$uploadname = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $uploadname);
$uploadname = $_SESSION['user']."_".$sirka."x".$vyska."_".$uploadname;  //spoji, dostaneme cele meno uploadnuteho suboru s cestou
$maxlength = 50 - strlen($_SESSION['user']) - 1 - strlen($sirka."x".$vyska); //max dlzka nazvu suboru (cely path moze mat max. 50 xnakov)

if($userfile['size']==0)
    $message .= "<span class=\"r\">Prázdny súbor!</span><br>";
if($userfile['size']>20000) //limit 20KB  -velkost je v B (bytoch)
    $message .= "<span class=\"r\">Príliš veľký súbor! (max. 20KB)</span><br>";
if($maxlength<0)
    $message .= "<span class=\"r\">Príliš dlhý názov súboru! (max. $maxlength znakov)</span><br>";
//niekedy berie format ako jpeg inokedy pjpeg, popr. x-png inokedy png, zalezi od browsera aku hlavicku posle
//if ($userfile['type'] != "image/gif" && $userfile['type'] != "image/pjpeg" && $userfile['type'] != "image/jpeg" && $userfile['type'] != "image/x-png" && $userfile['type'] != "image/png")
//  $message .= "<span class=\"r\">Nepodporovaný súbor! (iba .gif, .jpg, .png)</span><br>";
//file['type'] vyhodnocuje len na zaklade pripony (tj hocaakemu suboru dam priponu jpg a upne ho) a nie na zaklade hlavicky suboru ako getimagesize
$info = getimagesize($userfile['tmp_name']);
if ($info[2] != 1 && $info[2] != 2 && $info[2] != 3) //1=gif,2=jpg,3=png
    $message .= "<span class=\"r\">Nepodporovaný súbor! (iba .gif, .jpg, .png)</span><br>";
if ($info[0] != $sirka || $info[1] != $vyska) //[0]-sirka,[1]-vyska
    $message .= "<span class=\"r\">Nesprávne rozmery banneru! ($typ je $sirka x $vyska)</span><br>";
 
if($message=="") //subor je OK
{
    $user = $_SESSION['user'];

    if (move_uploaded_file($userfile['tmp_name'], $uploaddir.$uploadname))
    {
        $query = "SELECT id,path FROM bannery WHERE user=(SELECT id FROM users WHERE login='$user') AND velkost=$velkost";
        $result = $conn->query($query);


        if($conn->affected_rows==1)   //ak uz ma taky typ banneru
        {
            $row = $result->fetch_object();
            //POZOR tymto sa 'prepise' aj historia klikov (namiesto doterajsieho bannera tam bude ze bolo klikane na tento)
            $query = "UPDATE bannery SET path='$uploadname' WHERE id=$row->id";
            $conn->query($query);

            $query = "DELETE FROM kategoria_banner WHERE banner=$row->id";  //zmaze stare vazby na kategorie
            $conn->query($query);

            if($row->path!=$uploadname) //ak uploadol novy banner s inym menom
                unlink($uploaddir.$row->path);  //tak ten stary zmazem (ak by upoval s tym istym menom tak netreba mazat lebo ho prepisalo)
        }
        else //ak este nema banner danych rozmerov
        {
            $query = "INSERT INTO bannery VALUES(NULL, (SELECT id FROM users WHERE login='$user'), $velkost, '$uploadname');";
            $conn->query($query);
        }

        //naviazanie kategorie
        $query = "SELECT id FROM bannery WHERE user=(SELECT id FROM users WHERE login='$user') AND velkost=$velkost;";     //ziskanie id bannera
        $result = $conn->query($query);
        $row = $result->fetch_object();
        $conn->autocommit(FALSE);
        foreach ($kategorie as $kat)
            $conn->query("INSERT INTO kategoria_banner VALUES (NULL, $kat, $row->id)");
        $conn->commit();

        $conn->close();

        $message = "Súbor bol úspešne uploadnutý.";
    }
    else
        $message = "Zlyhalo uploadovanie súboru!";
}

$_SESSION['flash'] = $message;
$referer = $_SERVER['HTTP_REFERER'];
header("Location: $referer");
?>