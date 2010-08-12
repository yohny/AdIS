<?php
$nadpis = "Registrácia";
require 'left.php';

if(!isset ($_POST['login']) || !isset ($_POST['heslo']) || !isset($_POST['skupina']) || !isset ($_POST['web']))
    exit("Nekompletne data");

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
/* @var $conn mysqli */

$query = "SELECT * FROM users WHERE login='$login'";
$conn->query($query);
if ($conn->affected_rows>0)
  $message .= "<span class=\"r\">Váš login NIE JE unikátny (už ho používa niekto iný). Zvoľte si iný login!</span><br>";

if($message == "")
{
  $query = "INSERT INTO users VALUES(NULL, '$login', MD5('$heslo'), '$web' , '$kategoria')";    //insert do tabulky userov
  /* @var $result mysqli_result */
  $result = $conn->query($query);

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
$conn->close();
?>

<?php echo $message; ?>
<hr>
</div>

</div>
</body>
</html>
