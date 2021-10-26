<?php
include "chief.php";
$fn = new Chief();
$statement = "SELECT fax_machine, fax_number FROM fax ORDER BY fax_machine";
$result = $fn->query($statement);
$counter = 0;
# Get the number of rows in the result, as well as the first row
#  and the number of fields in the rows
?>
<?php if ($result) : ?>
    <div style="width:700px;border: 1px solid grey;text-align: center;">
        <table cellspacing="1" id="mainTable" class="nice-table" frame='below' width="100%">
            <tr style="background-color:#BCCDF0;margin-bottom: 5px;">
                <th class="t-h" width="400"><b> Fax Machine</b></th>
                <th class="t-h" width="400"><b> Fax Number</b></th>
            </tr>
            <?php while ($faxmachine = $fn->fetch_hash($result)) : ?>
                <?php $bgcolor = ($counter % 2) == 1 ? "#FFFFFF" : "#E2E0DC"; ?>
                <tr style="background-color: <?php print $bgcolor ?>" <?php print "onmouseover='(this.style.background=\"#AFCBD3\")' onmouseout='(this.style.background=\"$bgcolor\")'"; ?> >
                    <td align="center" width="400">
                        <?php
                        print $faxmachine['fax_machine']; #Fax machine 
                        $counter++;
                        ?>
                    </td>
                    <td align="center" width="400">
                        <?php print $faxmachine['fax_number']; #Fax number ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
<?php endif; ?>

