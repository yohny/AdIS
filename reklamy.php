<?php
$nadpis = "Reklamy";
require 'base/layout.php';
require 'base/secure.php';

require_once 'base/Database.php';
try
{
    $db = new Database();
}
catch(Exception $ex)
{
    exit("<h4>{$ex->getMessage()}</h4>
        <hr>
        </div>
        </div>
        </body>
        </html>");
}

$reklamy = $db->getReklamyByUser($_SESSION['user']);   //ziskanie reklam pouzivatela
$velkosti = $db->getAllFromVelkosti();      //ziskanie typov bannerov
$kategorie = $db->getAllFromKategorie();    //ziskanie kategorii

if(count($reklamy)>0)
  {$i=0;?>
    <h4>Vaše reklamy:</h4>
    <table style="text-align:left;" class="data">
        <thead>
        <tr>
            <th>
                Typ
            </th>
            <th>
                Rozmery
            </th>
            <th>
                Kategórie
            </th>
            <th>
                Zobraziť kód
            </th>
            <th>
                Akcia
            </th>
        </tr>
        </thead>
        <tbody>
    <?php foreach($reklamy as $reklama): $i++; ?>
        <tr <?php if($i%2==0) echo "class=\"dark\""; ?>>
            <td>
                <?php echo $reklama->velkost->nazov;?>
            </td>
            <td>
                <?php echo $reklama->velkost->sirka." x ".$reklama->velkost->vyska; ?>
            </td>
            <td>
                <?php echo implode(', ', $reklama->getKategorie($db->conn)); ?>
            </td>
            <td>
                <a onclick="show2('tr<?php echo $reklama->id; ?>')"><?php echo $reklama; ?></a>
            </td>
            <td>
                <form method="POST" action="actions/zmaz.php">
                    <input type="hidden" name="zmaz" value="<?php echo $reklama->id;?>">
                    <input type="button" value="Zmaž" onclick="if(confirm('Naozaj odstrániť?')) this.parentNode.submit();">
                </form>
            </td>
        </tr>
        <tr <?php if($i%2==0) echo "class=\"dark\"";?> style="display:none;" id="tr<?php echo $reklama->id; ?>">
            <td colspan="5">
                <p>Nasledujúci HTML kód vložte do vašej stránky (na miesto kde chcete mať reklamu):</p>
                <pre>
&lt;script language="javascript" type="text/javascript" charset="utf-8"&gt;
var adisRekl = <?php echo $reklama->id; ?>;
var adisRand = Math.random();
document.write(unescape("%3Cscript type=\"text/javascript\" language=\"javascript\" charset=\"utf-8\" src=\"http://<?php echo $_SERVER["HTTP_HOST"]; ?>/distrib/script.php?rekl="+adisRekl+"&amp;rand="+adisRand+"\"%3E%3C/script%3E"));
&lt;/script&gt;
                </pre>
            </td>
        </tr>
    <?php endforeach; ?>
        </tbody>
    </table>
  <?php
  }
  else
    echo "<h4>Nemáte ešte žiadnu reklamu.</h4>";
  ?>
<hr>
<h4>Pridanie novej reklamy:</h4>
<form name="add_form" action="actions/pridajReklamu.php" method="POST" enctype="multipart/form-data">
<table cellspacing="5" style="text-align:left;">
    <tr>
        <td title="Zvoľte rozmerový typ reklamnej jednotky.">
            Typ reklamy:
        </td>
        <td>
            <select name="velkost">
                <?php foreach($velkosti as $velkost): ?>
                <option value="<?php echo $velkost->id; ?>"><?php echo $velkost->sirka."x".$velkost->vyska." - ".$velkost; ?></option>
                <?php endforeach; ?>
            </select>
        </td>
    </tr>
    <tr title="Váš názov popisujúci túto reklamnú jednotku. Napr.: 'menu_vlavo' alebo 'reklama na uvodnej stranke'">
        <td>
            Názov (popis):
        </td>
        <td>
            <input type="text" name="meno" maxlength="50">
        </td>
    </tr>
    <tr title="Kategórie reklám (bannerov), ktoré chcete v tejto reklamnej jednotke zobrazovať.">
        <td>
            Kategórie:
        </td>
        <td>
            <select name="kategorie[]" multiple="multiple" size="5">
                <?php foreach($kategorie as $kategoria): ?>
                <option value="<?php echo $kategoria->id; ?>"><?php echo $kategoria; ?></option>
                <?php endforeach; ?>
            </select>
        </td>
    </tr>
    <tr>
        <td></td>
        <td>
            <input type="button" value="Uložiť" onClick="spracuj_add()">
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <div id="add_errbox" class="errbox"></div>
        </td>
    </tr>
</table>
</form>

<hr>
</div>

</div>
</body>
</html>
