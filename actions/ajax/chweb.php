<?php
header('Content-type: application/json; charset=UTF-8');

if (!isset($_POST['web']))
{
    echo 'Nekompletne data';
    return;
}

try
{
    if (!filter_var($_POST['web'], FILTER_VALIDATE_URL))
        $resp = array('success' => false, 'message' => 'Neplatná webová adresa!');
    elseif (Context::getInstance()->getUser()->setWeb($_POST['web'], $db))
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