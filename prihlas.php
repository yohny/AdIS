<?php 
session_start();

$login = $_POST['login'];
$heslo = $_POST['heslo'];

require 'datab_con.php';
/* @var $conn mysqli */

$query = "SELECT * FROM users WHERE login='$login' AND heslo=MD5('$heslo')";
/* @var $result mysqli_result */
$result = $conn->query($query);

if ($conn->affected_rows == 1)
{
  $row = $result->fetch_object();
  $_SESSION['user'] = $row->login;
  $_SESSION['group'] = $row->kategoria;
  $message = "Úspešne ste sa prihlásili!";
}
else
  $message = "Chyba pri prihlasovaní. / Neplatné prihlasovacie údaje!";

$conn->close();

$_SESSION['flash'] = $message;

$referer = $_SERVER['HTTP_REFERER'];
header("Location: $referer");
?>