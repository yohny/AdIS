<table style="width:100%">
    <tr>
        <td>
            od:
        </td>
        <td>
            <select name="odDay">
                <?php
                for ($i = 1; $i < 32; $i++)
                    echo '<option value="' . $i . '"' . ($i == $filter->odDay ? ' selected="selected"' : '') . '>' . $i . '</option>';
                ?>
            </select>
        </td>
        <td>
            <select name="odMonth">
                <?php
                for ($i = 1; $i < 13; $i++)
                    echo '<option value="' . $i . '"' . ($i == $filter->odMonth ? ' selected="selected"' : '') . '>' . $i . '</option>';
                ?>
            </select>
        </td>
        <td>
            <select name="odYear">
                <?php
                for ($i = 2010; $i < date('Y')+1; $i++)
                    echo '<option value="' . $i . '"' . ($i == $filter->odYear ? ' selected="selected"' : '') . '>' . $i . '</option>';
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
                    echo '<option value="' . $i . '"' . ($i == $filter->doDay ? ' selected="selected"' : '') . '>' . $i . '</option>';
                ?>
            </select>
        </td>
        <td>
            <select name="doMonth">
                <?php
                for ($i = 1; $i < 13; $i++)
                    echo '<option value="' . $i . '"' . ($i == $filter->doMonth ? ' selected="selected"' : '') . '>' . $i . '</option>';
                ?>
            </select>
        </td>
        <td>
            <select name="doYear">
                <?php
                for ($i = 2010; $i < date('Y')+1; $i++)
                    echo '<option value="' . $i . '"' . ($i == $filter->doYear ? ' selected="selected"' : '') . '>' . $i . '</option>';
                ?>
            </select>
        </td>
    </tr>
</table>