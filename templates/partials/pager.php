<?php
/**
 * pager potrebuje predtym ako je pouzity mat nastavene tieto premenne:
 *
 * @param $pages celkovy pocet stran
 * @param $aktPage aktualna strana
 */

if( isset($pages) && isset($aktPage) && $pages>1 ):?>
<table class="pager">
    <tr>
        <?php if($aktPage!=1): ?>
        <td onclick="setPage(1);" title="Prvá strana" style="cursor:pointer;font-size:16px;">
            &lt;&lt;
        </td>
        <td onclick="setPage(<?php echo $aktPage-1; ?>);" title="Predchádzajúca strana" style="cursor:pointer;font-size:16px;" class="dark">
            &lt;
        </td>
        <?php endif; ?>
        <td>
            <select onchange="setPage(this.value)">
                <?php for($p=1;$p<=$pages;$p++): ?>
                <option value="<?php echo $p; ?>" <?php if($aktPage==$p) echo 'selected="selected"'; ?>><?php echo $p; ?></option>
                <?php endfor; ?>
            </select> / <?php echo $pages; ?>
        </td>
        <?php if($aktPage!=$pages): ?>
        <td onclick="setPage(<?php echo $aktPage+1; ?>);" title="Nasledujúca strana" style="cursor:pointer;font-size:16px;" class="dark">
            &gt;
        </td>
        <td onclick="setPage(<?php echo $pages; ?>);" title="Posledná strana" style="cursor:pointer;font-size:16px;">
            &gt;&gt;
        </td>
        <?php endif; ?>
    </tr>
</table>
<?php else: ?>
<b>PAGER: chybaju premenne</b>
<?php endif;?>
