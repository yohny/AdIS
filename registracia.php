<?php 
$nadpis = "Registrácia";
require 'base/left.php';
if(isset($_SESSION['registrator']))
{
    $user = $_SESSION['registrator'];
    session_unregister('registrator');
}
?>

<form name="reg_form" action="actions/registruj.php" method="POST">
  <center>
  <table cellspacing="5" style="text-align:left">
    <tr title="Login slúžiaci na prihlásenie do systému. Musí byť jedinečný.">
      <td><label for="login">Login:</label></td>
      <td><input type="text" name="user[login]" maxlength="10" onKeyUp="overLogin(this.value);" <?php if(isset($user)) echo 'value="'.$user['login'].'"'; ?>><br>
      <span id="loginStatus"></span></td>
    </tr>
    <tr title="Heslo pre prístup do systému.">
      <td><label for="pass">Heslo:</label></td>
      <td><input type="password" name="user[heslo]" maxlength="10" <?php if(isset($user)) echo 'value="'.$user['heslo'].'"'; ?>></td>
    </tr>
    <tr title="Potvrdenie hesla do systému.">
      <td><label for="pass2">Heslo znova:</label></td>
      <td><input type="password" name="user[heslo2]" maxlength="10" <?php if(isset($user)) echo 'value="'.$user['heslo2'].'"'; ?>></td>
    </tr>
    <tr title="Vaša webová adresa vrátane protokolu (http://) napr.: 'http://www.vasafirma.sk'.">
      <td><label for="web">WWW adresa:</label></td>
      <td><input type="text" name="user[web]" maxlength="100" <?php if(isset($user)) echo 'value="'.$user['web'].'"'; ?>></td>
    </tr>
    <tr title="Zvoľte 'Inzerent' ak chcete pridať svoje reklamy, ktoré bolú zobrazované inými používateľmi na ich stránkach.">
      <td><label for="inzer">Inzerent</label></td>
      <td><input type="radio" name="user[skupina]" value="inzer" checked="checked"></td>
    </tr>
    <tr title="Zvoľte 'Zobrazovateľ' ak chcete zobrazovať reklamy íných používateľov na svojich stránkach.">
      <td><label for="zobra">Zobrazovateľ</label></td>
      <td><input type="radio" name="user[skupina]" value="zobra" <?php if(isset($user) && $user['skupina']=='zobra') echo 'checked="checkeded"'; ?>></td>
    </tr>
    <tr>
      <td colspan="2"><input type="button" value="Odošli" onClick="spracuj_reg()"></td>
    </tr>
    <tr>
      <td colspan="2"><div id="reg_errbox" class="errbox"></div></td>
    </tr>
  </table>
  </center>
</form>

<hr>
</div>

</div>
</body>
</html>
