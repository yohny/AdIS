<?php
Context::getInstance()->getResponse()->setHeading('profil');

try
{
   $web = Context::getInstance()->getUser()->getWeb();
}
catch (Exception $ex)
{
    Context::getInstance()->getResponse()->setFlash($ex->getMessage()) ;
    return;
}
?>
<form name="chpas_form" action="">
    <table cellspacing="5" style="text-align:left;width:300px;">
        <tr>
            <td width="80">Heslo:</td><td width="110"><span class="g">**********</span></td><td width="30"><a onclick="show('pas',this)">zmeň</a></td>
        </tr>
        <tr id="pas" style="display:none;">
            <td colspan="3">
                <table>
                    <tr>
                        <td>Staré heslo:</td><td><input type="password" name="old" maxlength="10"></td>
                    </tr>
                    <tr>
                        <td>Nové heslo:</td><td><input type="password" name="new1" maxlength="10"></td>
                    </tr>
                    <tr>
                        <td>Nové heslo:</td><td><input type="password" name="new2" maxlength="10"></td>
                    </tr>
                    <tr>
                        <td></td><td><input type="button" value="OK" onClick="spracuj_chpas()"></td>
                    </tr>
                    <tr>
                        <td colspan="2"><div id="chpas_errbox" class="errbox"></div><div id="chpas_okbox" class="okbox"></div></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</form>

<?php if (Context::getInstance()->getUser()->kategoria != 'admin'): ?>
<form name="chweb_form" action="">
    <table cellspacing="5" style="text-align:left;width:300px;">
        <tr>
            <td width="80">WWW adresa:</td><td width="110"><span class="g" id="webTd"><?php echo $web; ?></span></td><td width="30"><a onclick="show('web',this)">zmeň</a></td>
        </tr>
        <tr id="web" style="display:none;">
            <td colspan="3">
                <table>
                    <tr>
                        <td>Nová adresa:</td>
                        <td>
                            <table style="width: 100%;border-collapse: collapse;"><tr>
                            <td style="width: 40px;"><input type="text" value="http://" readonly style="border-right-style: none;"></td>
                            <td><input type="text" name="web" maxlength="30" style="border-left-style: none;"></td>
                            </tr></table>
                        </td>
                    </tr>
                    <tr>
                        <td></td><td><input type="button" value="OK" onClick="spracuj_chweb()"></td>
                    </tr>
                    <tr>
                        <td colspan="2"><div id="chweb_errbox" class="errbox"></div><div id="chweb_okbox" class="okbox"></div></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</form>
<?php endif; ?>
