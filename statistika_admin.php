<?php
$nadpis = "Štatistika";
require 'base/layout.php';
require 'base/secure.php';

/* @var $user User */ //aktualne prihlaseny pouzivatel
$user = $_SESSION['user'];
if($user->kategoria!='admin')
        exit('Nepovolený prístup');

define('ROWS_PER_PAGE', 10);

require_once 'base/model/Filter.php';
$filter = new Filter(ROWS_PER_PAGE);

if(isset($_POST['page']))
    $filter->page = $_POST['page'];
if(isset($_POST['date']))
{
    $filter->date = $_POST['date'];
    if($filter->date=='custom')
    {
        $filter->odDay = $_POST['odDay'];
        $filter->odMonth = $_POST['odMonth'];
        $filter->odYear = $_POST['odYear'];
        $filter->doDay = $_POST['doDay'];
        $filter->doMonth = $_POST['doMonth'];
        $filter->doYear = $_POST['doYear'];
    }
}
if(isset($_POST['bann']))
    $filter->banner = $_POST['bann'];
if(isset($_POST['rekl']))
    $filter->reklama = $_POST['rekl'];
if(isset($_POST['type']))
    $filter->type = $_POST['type'];

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

$pocet = $db->getStatisticsForAdmin($filter, true);
$events = $db->getStatisticsForAdmin($filter);

//udaje pre filter
$datumy = array(
    'today' =>  'dnes',
    'week'  =>  'posledný týždeň',
    'month' =>  'posledný mesiac',
    'year'  =>  'posledný rok',
    'all'   =>  'komplet',
    'custom'=>  'vlastné obdobie');

$bannery = $db->getBanneryByUser($user);
$reklamy = $db->getReklamyByUser($user);

//pre PAGER
$aktPage = $filter->page;
$pages = ceil($pocet/ROWS_PER_PAGE);
?>
<h4>Filter</h4>
<form name="filter" action="" method="POST">
    <input type="hidden" name="page" value="1"/>
    <table>
        <tr>
            <td>Obdobie:</td>
            <td>
                <select name="date" onchange="if(this.value=='custom') document.getElementById('customRow').style.display='table-row'; else document.getElementById('customRow').style.display='none'">
                    <?php foreach($datumy as $key => $value): ?>
                    <option value="<?php echo $key; ?>" <?php if($filter->date==$key) echo 'selected="selected"'; ?>><?php echo $value; ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr id="customRow" <?php if($filter->date!='custom') echo 'style="display: none;"'; ?>>
            <td></td>
            <td>
                 <?php require_once 'base/partials/dateInterval.php';?>
            </td>
        </tr>
        <tr>
            <td>Banner</td>
            <td>
                <select name="bann">
                    <option value="all">všetky</option>
                    <?php foreach($bannery as $banner): ?>
                    <option value="<?php echo $banner->id; ?>" <?php if($filter->banner==$banner->id) echo 'selected="selected"'; ?>>
                        <?php echo "$banner ({$banner->velkost->sirka}x{$banner->velkost->vyska}) :$banner->id"; ?>
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
                        <?php echo "$reklama ({$reklama->velkost->sirka}x{$reklama->velkost->vyska}) :$reklama->id" ?>
                    </option>
                    <?php endforeach; ?>
                    <option value="del" <?php if($filter->reklama=='del') echo 'selected="selected"'; ?>>zmazané</option>
                </select>
            </td>
        </tr>
        <tr>
            <td colspan="2">
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
<hr>
<h4>Počet: <span class="g" style="font-size:16px;"><?php echo $pocet; ?></span></h4>
<?php
if(count($events)==0)
    echo "<h4>Žiadne dáta!</h4>";
else
{ $i=0; include 'base/partials/pager.php'; ?>
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
        <?php foreach($events as $event): $i++; ?>
            <tr <?php if($i%2==0) echo "class=\"dark\""; ?>>
                <td>
                    <?php echo ($filter->page-1)*ROWS_PER_PAGE+$i; ?>.
                </td>
                <td>
                    <?php echo $event; ?>
                </td>
                <td>
                    <?php echo ($event->zobraLogin==''?"#zmazaný":$event->zobraLogin)." ($event->zobraId)"; ?>
                </td>
                <td>
                    <?php echo ($event->reklamaName==''?"#zmazaná":$event->reklamaName)." ($event->reklamaId)"; ?>
                </td>
                <td>
                    <?php echo ($event->inzerLogin==''?"#zmazaný":$event->inzerLogin)." ($event->inzerId)"; ?>
                </td>
                <td>
                    <?php echo ($event->bannerFilename==''?"#zmazaný":$event->bannerFilename)." ($event->bannerId)"; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php include 'base/partials/pager.php'; } ?>
<hr>
</div>

</div>
</body>
</html>
