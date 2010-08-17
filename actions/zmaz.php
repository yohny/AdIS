<?php
require '../base/left.php';
require '../base/secure.php';

$login = $_SESSION['user'];
$group = $_SESSION['group'];

require '../base/datab_con.php';
/* @var $conn mysqli */

if($group=='inzer')
{
    $query = "SELECT path FROM bannery WHERE user=(SELECT id FROM users WHERE login='$login') AND id={$_POST['zmaz']}";
    /* @var $result mysqli_result */
    $result = $conn->query($query);
    if($conn->affected_rows==1)
    {
        $bannername = $result->fetch_object()->path;
        unlink('../upload/'.$bannername);
        $conn->autocommit(FALSE);
        $conn->query("DELETE FROM bannery WHERE id={$_POST['zmaz']}");
        $conn->query("DELETE FROM kategoria_banner WHERE banner={$_POST['zmaz']}");
        $conn->commit();
        $message = 'Banner \''.$bannername.'\' zmazaný!';
    }
    else
        $message = 'Nemôžete zmazať tento banner!';
}
if($group=='zobra')
{
    $query = "SELECT meno FROM reklamy WHERE user=(SELECT id FROM users WHERE login='$login') AND id={$_POST['zmaz']}";
    /* @var $result mysqli_result */
    $result = $conn->query($query);
    if($result->num_rows==1)
    {
        $conn->autocommit(FALSE);
        $conn->query("DELETE FROM reklamy WHERE id={$_POST['zmaz']}");
        $conn->query("DELETE FROM kategoria_reklama WHERE reklama={$_POST['zmaz']}");
        $conn->commit();
        $message = '<span class="g">Reklama \''.$result->fetch_object()->meno.'\' zmazaná!</span>';
    }
    else
        $message = '<span class="r">Nemôžete zmazať túto reklamu!</span>';
}


$_SESSION['flash'] = $message;
$referer = $_SERVER['HTTP_REFERER'];
header("Location: $referer");
?>
