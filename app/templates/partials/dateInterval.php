<?php
/**
 * vizualny komponent reprezentujuci volic intervalu datumov
 *
 * pager potrebuje predtym ako je pouzity mat nastavene tieto premenne:
 * @param Filter $filter instancia filtra
 *
 * @version    1.0
 * @package    AdIS
 * @subpackage templates
 * @author     Ján Neščivera <jan.nescivera@gmail.com>
 *
 * @todo turn into object
 */

if(!isset($filter) || !($filter instanceof Filter))
{
    echo "<b>DATEINTERVAL: nenastavene premenne</b>";
    return;
}
?>
<table style="width:100%">
    <tr>
        <td>
            od:
        </td>
        <td>
            <select name="odDay">
                <?php
                for ($i = 1; $i < 32; $i++)
                    echo '<option value="' . $i . '"' . ($i == $filter->from->format('j') ? ' selected="selected"' : '') . '>' . $i . '</option>';
                ?>
            </select>
        </td>
        <td>
            <select name="odMonth">
                <?php
                for ($i = 1; $i < 13; $i++)
                    echo '<option value="' . $i . '"' . ($i == $filter->from->format('n') ? ' selected="selected"' : '') . '>' . $i . '</option>';
                ?>
            </select>
        </td>
        <td>
            <select name="odYear">
                <?php
                for ($i = 2010; $i < date('Y')+1; $i++)
                    echo '<option value="' . $i . '"' . ($i == $filter->from->format('Y') ? ' selected="selected"' : '') . '>' . $i . '</option>';
                ?>
            </select>
        </td>
    </tr>
    <tr>
        <td>
            do:
        </td>
        <td>
            <select name="doDay">
                <?php
                for ($i = 1; $i < 32; $i++)
                    echo '<option value="' . $i . '"' . ($i == $filter->to->format('j')? ' selected="selected"' : '') . '>' . $i . '</option>';
                ?>
            </select>
        </td>
        <td>
            <select name="doMonth">
                <?php
                for ($i = 1; $i < 13; $i++)
                    echo '<option value="' . $i . '"' . ($i == $filter->to->format('n') ? ' selected="selected"' : '') . '>' . $i . '</option>';
                ?>
            </select>
        </td>
        <td>
            <select name="doYear">
                <?php
                for ($i = 2010; $i < date('Y')+1; $i++)
                    echo '<option value="' . $i . '"' . ($i == $filter->to->format('Y') ? ' selected="selected"' : '') . '>' . $i . '</option>';
                ?>
            </select>
        </td>
    </tr>
</table>