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
<span style="font-variant: small-caps;font-weight: bolder;"><?= $_SESSION['user']; ?></span>
<form style="display: inline-block" method="post" action="/action/odhlas">
    <a style="margin-left:10px;" href="#" onclick="this.parentNode.submit();return false;">(Odhlásiť)</a>
    <input type="hidden" name="action" value="logout" />
    <input type="hidden" name="csrf_token" value="<?= Context::getInstance()->getCsrfToken(); ?>" />
</form>
<?php else: ?>
<h3>používateľ</h3>
<form name="log_form" action="/action/prihlas" method="post">
    <table>
        <tr>
			<td>
				<label>Login<br/>
					<input type="text" name="login" maxlength="10" />
				</label>
			</td>
		</tr>
        <tr>
			<td>
				<label>Heslo<br/>
					<input type="password" name="heslo" maxlength="10" />
				</label>
			</td>
		</tr>
		<tr>
			<td>
				<label><input style="vertical-align: bottom;" type="checkbox" name="neodhlasovat" value="neodhlasovat" /> Neodhlasovať</label>
			</td>
		</tr>
        <tr>
			<td>
				<input type="button" value="Prihlásiť" onclick="spracuj_log()" />
			</td>
		</tr>
        <tr>
			<td>
				<div id="log_errbox" class="errbox"></div>
			</td>
		</tr>
    </table>
    <input type="hidden" name="csrf_token" value="<?= Context::getInstance()->getCsrfToken(); ?>" />
</form>
<?php endif; ?>
