<?php
$nadpis = "Štatistika";
require 'base/left.php';
require 'base/secure.php';

define('ROWS_PER_PAGE', 10);

require_once 'base/model/Filter.php';
$filter = new Filter(ROWS_PER_PAGE);

if(isset($_GET['page'])) 
    $filter->page = $_GET['page'];
if(isset($_GET['date']))
    $filter->date = $_GET['date'];
if(isset($_GET['bann']))
    $filter->banner = $_GET['bann'];
if(isset($_GET['rekl']))
    $filter->reklama = $_GET['rekl'];

/* @var $user User */ //aktualne prihlaseny pouzivatel
$user = $_SESSION['user'];


require_once 'base/Database.php';
$db = new Database();

$pocetKlikov = $db->getKlikyByUser($user, $filter, true);
$kliky = $db->getKlikyByUser($user, $filter);

//udaje pre filter
//TODO zmenit z datumov na intervaly (den, tyzden,mesiac,rok...)
$query = "SELECT DISTINCT DATE(cas) AS datum FROM kliky";
if($user->kategoria!="admin")
    $query .= " WHERE $user->kategoria=$user->id";
$query .= " ORDER BY DATE(cas) DESC";
/* @var $datumy mysqli_result */
$datumy = $db->conn->query($query);

if($user->kategoria=='inzer' || $user->kategoria=='admin')
   $bannery = $db->getBanneryByUser($user);
if($user->kategoria=='zobra' || $user->kategoria=='admin')
   $reklamy = $db->getReklamyByUser($user);

//pre PAGER
$aktPage = $filter->page;
$pages = ceil($pocetKlikov/ROWS_PER_PAGE);
?>
<h4>Filter</h4>
<center>
<table>
    <tr>
        <td>Obdobie:</td>
        <td>
            <select id="dateSelect">
                <option value="<?php echo date('Y-m-d'); ?>">dnes</option>
                <option value="all" <?php if($filter->date=='all') echo 'selected="selected"'; ?>>komplet</option>
                <?php while($row = $datumy->fetch_object()): if(date('Y-m-d')!=$row->datum):?>
                <option value="<?php echo $row->datum; ?>" <?php if($filter->date==$row->datum) echo 'selected="selected"'; ?>><?php echo date_format(new DateTime($row->datum), 'd.m.Y'); ?></option>
                <?php endif; endwhile; ?>
            </select>
        </td>
    </tr>
    <?php if($user->kategoria=="inzer" || $user->kategoria=="admin"): ?>
    <tr>
        <td>Banner</td>
        <td>
            <select id="bannSelect">
                <option value="all">všetky</option>
                <?php foreach($bannery as $banner): ?>
                <option value="<?php echo $banner->id; ?>" <?php if($filter->banner==$banner->id) echo 'selected="selected"'; ?>>
                    <?php
                    if($user->kategoria!="admin")
                        echo substr($banner,strlen($user.$banner->velkost->sirka.$banner->velkost->vyska)+3).' ('.$banner->velkost->sirka.'x'.$banner->velkost->vyska.')';
                    else
                        echo "$banner ({$banner->velkost->sirka}x{$banner->velkost->vyska}) :$banner->id";
                    ?>
                </option>
                <?php endforeach; ?>
            </select>
        </td>
    </tr>
    <?php endif; if($user->kategoria=="zobra" || $user->kategoria=="admin"): ?>
    <tr>
        <td>Reklama</td>
        <td>
            <select id="reklSelect">
                <option value="all">všetky</option>
                <?php foreach($reklamy as $reklama): ?>
                <option value="<?php echo $reklama->id; ?>" <?php if($filter->reklama==$reklama->id) echo 'selected="selected"'; ?>>
                    <?php
                    if($user->kategoria!="admin")
                        echo $reklama.' ('.$reklama->velkost->sirka.'x'.$reklama->velkost->vyska.')';
                    else
                        echo "$reklama ({$reklama->velkost->sirka}x{$reklama->velkost->vyska}) :$reklama->id"
                    ?>
                </option>
                <?php endforeach; ?>
            </select>
        </td>
    </tr>
    <?php endif; ?>
    <tr>
        <td></td>
        <td>
            <input type="button" onclick="filter();" value="OK">
        </td>
    </tr>
</table>
</center>
<hr>
<h4>Kliknutí: <span class="g" style="font-size:16px;"><?php echo $pocetKlikov; ?></span></h4>
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
            <?php if($user->kategoria=="admin"): ?>
            <th>Zobrazovateľ</th>
            <th>Reklama</th>
            <th>Inzerent</th>
            <th>Banner</th>
            <?php endif; if($user->kategoria=="inzer"): ?>
            <th>Banner</th>
            <?php endif; if($user->kategoria=="zobra"): ?>
            <th>Reklama</th>
            <?php endif; ?>
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
                <?php if($user->kategoria=="admin"): ?>
                <td><?php echo ($klik->zobraLogin==''?"#zmazaný":$klik->zobraLogin)." ($klik->zobraId)"; ?></td>
                <td><?php echo ($klik->reklamaName==''?"#zmazaná":$klik->reklamaName)." ($klik->reklamaId)"; ?></td>
                <td><?php echo ($klik->inzerLogin==''?"#zmazaný":$klik->inzerLogin)." ($klik->inzerId)"; ?></td>
                <td><?php echo ($klik->bannerFilename==''?"#zmazaný":$klik->bannerFilename)." ($klik->bannerId)"; ?></td>
                <?php endif; if($user->kategoria=="inzer"): ?>
                <td><?php echo $klik->bannerFilename==''?"#zmazaný":substr($klik->bannerFilename,strlen($klik->inzerLogin)+1); ?></td>
                <?php endif; if($user->kategoria=="zobra"): ?>
                <td><?php echo $klik->reklamaName==''?"#zmazaná":$klik->reklamaName; ?></td>
                <?php endif; ?>
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
