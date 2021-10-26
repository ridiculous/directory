<hr />
<div class="basic-form basic-bg">
    <div class="asterisk-wrap" style="text-align:left;padding-left: 5px;">
        <span class="asterisk">All fields required</span>
    </div>
    <form action="submit_location.php" method="post">
        <?php
        if ($location['id'])
            print "<input type='hidden' name='location_id' value='" . $location['id'] . "' />";
        ?>
        <div>
            <table cellspacing="1" class="nice-table" frame='below' width="100%">
                <tr>
                    <th class="t-h"><b>Building Name</b></th>
                </tr>
                <tr>
                    <td align="center">
                        <input type="text" size="30" id="bldg" value="<?php print $location['building'] ?>" name="building" class="nice-field"/>
                    </td>
                </tr>
            </table>
            <br />
            <div style="float: left; width: 100%;">
                <?php $fn->savecancel("location"); ?>
            </div>
            <div style="clear:both"></div>
        </div>
    </form>
    <script type="text/javascript">
        jQuery(function($) {
            $(document).ready( function() {
                $('#bldg').focus();
            });
        });
    </script>
</div>