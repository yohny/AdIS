<?php
if(Context::getInstance()->getUser()->kategoria!='inzer')
{
    echo "Nepovolený prístup";
    return;
}
if (!isset($_FILES['userfile']) ||
    !isset($_POST['velkost']) ||
    !isset($_POST['kategorie']) ||
    !is_numeric($_POST['velkost']) ||
    !isset($_POST['csrf_token']) ||
    empty($_FILES['userfile']) ||
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
    $message = Banner::checkFile($_FILES['userfile'], $velkost);

//TODO bannerov aj viac jedneho typu? ma zmysel? - unikatne filename potom pomocou md5(timestamp)
//!osetrit vyber banneru do reklamy (eliminovat viacero kandidatov od jedneho usera)
if (!$message) //banner je OK
{
    $uploadname = Banner::createFilename($_FILES['userfile']['name'], $velkost);
    if (move_uploaded_file($_FILES['userfile']['tmp_name'], 'upload/' . $uploadname))
    {
        $banner = new Banner(null, Context::getInstance()->getUser()->id, $velkost, $uploadname);
        if ($banner->save($_POST['kategorie']))
            $message = "Banner bol úspešne uložený.";
        else
        {
            $message = "Nepodarilo sa uložiť banner.";
            unlink('upload/' . $uploadname);
        }
    }
    else
        $message = "Zlyhalo uploadovanie súboru!";
}

Context::getInstance()->getResponse()->setFlash($message);
Context::getInstance()->getResponse()->redirect = '/bannery';
?>