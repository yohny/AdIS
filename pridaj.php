<?php
$nadpis = "Upload banneru";
require 'left.php';
require 'secure.php';

if(!isset ($_POST['meno']) || !isset ($_POST['velkost']) || !isset ($_POST['kategorie']))
{
    echo "Nekompletne data";
    exit();
}

$meno = $_POST['meno'];
$velkost = $_POST['velkost'];
$kategorie = $_POST['kategorie'];
$message = "";

require 'datab_con.php';

$query = "SELECT * FROM velkosti WHERE id=$velkost";    //ziskanie rozmerov
$result = mysql_query($query) or die('Zlyhalo query!');
if(mysql_num_rows($result)!=1)
{
    echo "Nepodarilo sa ziskat velkost";
    exit ();
}
$row = mysql_fetch_array($result);
$sirka = $row['sirka'];
$vyska = $row['vyska'];
$typ = $row['nazov'];

if(strlen($meno)>50)
    $message .= "<span class=\"r\">Príliš dlhý názov! (max. 50 znakov)</span><br>";
//dalsie testy
//...

if($message=="")
{
    $user = $_SESSION['user'];

    $query = "SELECT * FROM reklamy WHERE user=(SELECT id FROM users WHERE login='$user') AND velkost=$velkost";
    $result = mysql_query($query) or die('Zlyhalo query!');

    if(mysql_num_rows($result)==1)   //ak uz ma taky typ reklamy
    {
        $row = mysql_fetch_array($result);
        $query = "UPDATE reklamy SET meno='$meno' WHERE id={$row['id']}";
        mysql_query($query) or die('Zlyhalo query!');

        $query = "DELETE FROM kategoria_reklama WHERE reklama={$row['id']}";  //zmaze stare vazby na kategorie
        mysql_query($query) or die('Zlyhalo query!');
    }
    else //ak este nema reklamu danych rozmerov
    {
        $query = "INSERT INTO reklamy VALUES(NULL, (SELECT id FROM users WHERE login='$user'), $velkost, '$meno')";
        mysql_query($query) or die('Zlyhalo query!');
    }

    //naviazanie kategorie
    $query = "SELECT id FROM reklamy WHERE user=(SELECT id FROM users WHERE login='$user') AND velkost=$velkost";   //ziskanie id reklamy
    $result = mysql_query($query) or die('Zlyhalo query!');
    $row = mysql_fetch_array($result);
    $query = "INSERT INTO kategoria_reklama VALUES";
    foreach ($kategorie as $kat)
    {
        $query .="(NULL, $kat, {$row['id']}),";
    }
    $query = substr($query, 0, strlen($query)-1);
    $result = mysql_query($query) or die('Zlyhalo query!');

    mysql_close($conn);

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
