<?php if (Context::getInstance()->getUser()): ?>
    <fieldset>
        <legend>Správa</legend>
        <a href="/profil">Profil</a>
    <?php
    if (Context::getInstance()->getUser()->kategoria == "inzer")
        echo "
            <a href=\"/bannery\">Bannery</a>
            <a href=\"/statistika\">Štatistika</a>";
    elseif (Context::getInstance()->getUser()->kategoria == "zobra")
        echo "
            <a href=\"/reklamy\">Reklamy</a>
            <a href=\"/statistika\">Štatistika</a>";
    elseif (Context::getInstance()->getUser()->kategoria == "admin")
        echo "
            <a href=\"/statistika_admin\">Štatistika</a>
            <a href=\"javascript: void(0);\"><span class=\"r\">ADMIN</span></a>";
    ?>
</fieldset>
<h3>používateľ</h3>
<span style="font-variant: small-caps;font-weight: bolder;"><?php echo $_SESSION['user']; ?></span>
<form style="display: inline-block" method="POST" action="/action/odhlas">
    <a style="margin-left:10px;" href="#" onclick="this.parentNode.submit();return false;">(Odlásiť)</a>
    <input type="hidden" name="action" value="logout">
    <input type="hidden" name="csrf_token" value="<?php echo Context::getInstance()->getCsrfToken(); ?>">
</form>
<?php else: ?>
        <h3>používateľ</h3>
        <form name="log_form" action="/action/prihlas" method="POST">
            <table>
                <tr><td>Login<br><input type="text" name="login" maxlength="10"></td></tr>
                <tr><td>Heslo<br><input type="password" name="heslo" maxlength="10"></td></tr>
                <tr><td><input type="button" value="Prihlásiť" onClick="spracuj_log()"><a style="margin-left:10px;" href="/registracia">Registruj</a></td></tr>
                <tr><td><div id="log_errbox" class="errbox"></div></td></tr>
            </table>
            <input type="hidden" name="csrf_token" value="<?php echo Context::getInstance()->getCsrfToken(); ?>">
        </form>
<?php endif; ?>
