<?php
Context::getInstance()->getResponse()->setHeaderContentType('application/json');

if (!isset($_POST['web']) || !isset($_POST['csrf_token']))
{
    echo 'Nekompletne data';
    return;
}
if($_POST['csrf_token'] != Context::getInstance()->getCsrfToken())
{
    echo 'CSRF fail!';
    return;
}

try
{
    if (!User::validUrl($_POST['web']))
        $resp = array('success' => false, 'message' => 'Neplatná webová adresa!');
    elseif(!User::isWebUnique($_POST['web']))
        $resp = array('success' => false, 'message' => 'Tento web už je registrovaný!');
    elseif (Context::getInstance()->getUser()->setWeb($_POST['web']))
        $resp = array('success' => true, 'message' => 'WWW adresa zmenená.');
    else
        $resp = array('success' => false, 'message' => 'Nepodarilo sa zmeniť web!');
}
catch (Exception $ex)
{
    $resp = array('success' => false, 'message' => $ex->getMessage());
}
echo json_encode($resp);
?>
