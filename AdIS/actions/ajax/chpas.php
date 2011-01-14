<?php
header('Content-type: application/json; charset=UTF-8');

if (!isset($_POST['old']) || !isset($_POST['new']))
{
    echo 'Nekompletne data';
    return;
}

try
{
    if (Context::getInstance()->getUser()->setPassword($_POST['old'], $_POST['new']))
        $resp = array('success' => true, 'message' => 'Heslo zmenené.');
    else
        $resp = array('success' => false, 'message' => 'Neporarilo sa zmeniť heslo!(nesprávne?)');
}
catch (Exception $ex)
{
    $resp = array('success' => false, 'message' => $ex->getMessage());
}
echo json_encode($resp);
?>

