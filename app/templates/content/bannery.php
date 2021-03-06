<?php
/**
 * obsah pre "bannery"
 *
 * @version    1.0
 * @package    AdIS
 * @subpackage templates
 * @author     Ján Neščivera <jan.nescivera@gmail.com>
 */

Context::getInstance()->getResponse()->setHeading('bannery');
if (Context::getInstance()->getUser()->kategoria != User::ROLE_INZER)
    throw new Exception("Nepovolený prístup!");

$db = Context::getInstance()->getDatabase();
$bannery = $db->getBanneryByUser(Context::getInstance()->getUser());   //ziskanie bannerov pouzivatela
$velkosti = $db->getAllFromVelkosti();      //ziskanie typov bannerov
$kategorie = $db->getAllFromKategorie();    //ziskanie kategorii

if(count($bannery)==0): ?>
<h4>Nemáte ešte žiaden banner</h4>
<?php else: ?>
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
    <?php $i=0; foreach($bannery as $banner): $i++; ?>
    <tr <?php if($i%2==0) echo "class=\"dark\""; ?>>
        <td>
            <?php echo $banner->velkost;?>
        </td>
        <td>
            <?php echo $banner->velkost->sirka." x ".$banner->velkost->vyska; ?>
        </td>
        <td>
            <?php echo implode(', ', $banner->getKategorie()); ?>
        </td>
        <td>
            <a href="#" onclick="show2('tr<?php echo $banner->id; ?>');return false;"><?php echo $banner; ?></a>
        </td>
        <td>
            <form method="post" action="/action/zmaz">
                <input type="hidden" name="zmaz" value="<?php echo $banner->id; ?>" />
                <input type="hidden" name="csrf_token" value="<?php echo Context::getInstance()->getCsrfToken(); ?>" />
                <input type="button" value="Zmaž" onclick="if(confirm('Naozaj odstrániť?')) this.form.submit();" />
            </form>
        </td>
    </tr>
    <tr <?php if($i%2==0) echo "class=\"dark\""; ?>  style="display:none;" id="tr<?php echo $banner->id; ?>">
        <td colspan="5">
            <div style="max-height:200px;overflow: auto;">
            <img alt="banner" src="/img/banner?id=<?php echo $banner->id; ?>" />
            </div>
        </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>

<hr/>
<h4>Pridanie nového banneru:</h4>
<form name="upl_form" action="/action/pridajBanner" method="post" enctype="multipart/form-data">
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
                <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo Config::getUploadSize(); ?>" />
                <input type="hidden" name="APC_UPLOAD_PROGRESS" value="<?php echo uniqid(); ?>" />
                <input type="file" name="userfile" />
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
                <input type="button" value="Uložiť" onclick="spracuj_upl()" />
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <div id="upl_errbox" class="errbox"></div>
            </td>
        </tr>
    </table>
    <input type="hidden" name="csrf_token" value="<?php echo Context::getInstance()->getCsrfToken(); ?>" />
</form>