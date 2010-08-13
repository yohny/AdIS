<?php 
$nadpis = "Štatistika";
require 'base/left.php';
require 'base/secure.php';

$maxPageRows = 10;

$user = $_SESSION['user'];
$group = $_SESSION['group'];

$aktPage = 1;  //default je prva strana
if(isset($_GET['page'])) 
    $aktPage = $_GET['page'];
$aktDate = date('Y-m-d', time()); //default je dnesok
if(isset($_GET['date']))
    $aktDate = $_GET['date'];
$aktBann = 'all';   //default vsetky bannery
if(isset($_GET['bann']))
    $aktBann = $_GET['bann'];
$aktRekl = 'all';   //default vsetky reklamy
if(isset($_GET['rekl']))
    $aktRekl = $_GET['rekl'];


$query = "SELECT kliky.*, reklamy.meno, bannery.path, u1.login AS zobra_login, u2.login AS inzer_login FROM kliky
    LEFT JOIN reklamy ON (kliky.reklama=reklamy.id)
    LEFT JOIN bannery ON (kliky.banner=bannery.id)
    LEFT JOIN users  AS u1 ON (kliky.zobra=u1.id)
    LEFT JOIN users AS u2 ON (kliky.inzer=u2.id)";
//    $query .= " LEFT JOIN users ON ()"; //pre admina
if($group != "admin")
    $query .= " WHERE $group=(SELECT id FROM users WHERE login='$user')";
else
    $query .= " WHERE 1";

//filter
if($aktDate!='all')
    $query .= " AND DATE(cas)='$aktDate'";
if($aktBann!='all')
    $query .= " AND bannery.id=$aktBann";
if($aktRekl!='all')
    $query .= " AND reklamy.id=$aktRekl";

require 'base/datab_con.php';
/* @var $conn mysqli */


//paging
$countQuery = preg_replace('/(select) (.*) (from kliky)/i', '$1 COUNT(*) AS count $3', $query);  //non-case-sensitive /i, 'from kliky' aby nenahradilo aj v subquery
/* @var $result mysqli_result */
$result = $conn->query($countQuery);
$count = $result->fetch_object()->count;
$pages = ceil($count/$maxPageRows);

$query .= " ORDER BY cas DESC";
$query .= " LIMIT ".($aktPage-1)*$maxPageRows.", $maxPageRows";
$result = $conn->query($query); //result s klikmi podla poziadaviek filtra

//udaje pre filter
$query = "SELECT DISTINCT DATE(cas) AS datum FROM kliky";
if($group!="admin")
    $query .= " WHERE $group=(SELECT id FROM users WHERE login='$user')";
$query .= " ORDER BY DATE(cas) DESC";
/* @var $datumy mysqli_result */
$datumy = $conn->query($query);
if($group!='admin')
{
    $table = $group=='inzer'?"bannery":"reklamy";
    $query = "SELECT $table.*, velkosti.sirka, velkosti.vyska
        FROM $table JOIN velkosti ON ($table.velkost=velkosti.id)
        WHERE user=(SELECT id FROM users WHERE login='$user')
        ORDER BY nazov ASC";  //nazov velkoti/typu banneru
    /* @var $bannrekl mysqli_result */
    $bannrekl = $conn->query($query);
}
else  //query pre admina - extra pre rekl aj bann + inze a zobr
{
    $query = "SELECT bannery.*, velkosti.sirka, velkosti.vyska, users.login
        FROM bannery 
        JOIN velkosti ON (bannery.velkost=velkosti.id)
        JOIN users ON (bannery.user=users.id)
        ORDER BY login ASC";  //nazov velkoti/typu banneru
    /* @var $bannery mysqli_result */
    $bannery = $conn->query($query);
    $query = "SELECT reklamy.*, velkosti.sirka, velkosti.vyska, users.login
        FROM reklamy 
        JOIN velkosti ON (reklamy.velkost=velkosti.id)
        JOIN users ON (reklamy.user=users.id)
        ORDER BY login ASC";  //nazov velkoti/typu banneru
    /* @var $reklamy mysqli_result */
    $reklamy = $conn->query($query);
}

