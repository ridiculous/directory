<hr />
<div class="basic-form basic-bg">
    <div class="asterisk-wrap" style="text-align:left;padding-left: 5px;">
        <span class="asterisk">* Required</span>
    </div>
    <form action="submit_department.php" method="post">
        <?php
        include('models/division.php');   
        $division = new Division();
        if ($department['id'])
            print "<input type='hidden' name='department_id' value='" . $department['id'] . "' />";
        ?>
        <table cellspacing="1" class="nice-table" frame='below' width="100%">
            <tr>
                <th class="t-h"><b> Department Name</b> <span class="asterisk">*</span></th>
                <th class="t-h"><b> Acronym</b></th>
                <th class="t-h"><b> Department Chair</b> </th>
                <th class="t-h"><b> Division</b></th>
            </tr>
            <tr>
                <td align="center">
                    <?php
                    $dname = $department['dep_name'];
                    $fn->printq("<input type=!<^@^>!text!<^@^>! size=!<^@^>!35!<^@^>! id=!<^@^>!dep!<^@^>! name=!<^@^>!dep!<^@^>! value=!<^@^>!$dname!<^@^>! class=!<^@^>!nice-field!<^@^>!/>");
                    ?>
                </td>
                <td align="center">
                    <?php
                    $dacr = $department['dep_acronym'];
                    $fn->printq("<input type=!<^@^>!text!<^@^>! size=!<^@^>!15!<^@^>! id=!<^@^>!acr!<^@^>! name=!<^@^>!acr!<^@^>! value=!<^@^>!$dacr!<^@^>! class=!<^@^>!nice-field!<^@^>!/>");
                    ?>
                </td>
                <td align="center">
                    <?php
                    $dchair = $department['dep_chair'];
                    $fn->printq("<input type=!<^@^>!text!<^@^>! size=!<^@^>!30!<^@^>! id=!<^@^>!chair!<^@^>! name=!<^@^>!chair!<^@^>! value=!<^@^>!$dchair!<^@^>! class=!<^@^>!nice-field!<^@^>!/>");
                    ?>
                </td>
                <td align="center">
                    <?php
                    $fn->generate_select_menu($division->_all(), "division", $department['division_id'], $department['division_name']);
                    ?>
                </td>
            </tr>
        </table>
        <br />
        <div style="float: left; width: 100%;">
            <?php $fn->savecancel("department"); ?>
        </div>
        <div style="clear:both"></div>
    </form>
    <script type="text/javascript">
        jQuery(function($){
            $('#dep').focus();
            $.get("sarge.php?cleared=youkn0wit", function(data){
                data = data.replace("[","");
                data = data.replace("]","");
                names = data.split(",");
                $( "#chair" ).autocomplete({
                    source: names
                });
            });
        });
    </script>
</div>