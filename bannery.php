<?php
$nadpis = "Bannery";
require 'base/left.php';
require 'base/secure.php';

$login = $_SESSION['user'];

require 'base/datab_con.php';
/* @var $conn mysqli */

$query = "SELECT bannery.*, velkosti.sirka, velkosti.vyska, velkosti.nazov FROM bannery JOIN velkosti ON (bannery.velkost=velkosti.id) WHERE user=(SELECT id FROM users WHERE login='$login') ORDER BY nazov";
/* @var $bannery mysqli_result */
$bannery = $conn->query($query);

$query = "SELECT * FROM velkosti ORDER BY nazov ASC";     //ziskanie typov bannerov
/* @var $velkosti mysqli_result */
$velkosti = $conn->query($query);

$query = "SELECT * FROM kategorie ORDER BY nazov ASC";     //ziskanie kategorii
/* @var $kategorie mysqli_result */
$kategorie = $conn->query($query);

if($bannery->num_rows>0)
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
                Zobraziť banner
            </th>
            <th>
                Akcia
            </th>
        </tr>
        </thead>
        <tbody>
    <?php while($row=$bannery->fetch_object()): $i++; ?>
        <tr <?php if($i%2==0) echo "class=\"dark\""; ?>>
            <td>
                <?php echo $row->nazov;?>
            </td>
            <td>
                <?php echo $row->sirka." x ".$row->vyska; ?>
            </td>
            <td>
                <?php
                $query = "SELECT kategorie.nazov FROM kategoria_banner JOIN kategorie ON (kategoria_banner.kategoria=kategorie.id) WHERE banner=$row->id ORDER BY kategorie.nazov ASC";
                $kateg = $conn->query($query);
                while ($kat = $kateg->fetch_object())
                    echo $kat->nazov."<BR>";
                ?>
            </td>
            <td>
                <a href="javascript: show2('tr<?php echo $row->id; ?>')"><?php echo substr($row->path,strlen($login.$row->sirka.$row->vyska)+3); ?></a>
            </td>
            <td>
                <form method="POST" action="actions/zmaz.php">
                    <input type="hidden" name="zmaz" value="<?php echo $row->id;?>">
                    <input type="button" value="Zmaž" onclick="if(confirm('Naozaj odstrániť?')) this.parentNode.submit();">
                </form>
            </td>
        </tr>
        <tr <?php if($i%2==0) echo "class=\"dark\"";?>  style="display:none;" id="tr<?php echo $row->id; ?>">
            <td colspan="5">
                <div style="max-height:200px;overflow: auto;">
                <img alt="banner" src="<?php echo 'upload/'.$row->path; ?>">
                </div>
            </td>
        </tr>
    <?php endwhile;$conn->close();?>
        </tbody>
    </table>
  <?php
  }
  else
    echo "<h4>Nemáte ešte žiaden banner.</h4>";
  ?>
<hr>
<center>
<form name="upl_form" action="actions/uploadni.php" method="POST" enctype="multipart/form-data">
<table cellspacing="5" style="text-align:left;">
    <tr title="Zvoľte rozmerový typ reklamného banneru.">
        <td>
            Typ banneru:
        </td>
        <td>
            <select name="velkost">
                <?php while($row=$velkosti->fetch_object()): ?>
                <option value="<?php echo $row->id; ?>"><?php echo $row->sirka."x".$row->vyska." - ".$row->nazov; ?></option>
                <?php endwhile; ?>
            </select>
        </td>
    </tr>
    <tr title="Zvoľte súbor banneru.">
        <td>
            Banner:
        </td>
        <td>
            <input type="file" name="userfile">
        </td>
    </tr>
    <tr title="Kategórie, do ktorých spadá Vaša stránka (prezentovaná týmto bannerom).">
        <td>
            Kategórie:
        </td>
        <td>
            <select name="kategorie[]" multiple="multiple" size="5">
                <?php while($row=$kategorie->fetch_object()): ?>
                <option value="<?php echo $row->id; ?>"><?php echo $row->nazov; ?></option>
                <?php endwhile; ?>
            </select>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <input type="button" value="Upload" onClick="spracuj_upl()">
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <div id="upl_errbox" class="errbox"></div>
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
