<?php
$nadpis = "Upload banneru";
require '../base/left.php';
require '../base/secure.php';

if(!isset ($_POST['meno']) || !isset ($_POST['velkost']) || !isset ($_POST['kategorie']))
    exit("Nekompletne data");

$meno = $_POST['meno'];
$velkost = $_POST['velkost'];
$kategorie = $_POST['kategorie'];
$message = "";

require '../base/datab_con.php';
/* @var $conn mysqli */

$query = "SELECT * FROM velkosti WHERE id=$velkost";    //ziskanie rozmerov
/* @var $result mysqli_result */
$result = $conn->query($query);
if($conn->affected_rows!=1)
    exit ("Nepodarilo sa ziskat velkost");

$row = $result->fetch_object();
$sirka = $row->sirka;
$vyska = $row->vyska;
$typ = $row->nazov;

if(strlen($meno)>50)
    $message .= "<span class=\"r\">Príliš dlhý názov! (max. 50 znakov)</span><br>";
//dalsie testy
//...

if($message=="")
{
    $user = $_SESSION['user'];

    $query = "SELECT id FROM reklamy WHERE user=(SELECT id FROM users WHERE login='$user') AND velkost=$velkost";
    $result = $conn->query($query);

    if($conn->affected_rows==1)   //ak uz ma taky typ reklamy
    {
        $row = $result->fetch_object();
        //POZOR tymto sa 'prepise' aj historia klikov (namiesto doterajsej reklamy tam bude ze bolo klikane na tuto)
        $query = "UPDATE reklamy SET meno='$meno' WHERE id=$row->id";
        $conn->query($query);

        $query = "DELETE FROM kategoria_reklama WHERE reklama=$row->id";  //zmaze stare vazby na kategorie
        $conn->query($query);
    }
    else //ak este nema reklamu danych rozmerov
    {
        $query = "INSERT INTO reklamy VALUES(NULL, (SELECT id FROM users WHERE login='$user'), $velkost, '$meno')";
        $conn->query($query);;
    }

    //naviazanie kategorie
    $query = "SELECT id FROM reklamy WHERE user=(SELECT id FROM users WHERE login='$user') AND velkost=$velkost";   //ziskanie id reklamy
    $result = $conn->query($query);
    $row = $result->fetch_object();
    $conn->autocommit(FALSE);
    foreach ($kategorie as $kat)
        $conn->query("INSERT INTO kategoria_reklama VALUES (NULL, $kat, $row->id)");
    $conn->commit();

    $conn->close();

    $message = "<h4>Reklama bola úspešne pridaná.</h4>";
}
$message .= "<br>Späť na pridanie - <a href=\"reklamy.php\">TU</a>";
?>

<?php echo $message; ?>
<hr>
</div>

</div>
</body>
</html>
