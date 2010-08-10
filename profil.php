<?php
$nadpis = "Profil";
require 'left.php';
require 'secure.php';

$user = $_SESSION['user'];
$group = $_SESSION['group'];

if($group != "admin")
{
  include "datab_con.php";
  
  $query = "SELECT web FROM users WHERE login='$user'";
  $result = mysql_query($query) or die('Zlyhalo query!');
  mysql_close($conn); 
  $row = mysql_fetch_array($result);
}
?>
<center>
<table cellspacing="5" style="text-align:left;">
  <tr>
    <td width="80">Heslo:</td><td width="110"><span class="g">**********</span></td><td width="30"><a href="javascript: show('pas',this)">zmeň</a></td>
  </tr>
  <tr id="pas" style="display:none;">
    <td colspan="3">
      <form name="chpas_form">
      <table>
      <tr><td>Staré heslo:</td><td><input type="password" name="old" maxlength="10"></td></tr>
      <tr><td>Nové heslo:</td><td><input type="password" name="new1" maxlength="10"></td></tr>
      <tr><td>Nové heslo:</td><td><input type="password" name="new2" maxlength="10"></td></tr>
      <tr><td colspan="2"><input type="button" value="OK" onClick="spracuj_chpas()"></td></tr>
      <tr><td colspan="2"><div id="chpas_errbox" class="errbox"></div><div id="chpas_okbox" class="okbox"></div></td></tr>
      </table>
      </form>
    </td>
  </tr>
<?php if($group != "admin"): ?>
  <tr>
    <td>WWW adresa:</td><td><span class="g"><?php echo $row['web']; ?></span></td><td><a href="javascript: show('web',this)">zmeň</a></td>
  </tr>
  <tr id="web" style="display:none;">
    <td colspan="3">
      <form name="chweb_form">
      <table>
      <tr><td>Nová adresa:</td><td><input type="text" name="web" maxlength="20"></td></tr>
      <tr><td colspan="2"><input type="button" value="OK" onClick="spracuj_chweb()"></td></tr>
      <tr><td colspan="2"><div id="chweb_errbox" class="errbox"></div><div id="chweb_okbox" class="okbox"></div></td></tr>
      </table>
      </form>
    </td>
  </tr>
<?php endif; ?>
</table>
</center>

<hr>
</div>

</div>
</body>
</html>
