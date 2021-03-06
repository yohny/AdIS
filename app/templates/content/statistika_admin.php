<?php
/**
 * obsah pre "statistika"
 *
 * @version    1.0
 * @package    AdIS
 * @subpackage templates
 * @author     Ján Neščivera <jan.nescivera@gmail.com>
 */


Context::getInstance()->getResponse()->setHeading('štatistika');

if (Context::getInstance()->getUser()->kategoria != User::ROLE_ADMIN)
    throw new Exception("Nepovolený prístup!");

$filter = new Filter($_GET);

$db = Context::getInstance()->getDatabase();
$pocet = $db->getStatisticsForAdmin($filter, true);
$events = $db->getStatisticsForAdmin($filter);
$bannery = $db->getAllFromBannery();
$reklamy = $db->getAllFromReklamy();

//pre PAGER
$aktPage = $filter->page;
$pages = ceil($pocet/Config::getStatRowsPerPage());
?>
<h4>Filter</h4>
<form name="filter" action="" method="get">
    <input type="hidden" name="page" value="1" />
    <table>
        <tr>
            <td>Obdobie:</td>
            <td>
                <select name="date" onchange="if(this.value=='custom') document.getElementById('customRow').style.display='table-row'; else document.getElementById('customRow').style.display='none'">
                    <?php foreach(Filter::$dateOptions as $key => $value): ?>
                    <option value="<?php echo $key; ?>" <?php if($filter->date==$key) echo 'selected="selected"'; ?>><?php echo $value; ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr id="customRow" <?php if($filter->date!='custom') echo 'style="display: none;"'; ?>>
            <td></td>
            <td>
                 <?php require_once TEMPLATES_DIR.'/partials/dateInterval.php';?>
            </td>
        </tr>
        <tr>
            <td>Banner</td>
            <td>
                <select name="bann">
                    <option value="all">všetky</option>
                    <?php foreach($bannery as $banner): ?>
                    <option value="<?php echo $banner->id; ?>" <?php if($filter->banner==$banner->id) echo 'selected="selected"'; ?>>
                        <?php echo htmlspecialchars($banner->filename); ?>
                    </option>
                    <?php endforeach; ?>
                    <option value="del" <?php if($filter->banner=='del') echo 'selected="selected"'; ?>>zmazané</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>Reklama</td>
            <td>
                <select name="rekl">
                    <option value="all">všetky</option>
                    <?php foreach($reklamy as $reklama): ?>
                    <option value="<?php echo $reklama->id; ?>" <?php if($filter->reklama==$reklama->id) echo 'selected="selected"'; ?>>
                        <?php echo "$reklama ({$reklama->velkost->sirka}x{$reklama->velkost->vyska})" ?>
                    </option>
                    <?php endforeach; ?>
                    <option value="del" <?php if($filter->reklama=='del') echo 'selected="selected"'; ?>>zmazané</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>Druh</td>
            <td>
                <input type="radio" name="type" value="click" id="click" checked="checked" /><label for="click">Kliky</label>
                <input type="radio" name="type" value="view" id ="view" <?php if($filter->type=='view') echo 'checked="checked"'; ?>/><label for="view">Zobrazenia</label>
            </td>
        </tr>
        <tr>
            <td></td>
            <td>
                <input type="button" onclick="spracuj_filter()" value="Filtruj" />
            </td>
        </tr>
        <tr>
            <td></td>
            <td><div id="filter_errbox" class="errbox"></div></td>
        </tr>
    </table>
</form>

<hr/>
<h4>Počet: <span class="g" style="font-size:16px;"><?php echo $pocet; ?></span></h4>
<?php if(count($events)==0): ?>
<h4 class="r">Žiadne dáta!</h4>
<?php else: ?>
<?php  include TEMPLATES_DIR.'/partials/pager.php'; ?>
<table class="data">
    <thead>
        <tr>
        <th>Por.</th>
        <th>Čas</th>
        <th>Zobrazovateľ</th>
        <th>Reklama</th>
        <th>Inzerent</th>
        <th>Banner</th>
        </tr>
    </thead>
    <tbody>
    <?php $i=0; foreach($events as $event): $i++; ?>
        <tr <?php if($i%2==0) echo "class=\"dark\""; ?>>
            <td>
                <?php echo ($filter->page-1)*Config::getStatRowsPerPage()+$i; ?>.
            </td>
            <td>
                <?php echo $event->cas->format('d.m.Y H:i:s'); ?>
            </td>
            <td>
                <?php echo ($event->zobraLogin?htmlspecialchars($event->zobraLogin):"#zmazaný")." ($event->zobraId)"; ?>
            </td>
            <td>
                <?php echo ($event->reklamaName?htmlspecialchars($event->reklamaName):"#zmazaná")." ($event->reklamaId)"; ?>
            </td>
            <td>
                <?php echo ($event->inzerLogin?htmlspecialchars($event->inzerLogin):"#zmazaný")." ($event->inzerId)"; ?>
            </td>
            <td>
                <?php echo ($event->bannerFilename?htmlspecialchars($event->bannerFilename):"#zmazaný")." ($event->bannerId)"; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php include TEMPLATES_DIR.'/partials/pager.php'; ?>
<?php endif; ?>