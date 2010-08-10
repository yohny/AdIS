<?php
$nadpis = "Reklamy";
require 'left.php';
require 'secure.php';

$login = $_SESSION['user'];

require 'datab_con.php';

if(isset ($_GET['zmaz']))  //mazanie
{
    $query = "SELECT * FROM reklamy WHERE user=(SELECT id FROM users WHERE login='$login') AND id={$_GET['zmaz']}";
    $result = mysql_query($query) or die('Zlyhalo query!');
    if(mysql_num_rows($result)==1)
    {
        $query = "DELETE FROM reklamy WHERE id={$_GET['zmaz']}";
        mysql_query($query) or die('Zlyhalo query!');
        $query = "DELETE FROM kategoria_reklama WHERE reklama={$_GET['zmaz']}";
        mysql_query($query) or die('Zlyhalo query!');
    }
}

$query = "SELECT reklamy.*, velkosti.sirka, velkosti.vyska, velkosti.nazov FROM reklamy JOIN velkosti ON (reklamy.velkost=velkosti.id) WHERE user=(SELECT id FROM users WHERE login='$login') ORDER BY nazov";
$reklamy = mysql_query($query) or die('Zlyhalo query!');

$query = "SELECT * FROM velkosti ORDER BY nazov ASC";     //ziskanie typov bannerov
$velkosti = mysql_query($query) or die('Zlyhalo query!');

$query = "SELECT * FROM kategorie ORDER BY nazov ASC";     //ziskanie kategorii
$kategorie = mysql_query($query) or die('Zlyhalo query!');

if(mysql_num_rows($reklamy)>0)
  {$i=0;?>
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
                Zobraziť HTML kód
            </th>
            <th>
                Zmazať
            </th>
        </tr>
        </thead>
        <tbody>
    <?php while($row=mysql_fetch_array($reklamy)): $i++; ?>
        <tr <?php if($i%2==0) echo "class=\"dark\""; ?>>
            <td>
                <?php echo $row['nazov'];?>
            </td>
            <td>
                <?php echo $row['sirka']." x ".$row['vyska']; ?>
            </td>
            <td>
                <?php
                $query = "SELECT kategorie.nazov FROM kategoria_reklama JOIN kategorie ON (kategoria_reklama.kategoria=kategorie.id) WHERE reklama={$row['id']} ORDER BY kategorie.nazov ASC";
                $kateg = mysql_query($query) or die('Zlyhalo query!');
                while ($kat = mysql_fetch_array($kateg))
                    echo $kat['nazov']."<BR>";
                ?>
            </td>
            <td>
                <a href="javascript: show2('tr<?php echo $row['id']; ?>')"><?php echo $row['meno']; ?></a>
            </td>
            <td>
               <a href="javascript: if(confirm('Naozaj odstrániť?')) window.location.search='?zmaz=<?php echo $row['id'];?>'"><span class="r"><b>X</b></span></a>
            </td>
        </tr>
        <tr <?php if($i%2==0) echo "class=\"dark\"";?> style="display:none;" id="tr<?php echo $row['id']; ?>">
            <td colspan="5">
                <p>Nasledujúci HTML kód vložte do vašej stránky (na miesto kde chcete mať reklamu):</p>
                <textarea cols="60" rows="4">
&lt;script language="javascript" type="text/javascript" src="http://localhost/adis/script.php?rekl=<?php echo $row['id']; ?>"&gt;&lt;/script&gt;
                </textarea>
            </td>
        </tr>
    <?php endwhile; mysql_close($conn);?>
        </tbody>
    </table>
  <?php
  }
  else
    echo "<h4>Nemáte ešte žiadnu reklamu.</h4>";
  ?>
<hr>
<center>
<form name="add_form" action="pridaj.php" method="POST" enctype="multipart/form-data">
<table cellspacing="5" style="text-align:left;">
    <tr>
        <td title="Zvoľte rozmerový typ reklamnej jednotky.">
            Typ reklamy:
        </td>
        <td>
            <select name="velkost">
                <?php while($row=mysql_fetch_array($velkosti)): ?>
                <option value="<?php echo $row['id']; ?>"><?php echo $row['sirka']."x".$row['vyska']." - ".$row['nazov']; ?></option>
                <?php endwhile; ?>
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
                <?php while($row=mysql_fetch_array($kategorie)): ?>
                <option value="<?php echo $row['id']; ?>"><?php echo $row['nazov']; ?></option>
                <?php endwhile; ?>
            </select>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <input type="button" value="Pridaj" onClick="spracuj_add()">
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <div id="add_errbox" class="errbox"></div>
        </td>
    </tr>
</table>
</form>
</center>

<hr>
</div>

</div>
</body>
</html>