$conn->close();
?>
<h4>Filter</h4>
<center>
<table>
    <tr>
        <td>Obdobie:</td>
        <td>
            <select id="dateSelect">
                <option value="<?php echo date('Y-m-d'); ?>">dnes</option>
                <option value="all" <?php if($aktDate=='all') echo 'selected="selected"'; ?>>komplet</option>
                <?php while($row = $datumy->fetch_object()): if(date('Y-m-d')!=$row->datum):?>
                <option value="<?php echo $row->datum; ?>" <?php if($aktDate==$row->datum) echo 'selected="selected"'; ?>><?php echo date_format(new DateTime($row->datum), 'd.m.Y'); ?></option>
                <?php endif; endwhile; ?>
            </select>
        </td>
    </tr>
    <?php if($group=="inzer"): ?>
    <tr>
        <td>Banner</td>
        <td>
            <select id="bannSelect">
                <option value="all">všetky</option>
                <?php while($row = $bannrekl->fetch_object()): ?>
                <option value="<?php echo $row->id; ?>" <?php if($aktBann==$row->id) echo 'selected="selected"'; ?>><?php echo substr(basename($row->path),strlen($user.$row->sirka.$row->vyska)+3).' ('.$row->sirka.'x'.$row->vyska.')'; ?></option>
                <?php endwhile; ?>
            </select>
        </td>
    </tr>
    <?php endif; if($group=="zobra"): ?>
    <tr>
        <td>Reklama</td>
        <td>
            <select id="reklSelect">
                <option value="all">všetky</option>
                <?php while($row = $bannrekl->fetch_object()): ?>
                <option value="<?php echo $row->id; ?>" <?php if($aktRekl==$row->id) echo 'selected="selected"'; ?>><?php echo $row->meno.' ('.$row->sirka.'x'.$row->vyska.')'; ?></option>
                <?php endwhile; ?>
            </select>
        </td>
    </tr>
    <?php endif; if($group=="admin"): ?>
    <tr>
        <td>Reklama</td>
        <td>
            <select id="reklSelect">
                <option value="all">všetky</option>
                <?php while($row = $reklamy->fetch_object()): ?>
                <option value="<?php echo $row->id; ?>" <?php if($aktRekl==$row->id) echo 'selected="selected"'; ?>><?php echo $row->meno.' ('.$row->sirka.'x'.$row->vyska.')'.' | '.$row->login.' ('.$row->user.')'; ?></option>
                <?php endwhile; ?>
            </select>
        </td>
    </tr>
    <tr>
        <td>Banner</td>
        <td>
            <select id="bannSelect">
                <option value="all">všetky</option>
                <?php while($row = $bannery->fetch_object()):?>
                <option value="<?php echo $row->id; ?>" <?php if($aktBann==$row->id) echo 'selected="selected"'; ?>><?php echo substr(basename($row->path),strlen($row->login.$row->sirka.$row->vyska)+3).' ('.$row->sirka.'x'.$row->vyska.')'.' | '.$row->login.' ('.$row->user.')'; ?></option>
                <?php endwhile; ?>
            </select>
        </td>
    </tr>
    <?php endif; ?>
    <tr>
        <td></td>
        <td>
            <input type="button"  onclick="filter();" value="OK">
        </td>
    </tr>
</table>
</center>
<hr>
<h4>Počet klikov za <?php echo ($aktDate=='all'?'celé obdobie':date_format(new DateTime($aktDate), 'd.m.Y')); ?>
<?php if($group=='inzer')
         echo ' pre '.($aktBann=="all"?"všetky bannery":"<script language=\"javascript\" type=\"text/javascript\">document.write(document.getElementById('bannSelect').options[document.getElementById('bannSelect').selectedIndex].text);</script>");
      if($group=='zobra')
         echo ' pre '.($aktRekl=="all"?"všetky reklamy":"<script language=\"javascript\" type=\"text/javascript\">document.write(document.getElementById('reklSelect').options[document.getElementById('reklSelect').selectedIndex].text);</script>");
      if($group=='admin')
      {
         echo ' pre '.($aktBann=="all"?"všetky bannery":"<script language=\"javascript\" type=\"text/javascript\">document.write(document.getElementById('bannSelect').options[document.getElementById('bannSelect').selectedIndex].text);</script>");
         echo ' a pre '.($aktRekl=="all"?"všetky reklamy":"<script language=\"javascript\" type=\"text/javascript\">document.write(document.getElementById('reklSelect').options[document.getElementById('reklSelect').selectedIndex].text);</script>");
      }
    ?>: <span class="g" style="font-size:16px;"><?php echo $count; ?></span></h4>
<?php
if($result->num_rows==0)
    echo "<h4>Žiadne dáta!</h4>";
else
{ $i=0; include 'base/pager.php'; ?>
    <table class="data">
        <thead>
            <tr>
            <th>Por.</th>
            <th>Čas</th>
            <?php if($group=="admin"): ?>
            <th>Zobrazovateľ</th>
            <th>Reklama</th>
            <th>Inzerent</th>
            <th>Banner</th>
            <?php endif; if($group=="inzer"): ?>
            <th>Banner</th>
            <?php endif; if($group=="zobra"): ?>
            <th>Reklama</th>
            <?php endif; ?>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_object()): $i++; ?>
            <tr <?php if($i%2==0) echo "class=\"dark\""; ?>>
                <td>
                    <?php echo ($aktPage-1)*$maxPageRows+$i; ?>.
                </td>
                <td>
                    <?php echo date_format(new DateTime($row->cas), 'd.m.Y H:i:s'); ?>
                </td>
                <?php if($group=="admin"): ?>
                <td><?php echo $row->zobra_login==''?'#zmazaný':$row->zobra_login.' ('.$row->zobra.')'; ?></td>
                <td><?php echo $row->meno==''?'#zmazaná':$row->meno.' ('.$row->reklama.')'; ?></td>
                <td><?php echo $row->inzer_login==''?'#zmazaný':$row->inzer_login.' ('.$row->inzer.')'; ?></td>
                <td><?php echo $row->path==''?'#zmazaný':substr(basename($row->path),strlen($row->inzer_login)+1).' ('.$row->banner.')'; ?></td>
                <?php endif; if($group=="inzer"): ?>
                <td><?php echo $row->path==''?'#zmazaný':substr(basename($row->path),strlen($user)+1); ?></td>
                <?php endif; if($group=="zobra"): ?>
                <td><?php echo $row->meno==''?'#zmazaná':$row->meno; ?></td>
                <?php endif; ?>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
<?php include 'base/pager.php'; } ?>
<hr>
</div>

</div>
</body>
</html>
