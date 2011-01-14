<?php
Context::getInstance()->getResponse()->setHeading('bannery');

try
{
    $db = new Database();
}
catch(Exception $ex)
{
    echo 'nepodarilo sa pripojit na db';
    return;
}

$bannery = $db->getBanneryByUser($_SESSION['user']);   //ziskanie bannerov pouzivatela
$velkosti = $db->getAllFromVelkosti();      //ziskanie typov bannerov
$kategorie = $db->getAllFromKategorie();    //ziskanie kategorii

if(count($bannery)>0)
  {$i=0;?>
    <h4>Vaše bannery:</h4>
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
                <?php echo implode(', ', $banner->getKategorie($db->conn)); ?>
            </td>
            <td>
                <a onclick="show2('tr<?php echo $banner->id; ?>')"><?php echo substr($banner,strlen($_SESSION['user'].$banner->velkost->sirka.$banner->velkost->vyska)+3); ?></a>
            </td>
            <td>
                <form method="POST" action="/action/zmaz">
                    <input type="hidden" name="zmaz" value="<?php echo $banner->id; ?>">
                    <input type="button" value="Zmaž" onclick="if(confirm('Naozaj odstrániť?')) this.parentNode.submit();">
                </form>
            </td>
        </tr>
        <tr <?php if($i%2==0) echo "class=\"dark\"";?>  style="display:none;" id="tr<?php echo $banner->id; ?>">
            <td colspan="5">
                <div style="max-height:200px;overflow: auto;">
                <img alt="banner" src="<?php echo 'upload/'.$banner; ?>">
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
<h4>Pridanie nového banneru:</h4>
<form name="upl_form" action="/action/pridajBanner" method="POST" enctype="multipart/form-data">
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
        <td></td>
        <td>
            <input type="button" value="Uložiť" onClick="spracuj_upl()">
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <div id="upl_errbox" class="errbox"></div>
        </td>
    </tr>
</table>
</form>

<hr>
</div>

</div>
</body>
</html>
