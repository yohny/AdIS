<?php 
session_start();

//if(!isset ($_POST['login']) || !isset ($_POST['heslo']))
//{
//    $message = "Neuplne data!";
//    exit ();
//}

$login = $_POST['login'];
$heslo = $_POST['heslo'];

require 'datab_con.php';

$query = "SELECT * FROM users WHERE login='$login' AND heslo=MD5('$heslo')";
$result = mysql_query($query);
mysql_close($conn);

if (mysql_numrows($result)==1) 
{
  $row = mysql_fetch_array($result);
  $_SESSION['user'] = $row['login'];
  $_SESSION['group'] = $row['kategoria'];
  $message = "Úspešne ste sa prihlásili!";
}
else
  $message = "Chyba pri prihlasovaní. / Neplatné prihlasovacie údaje!";

$_SESSION['flash'] = $message;

$referer = $_SERVER['HTTP_REFERER'];
header("Location: $referer");
?>