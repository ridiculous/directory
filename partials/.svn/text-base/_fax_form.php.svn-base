<hr />
<div class="basic-form basic-bg">
    <form action="submit_fax.php" method="post">
        <table cellspacing="1" class="nice-table" frame='below' width="100%">
            <tr>
                <th class="t-h"><b> Fax Machine</b> </th>
                <th class="t-h"><b> Fax Number</b></th>
            </tr>
            <tr>
                <td align="center">
                    <input type="text" size="50" id="fax_machine" name="fax_machine" class="nice-field" value="<?php print @$faxmachine['fax_machine'] ?>"/>
                </td>
                <td align="center">
                    <input type="text" size="15" name="fax_number" class="nice-field" value="<?php print @$faxmachine['fax_number'] ?>"/>
                </td>
            </tr>
        </table>
        <br />
        <div style="float: left; width: 100%;">
            <?php $fn->savecancel("fax", null, 'fax.php'); ?>
        </div>
        <div class="clear"></div>
        <input type="hidden" name="fax_id" value="<?php print @$faxmachine['id']; ?>" />
    </form>
    <script type="text/javascript">
        jQuery(function($){
            $(document).ready( function() {
                $("#fax_machine").focus();
            });
        });
    </script>
</div>