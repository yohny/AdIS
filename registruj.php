<?php
$nadpis = "Registrácia";
require 'left.php';

if(!isset ($_POST['login']) || !isset ($_POST['heslo']) || !isset($_POST['skupina']) || !isset ($_POST['web']))
{
    echo "Neuplne data!";
    exit();
}

$login = $_POST['login'];
$heslo = $_POST['heslo'];
$kategoria = $_POST['skupina'];  //na 5 znakov skratit
$kategoria = substr($kategoria, 0, 5);
$web = $_POST['web'];  //treba odstranit protokol  - netreba filter berie vratane protokolu
//$protocol = array("http://", "https://", "ftp://");
//$web = str_replace($protocol, "", $web);

$message = "";

if(!filter_var($web, FILTER_VALIDATE_URL))
    $message .= "<span class=\"r\"|>Neplatná webová adresa!</span><br>";

require 'datab_con.php';

$query = "SELECT * FROM users WHERE login='$login'";
$result = mysql_query($query) or die('Zlyhalo query!');
if (mysql_numrows($result)>0)
  $message .= "<span class=\"r\">Váš login NIE JE unikátny (už ho používa niekto iný). Zvoľte si iný login!</span><br>";

if($message == "")
{
  $query = "INSERT INTO users VALUES(NULL, '$login', MD5('$heslo'),'$web' , '$kategoria')";    //insert do tabulky userov
  $result = mysql_query($query) or die('Zlyhalo query!');

  $_SESSION['user'] = $login;
  $_SESSION['group'] = $kategoria;

  $message="<h4>Registrácia úspešná.</h4>";
  if($kategoria=="inzer")
    $message .= "Správa Vaších bannerov - <a href=\"bannery.php\">TU</a>";
  if($kategoria=="zobra")
    $message .= "Správa Vaších reklám - <a href=\"reklamy.php\">TU</a>";
}
else
    $message .= "<br>Späť na registráciu - <a href=\"registracia.php\">TU</a>";
mysql_close($conn);
?>

<?php echo $message; ?>
<hr>
</div>

</div>
</body>
</html>
