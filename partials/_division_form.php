<hr />
<div class="basic-form basic-bg">
    <form action="submit_division.php" method="post">
        <table cellspacing="1" class="nice-table" frame='below' width="100%">
            <tr>
                <th class="t-h"><b> Division Name</b> </th>
            </tr>
            <tr>
                <td align="center">
                    <input type="text" size="50" id="div_name" name="div_name" class="nice-field" value="<?php print @$division['name'] ?>"/>
                </td>

            </tr>
        </table>
        <br />
        <div style="float: left; width: 100%;">
            <?php $fn->savecancel("division", null, 'manage_divisions.php'); ?>
        </div>
        <div class="clear"></div>
        <input type="hidden" name="division_id" value="<?php print @$division['id']; ?>" />
    </form>
    <script type="text/javascript">
        jQuery(function($){
            $(document).ready( function() {
                $("#div_name").focus();
            });
        });
    </script>
</div>