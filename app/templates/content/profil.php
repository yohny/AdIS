<?php
/**
 * obsah pre "profil"
 *
 * @version    1.0
 * @package    AdIS
 * @subpackage templates
 * @author     Ján Neščivera <jan.nescivera@gmail.com>
 */


Context::getInstance()->getResponse()->setHeading('profil');
?>
<table cellspacing="5" style="margin: 0 auto; width: 300px; border-spacing: 5px;">
        <tr>
            <td width="80">Registrácia</td><td><span class="g"><?php echo Context::getInstance()->getUser()->getRegistrationTime()->format('d.m.Y H:i:s'); ?></span></td>
        </tr>
        <tr>
            <td width="80">Posledné prihlásenie</td><td><span class="g"><?php echo Context::getInstance()->getUser()->getLastLoginTime()->format('d.m.Y H:i:s'); ?></span></td>
        </tr>
</table>

<form name="chpas_form" action="">
    <table>
        <tr>
            <td width="80">Heslo:</td><td><span class="g">**********</span></td><td width="30"><a href="#" onclick="show('pas',this);return false;">zmeň</a></td>
        </tr>
        <tr id="pas" style="display:none;">
            <td colspan="3">
                <table style="width: 100%;">
                    <tr>
                        <td>Staré heslo:</td><td><input type="password" name="old" maxlength="10" /></td>
                    </tr>
                    <tr>
                        <td>Nové heslo:</td><td><input type="password" name="new1" maxlength="10" /></td>
                    </tr>
                    <tr>
                        <td>Nové heslo:</td><td><input type="password" name="new2" maxlength="10" /></td>
                    </tr>
                    <tr>
                        <td></td><td><input type="button" value="OK" onclick="spracuj_chpas()" /></td>
                    </tr>
                    <tr>
                        <td colspan="2"><div id="chpas_errbox" class="errbox"></div><div id="chpas_okbox" class="okbox"></div></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <input type="hidden" name="csrf_token" value="<?php echo Context::getInstance()->getCsrfToken(); ?>" />
</form>

<?php if (Context::getInstance()->getUser()->kategoria != User::ROLE_ADMIN): ?>
<form name="chweb_form" action="">
    <table cellspacing="5">
        <tr>
            <td width="80">WWW adresa:</td><td><span class="g" id="webTd"><?php echo "http://".Context::getInstance()->getUser()->getWeb(); ?></span></td><td width="30"><a href="#" onclick="show('web',this);return false;">zmeň</a></td>
        </tr>
        <tr id="web" style="display:none;">
            <td colspan="3">
                <table style="width: 100%;">
                    <tr>
                        <td>Nová adresa:</td>
                        <td>
                            <table style="width: 100%;border-collapse: collapse;"><tr>
                            <td style="width: 32px;"><input type="text" value="http://" readonly="readonly" style="border-right-style: none;" onclick="this.form['web'].focus()" /></td>
                            <td><input type="text" name="web" maxlength="30" style="border-left-style: none;" /></td>
                            </tr></table>
                        </td>
                    </tr>
                    <tr>
                        <td></td><td><input type="button" value="OK" onclick="spracuj_chweb()" /></td>
                    </tr>
                    <tr>
                        <td colspan="2"><div id="chweb_errbox" class="errbox"></div><div id="chweb_okbox" class="okbox"></div></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <input type="hidden" name="csrf_token" value="<?php echo Context::getInstance()->getCsrfToken(); ?>" />
</form>
<?php endif; ?>
