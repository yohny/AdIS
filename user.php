<?php
if( isset($_SESSION['user']) && isset($_SESSION['group']) )
{
?>
<br>
<table style="text-align:center;">
<tr>
<td width="60" valign="top"><span class="g" style="font-weight:bold;"><?php echo $_SESSION['user']; ?></span></td>
<td><form name="out_form" method="POST" action="odhlas.php"><input type="submit" name="action" value="Odhlásiť"></form></td>
</tr>
</table>

<fieldset>
<legend>konto</legend>
<a href="profil.php">Profil</a>
<a href="statistika.php">Štatistika</a>
<?php
if($_SESSION['group']=="inzer")
  echo "<a href=\"bannery.php\">Bannery</a>";
elseif($_SESSION['group']=="zobra")
  echo "<a href=\"reklamy.php\">Reklamy</a>";
elseif($_SESSION['group']=="admin")
  echo "<a href=\"javascript:;\"><span class=\"r\">ADMIN</span></a>";
?>
</fieldset>
<?php
}
else
{
?>
<form name="log_form" action="prihlas.php" method="POST">
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
