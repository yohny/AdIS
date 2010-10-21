<?php
$nadpis = "Štatistika";
require 'base/left.php';
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

require_once 'base/Database.php';
$db = new Database();

$pocet = $db->getKliky( $filter, true);
$kliky = $db->getKliky($filter);

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
                 od:
                <select name="odDay">
                    <?php
                    for($i=1;$i<32;$i++)
                        echo '<option value="'.$i.'"'.($i==$filter->odDay?' selected="selected"':'').'>'.$i.'</option>';
                    ?>
                </select>
                <select name="odMonth">
                    <?php
                    for($i=1;$i<13;$i++)
                        echo '<option value="'.$i.'"'.($i==$filter->odMonth?' selected="selected"':'').'>'.$i.'</option>';
                    ?>
                </select>
                <select name="odYear">
                    <?php
                    for($i=1995;$i<2026;$i++)
                        echo '<option value="'.$i.'"'.($i==$filter->odYear?' selected="selected"':'').'>'.$i.'</option>';
                    ?>
                </select>
                 do:
                <select name="doDay">
                    <?php
                    for($i=1;$i<32;$i++)
                        echo '<option value="'.$i.'"'.($i==$filter->doDay?' selected="selected"':'').'>'.$i.'</option>';
                    ?>
                </select>
                <select name="doMonth">
                    <?php
                    for($i=1;$i<13;$i++)
                        echo '<option value="'.$i.'"'.($i==$filter->doMonth?' selected="selected"':'').'>'.$i.'</option>';
                    ?>
                </select>
                <select name="doYear">
                    <?php
                    for($i=1995;$i<2026;$i++)
                        echo '<option value="'.$i.'"'.($i==$filter->doYear?' selected="selected"':'').'>'.$i.'</option>';
                    ?>
                </select>
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
                </select>
            </td>
        </tr>
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
<h4>Počet: <span class="g" style="font-size:16px;"><?php echo $pocet; ?></span></h4>
<?php
if(count($kliky)==0)
    echo "<h4>Žiadne dáta!</h4>";
else
{ $i=0; include 'base/pager.php'; ?>
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
        <?php foreach($kliky as $klik): $i++; ?>
            <tr <?php if($i%2==0) echo "class=\"dark\""; ?>>
                <td>
                    <?php echo ($filter->page-1)*ROWS_PER_PAGE+$i; ?>.
                </td>
                <td>
                    <?php echo $klik; ?>
                </td>
                <td>
                    <?php echo ($klik->zobraLogin==''?"#zmazaný":$klik->zobraLogin)." ($klik->zobraId)"; ?>
                </td>
                <td>
                    <?php echo ($klik->reklamaName==''?"#zmazaná":$klik->reklamaName)." ($klik->reklamaId)"; ?>
                </td>
                <td>
                    <?php echo ($klik->inzerLogin==''?"#zmazaný":$klik->inzerLogin)." ($klik->inzerId)"; ?>
                </td>
                <td>
                    <?php echo ($klik->bannerFilename==''?"#zmazaný":$klik->bannerFilename)." ($klik->bannerId)"; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php include 'base/pager.php'; } ?>
<hr>
</div>

</div>
</body>
</html>