<?php
$nadpis = "Bannery";
require 'base/left.php';
require 'base/secure.php';

$login = $_SESSION['user'];

require_once 'base/model/Database.php';
$db = new Database();

$bannery = $db->getBanneryByUser($login);
$velkosti = $db->getAllFromVelkosti();      //ziskanie typov bannerov
$kategorie = $db->getAllFromKategorie();    //ziskanie kategorii

if(count($bannery)>0)
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
    <?php foreach($bannery as $banner): $i++; ?>
        <tr <?php if($i%2==0) echo "class=\"dark\""; ?>>
            <td>
                <?php echo $banner->velkost->nazov;?>
            </td>
            <td>
                <?php echo $banner->velkost->sirka." x ".$banner->velkost->vyska; ?>
            </td>
            <td>
                <?php
                $kateg = $banner->getKategorie($db->conn);
                foreach($kateg as $kat)
                    echo $kat."<BR>";
                ?>
            </td>
            <td>
                <a href="javascript: show2('tr<?php echo $banner->id; ?>')"><?php echo substr($banner->filename,strlen($login.$banner->velkost->sirka.$banner->velkost->vyska)+3); ?></a>
            </td>
            <td>
                <form method="POST" action="actions/zmaz.php">
                    <input type="hidden" name="zmaz" value="<?php echo $banner->id;?>">
                    <input type="button" value="Zmaž" onclick="if(confirm('Naozaj odstrániť?')) this.parentNode.submit();">
                </form>
            </td>
        </tr>
        <tr <?php if($i%2==0) echo "class=\"dark\"";?>  style="display:none;" id="tr<?php echo $banner->id; ?>">
            <td colspan="5">
                <div style="max-height:200px;overflow: auto;">
                <img alt="banner" src="<?php echo 'upload/'.$banner->filename; ?>">
                </div>
            </td>
        </tr>
    <?php endforeach; ?>
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
                <?php foreach($velkosti as $velkost): ?>
                <option value="<?php echo $velkost->id; ?>"><?php echo $velkost->sirka."x".$velkost->vyska." - ".$velkost; ?></option>
                <?php endforeach; ?>
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
                <?php foreach($kategorie as $kategoria): ?>
                <option value="<?php echo $kategoria->id; ?>"><?php echo $kategoria; ?></option>
                <?php endforeach; ?>
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
