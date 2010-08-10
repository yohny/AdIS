<?php 
$nadpis = "Odhlásenie";
session_start();

$message = "";
if($_POST['action']=="Odhlásiť")
{
  session_destroy();
  $message = "<h4>Boli ste odhlásený.</h4>";
}
else
  $message = "<span class=\"r\">Chybná požiadavka.</span>";

require 'left.php';
?>

<?php echo $message; ?>
<hr>
</div>

</div>
</body>
</html>
