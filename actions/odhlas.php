<?php
if ($_POST['action'] == "logout")
{
    session_unset();
    $message = "Boli ste odhlásený.";
}
else
    $message = "Chybná požiadavka";

Context::getInstance()->setFlash($message);

header("Location: /");
?>