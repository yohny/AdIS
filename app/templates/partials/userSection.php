<?php
/**
 * vizualny komponent zapezpecujuci zobrazenie prihlasovacieho formulara alebo uzivatelskeho menu
 *
 * ak nie je ziadny prihlaseny pouzivatel, tak sa zobrazuje prihlasovaci formular,
 * ak je prihlaseny tak sa zobrazuje prislusne pouzivatelske menu v zavislosti od kategorie
 *
 * @version    1.0
 * @package    AdIS
 * @subpackage templates
 * @author     Ján Neščivera <jan.nescivera@gmail.com>
 *
 */

if (Context::getInstance()->getUser()): ?>
<fieldset>
    <legend>Správa</legend>
    <a href="/profil">Profil</a>
<?php if (Context::getInstance()->getUser()->kategoria == User::ROLE_INZER): ?>
    <a href="/bannery">Bannery</a>
    <a href="/statistika">Štatistika</a>
<?php elseif (Context::getInstance()->getUser()->kategoria == User::ROLE_ZOBRA): ?>
    <a href="/reklamy">Reklamy</a>
    <a href="/statistika">Štatistika</a>
<?php elseif (Context::getInstance()->getUser()->kategoria == User::ROLE_ADMIN): ?>
    <a href="/statistika_admin">Štatistika</a>
    <a href="#"><span class="r">ADMIN</span></a>
<?php endif; ?>
</fieldset>
<h3>používateľ</h3>
<span style="font-variant: small-caps;font-weight: bolder;"><?php echo $_SESSION['user']; ?></span>
<form style="display: inline-block" method="post" action="/action/odhlas">
    <a style="margin-left:10px;" href="#" onclick="this.parentNode.submit();return false;">(Odhlásiť)</a>
    <input type="hidden" name="action" value="logout" />
    <input type="hidden" name="csrf_token" value="<?php echo Context::getInstance()->getCsrfToken(); ?>" />
</form>
<?php else: ?>
<h3>používateľ</h3>
<form name="log_form" action="/action/prihlas" method="post">
    <table>
        <tr><td>Login<br/><input type="text" name="login" maxlength="10" /></td></tr>
        <tr><td>Heslo<br/><input type="password" name="heslo" maxlength="10" /></td></tr>
        <tr><td><input type="button" value="Prihlásiť" onclick="spracuj_log()" /><a style="margin-left:10px;" href="/registracia">Registruj</a></td></tr>
        <tr><td><div id="log_errbox" class="errbox"></div></td></tr>
    </table>
    <input type="hidden" name="csrf_token" value="<?php echo Context::getInstance()->getCsrfToken(); ?>" />
</form>
<?php endif; ?>
