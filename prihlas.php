<?php 
$nadpis = "Prihlásenie";
session_start();

if(!isset ($_POST['login']) || !isset ($_POST['heslo']))
{
    echo "Neuplne data!";
    exit ();
}
$login = $_POST['login'];
$heslo = $_POST['heslo'];
$message = "";

require 'datab_con.php';

$query = "SELECT * FROM users WHERE login='$login' AND heslo=MD5('$heslo')";
$result = mysql_query($query) or die('Zlyhalo query!');

if (mysql_numrows($result)==1) 
{
  $row = mysql_fetch_array($result);
  $_SESSION['user'] = $row['login'];
  $_SESSION['group'] = $row['kategoria'];
  $message = "<h4>Úspešne ste sa prihlásili!</h4>";
}
else
  $message = "<h4>Chyba pri prihlasovaní. / Neplatné prihlasovacie údaje!</h4>";

mysql_close($conn);

require 'left.php';
?>

<?php echo $message; ?>
<hr>
</div>

</div>
</body>
</html>
