<?php 
$nadpis = "Registrácia";
require 'base/left.php';
if(isset($_SESSION['registrator']))
{
    $tmpuser = $_SESSION['registrator'];
    session_unregister('registrator');
}
?>

<form name="reg_form" action="actions/registruj.php" method="POST" autocomplete="off">
  <center>
  <table>
    <tr title="Login slúžiaci na prihlásenie do systému. Musí byť jedinečný.">
      <td><label for="user_login">Login:</label></td>
      <td><input type="text" name="user[login]" id="user_login" maxlength="10" onBlur="overLogin(this.value);" <?php if(isset($tmpuser)) echo 'value="'.$tmpuser['login'].'"'; ?>>
    </tr>
    <tr>
        <td></td>
        <td style="font-weight: bold;width:200px;">
            <span id="loginStatus">Zadajte unikátny login.</span>
        </td>
    </tr>
    <tr title="Heslo pre prístup do systému.">
      <td><label for="user_heslo">Heslo:</label></td>
      <td><input type="password" name="user[heslo]" id="user_heslo" maxlength="10" <?php if(isset($tmpuser)) echo 'value="'.$tmpuser['heslo'].'"'; ?>></td>
    </tr>
    <tr title="Potvrdenie hesla do systému.">
      <td><label for="user_heslo2">Heslo znova:</label></td>
      <td><input type="password" name="user[heslo2]" id="user_heslo2" maxlength="10" <?php if(isset($tmpuser)) echo 'value="'.$tmpuser['heslo2'].'"'; ?>></td>
    </tr>
    <tr title="Vaša webová adresa vrátane protokolu (http://) napr.: 'http://www.vasafirma.sk'.">
      <td><label for="user_web">WWW adresa:</label></td>
      <td><input type="text" name="user[web]" id="user_web" maxlength="100" <?php if(isset($tmpuser)) echo 'value="'.$tmpuser['web'].'"'; ?>></td>
    </tr>
    <tr title="Zvoľte 'Inzerent' ak chcete pridať svoje reklamy, ktoré bolú zobrazované inými používateľmi na ich stránkach.">
      <td><label for="user_inzer">Inzerent</label></td>
      <td><input type="radio" name="user[skupina]" id="user_inzer" value="inzer" checked="checked"></td>
    </tr>
    <tr title="Zvoľte 'Zobrazovateľ' ak chcete zobrazovať reklamy íných používateľov na svojich stránkach.">
      <td><label for="user_zobra">Zobrazovateľ</label></td>
      <td><input type="radio" name="user[skupina]" id="user_zobra" value="zobra" <?php if(isset($tmpuser) && $tmpuser['skupina']=='zobra') echo 'checked="checkeded"'; ?>></td>
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
<!-- //TODO remove CENTER tags - causing alignment problems at least in IE-->
<hr>
</div>

</div>
</body>
</html>
