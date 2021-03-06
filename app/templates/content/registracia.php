<?php
/**
 * obsah pre "registracia"
 *
 * @version    1.0
 * @package    AdIS
 * @subpackage templates
 * @author     Ján Neščivera <jan.nescivera@gmail.com>
 */


Context::getInstance()->getResponse()->setHeading('registrácia');

if(Context::getInstance()->getUser())
    throw new Exception("Už ste registrovaný!");

if (isset($_SESSION['registrator']))
{
    $tmpuser = $_SESSION['registrator'];
    unset($_SESSION['registrator']);
}
?>
<form name="reg_form" action="/action/registruj" method="post">
    <table>
        <tr title="Login slúžiaci na prihlásenie sa do systému. Musí byť jedinečný.">
            <td>Login:</td>
            <td><input type="text" name="user[login]" maxlength="10" onblur="overLogin(this.value);" <?php if (isset($tmpuser)) echo 'value="' . $tmpuser['login'] . '"'; ?> /></td>
        </tr>
        <tr>
            <td></td>
            <td style="font-weight: bold;width:200px;">
                <span id="loginStatus">Zadajte unikátny login.</span>
            </td>
        </tr>
        <tr title="Heslo pre prístup do systému.">
            <td>Heslo:</td>
            <td><input type="password" name="user[heslo]" maxlength="10" <?php if (isset($tmpuser)) echo 'value="' . $tmpuser['heslo'] . '"'; ?> /></td>
        </tr>
        <tr title="Potvrdenie hesla do systému.">
            <td>Heslo znova:</td>
            <td><input type="password" name="user[heslo2]" maxlength="10" <?php if (isset($tmpuser)) echo 'value="' . $tmpuser['heslo2'] . '"'; ?> /></td>
        </tr>
        <tr title="Vaša webová adresa napr.: 'http://www.priklad.sk'.">
            <td>WWW adresa:</td>
            <td>
                <table style="width: 100%;border-collapse: collapse;"><tr>
                        <td style="width: 32px;"><input type="text" value="http://" readonly="readonly" style="border-right-style: none;" onclick="this.form['user[web]'].focus()" /></td>
                        <td><input type="text" name="user[web]" maxlength="30" style="border-left-style: none;" <?php if (isset($tmpuser)) echo "value=\"{$tmpuser['web']}\""; ?> /></td>
                    </tr></table>
            </td>
        </tr>
        <tr>
            <td>Typ:</td>
            <td>
                <table>
                    <tr title="Zvoľte 'Inzerent' ak chcete pridať svoje reklamy, ktoré bolú zobrazované inými používateľmi na ich stránkach.">
                        <td><label for="user_inzer">Inzerent</label></td>
                        <td><input type="radio" name="user[skupina]" id="user_inzer" value="inzer" checked="checked" /></td>
                    </tr>
                    <tr title="Zvoľte 'Zobrazovateľ' ak chcete zobrazovať reklamy íných používateľov na svojich stránkach.">
                        <td><label for="user_zobra">Zobrazovateľ</label></td>
                        <td><input type="radio" name="user[skupina]" id="user_zobra" value="zobra" <?php if (isset($tmpuser) && $tmpuser['skupina'] == 'zobra') echo 'checked="checkeded"'; ?> /></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr title="Captcha na overenie, či formulár vypĺňa človek. Kliknutím sa načíta nový obrázok.">
            <td>Captcha:</td>
            <td><img alt="captcha" src="/img/captcha" onclick="this.src='/img/captcha?rand='+Math.random();" /></td>
        </tr>
        <tr title="Sem prepíšte text z obrázku vyššie.">
            <td>Prepis:</td>
            <td><input type="text" name="user[captcha]" maxlength="6" /></td>
        </tr>
        <tr>
            <td></td>
            <td><input type="button" value="Odošli" onclick="spracuj_reg()" /></td>
        </tr>
        <tr>
            <td colspan="2"><div id="reg_errbox" class="errbox"></div></td>
        </tr>
    </table>
    <input type="hidden" name="csrf_token" value="<?php echo Context::getInstance()->getCsrfToken(); ?>" />
</form>