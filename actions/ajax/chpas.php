<?php
Context::getInstance()->getResponse()->setHeaderContentType('application/json');

if (!isset($_POST['old']) || !isset($_POST['new']) || !isset($_POST['csrf_token']))
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
    if (Context::getInstance()->getUser()->setPassword($_POST['old'], $_POST['new']))
        $resp = array('success' => true, 'message' => 'Heslo zmenené.');
    else
        $resp = array('success' => false, 'message' => 'Neporarilo sa zmeniť heslo! (nesprávne?)');
}
catch (Exception $ex)
{
    $resp = array('success' => false, 'message' => $ex->getMessage());
}
echo json_encode($resp);
?>

