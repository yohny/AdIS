<?php
Context::getInstance()->getResponse()->setHeading('štatistika');

define('ROWS_PER_PAGE', 10);

$filter = new Filter(ROWS_PER_PAGE);
if(!$filter->parse($_POST))
{
    Context::getInstance()->getResponse()->setFlash('Neplatné filtrovacie kritéria!');
    return;
}

$user = Context::getInstance()->getUser();
try
{
    $db = Context::getInstance()->getDatabase();
    $counts = $db->getStatisticsForUser($user, $filter, true);
    $stats = $db->getStatisticsForUser($user, $filter);
    if($user->kategoria=='inzer')
       $bannery = $db->getBanneryByUser();
    if($user->kategoria=='zobra')
       $reklamy = $db->getReklamyByUser();
}
catch(Exception $ex)
{
    Context::getInstance()->getResponse()->setFlash($ex->getMessage()) ;
    return;
}

//pre PAGER
$aktPage = $filter->page;
$pages = ceil($counts['count']/ROWS_PER_PAGE);
?>
<h4>Filter</h4>
<form name="filter" action="" method="POST">
    <input type="hidden" name="page" value="1"/>
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
                <?php require_once 'templates/partials/dateInterval.php'; ?>
            </td>
        </tr>
        <?php if($user->kategoria=="inzer"): ?>
        <tr>
            <td>Banner</td>
            <td>
                <select name="bann">
                    <option value="all">všetky</option>
                    <?php foreach($bannery as $banner): ?>
                    <option value="<?php echo $banner->id; ?>" <?php if($filter->banner==$banner->id) echo 'selected="selected"'; ?>>
                        <?php echo $banner.' ('.$banner->velkost->sirka.'x'.$banner->velkost->vyska.')';?>
                    </option>
                    <?php endforeach; ?>
                    <option value="del" <?php if($filter->banner=='del') echo 'selected="selected"'; ?>>zmazané</option>
                </select>
            </td>
        </tr>
        <?php endif; if($user->kategoria=="zobra"): ?>
        <tr>
            <td>Reklama</td>
            <td>
                <select name="rekl">
                    <option value="all">všetky</option>
                    <?php foreach($reklamy as $reklama): ?>
                    <option value="<?php echo $reklama->id; ?>" <?php if($filter->reklama==$reklama->id) echo 'selected="selected"'; ?>>
                        <?php echo $reklama.' ('.$reklama->velkost->sirka.'x'.$reklama->velkost->vyska.')'; ?>
                    </option>
                    <?php endforeach; ?>
                    <option value="del" <?php if($filter->reklama=='del') echo 'selected="selected"'; ?>>zmazané</option>
                </select>
            </td>
        </tr>
        <?php endif; ?>
        <tr>
            <td></td>
            <td>
                <input type="button" onclick="spracuj_filter()" value="Filtruj">
            </td>
        </tr>
        <tr>
            <td></td>
            <td><div id="filter_errbox" class="errbox"></div></td>
        </tr>
    </table>
</form>

<hr>
<h4>
    Zobrazení: <span class="g" style="font-size:16px;margin-right: 10px;"><?php echo $counts['views']?$counts['views']:'0'; ?></span>
    Kliknutí: <span class="g" style="font-size:16px;margin-right: 10px;"><?php echo $counts['clicks']?$counts['clicks']:'0'; ?></span>
    CTR: <span class="g" style="font-size:16px;margin-right: 10px;"><?php echo $counts['views']?number_format($counts['clicks']/$counts['views']*100, 2):'0.00'; ?>%</span>
</h4>
<?php if(count($stats)==0): ?>
<h4>Žiadne dáta!</h4>
<?php else: ?>
<?php include 'templates/partials/pager.php'; ?>
<table class="data">
    <thead>
        <tr>
        <th>Por.</th>
        <th>Dátum</th>
        <th><?php echo $user->kategoria=="inzer"?'Banner':'Reklama' ?></th>
        <th>Zobrazenia</th>
        <th>Kliky</th>
        <th>CTR</th>
        </tr>
    </thead>
    <tbody>
    <?php $i=0; foreach($stats as $stat): $i++; ?>
        <tr <?php if($i%2==0) echo "class=\"dark\""; ?>>
            <td>
                <?php echo ($filter->page-1)*ROWS_PER_PAGE+$i; ?>.
            </td>
            <td>
                <?php echo $stat; ?>
            </td>
            <td>
            <?php
            if($user->kategoria=="inzer")
                echo $stat->meno?$stat->meno:"#zmazaný";
            else//zobra
                echo $stat->meno?$stat->meno:"#zmazaná";
            ?>
            </td>
            <td>
                <?php echo $stat->zobrazenia?$stat->zobrazenia:"0"; ?>
            </td>
            <td>
                <?php echo $stat->kliky?$stat->kliky:"0"; ?>
            </td>
            <td>
                <?php echo $stat->zobrazenia?number_format($stat->kliky/$stat->zobrazenia*100, 2):'0.00'; ?>%
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php include 'templates/partials/pager.php'; ?>
<?php endif; ?>