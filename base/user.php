<?php
if(isset($_SESSION['user']))
{
?>
Prihlásený: <span class="g" style="font-weight:bold;"><?php echo $_SESSION['user']; ?></span>
<fieldset>
<legend>konto</legend>
<a href="profil.php">Profil</a>
<?php
if($_SESSION['user']->kategoria=="inzer")
  echo "<a href=\"bannery.php\">Bannery</a>
      <a href=\"statistika.php\">Štatistika</a>";
elseif($_SESSION['user']->kategoria=="zobra")
  echo "<a href=\"reklamy.php\">Reklamy</a>
      <a href=\"statistika.php\">Štatistika</a>";
elseif($_SESSION['user']->kategoria=="admin")
  echo "<a href=\"statistika_admin.php\">Štatistika</a>
      <a href=\"javascript: void(0);\"><span class=\"r\">ADMIN</span></a>";
?>
</fieldset>
<form name="out_form" method="POST" action="actions/odhlas.php"><input type="submit" name="action" value="Odhlásiť"></form>
<?php
}
else
{
?>
<form name="log_form" action="actions/prihlas.php" method="POST">
  <table>
  <tr><td>Login<br><input type="text" name="login" maxlength="10"></td></tr>
  <tr><td>Heslo<br><input type="password" name="heslo" maxlength="10"></td></tr>
  <tr><td><input type="button" value="Prihlásiť" onClick="spracuj_log()"></td></tr>
  <tr><td><div id="log_errbox" class="errbox"></div></td></tr>
  </table>
</form>
<?php
}
?>
