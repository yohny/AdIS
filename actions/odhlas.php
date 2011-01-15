<?php
if ($_POST['action'] == "logout")
{
    session_unset();
    //session_destroy(); -nemoze potom nastavit flash
    $message = "Boli ste odhlásený.";
}
else
    $message = "Chybná požiadavka";

Context::getInstance()->getResponse()->setFlash($message);
header("Location: /");
?>