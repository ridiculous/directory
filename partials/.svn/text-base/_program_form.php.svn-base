<hr />
<div class="basic-form basic-bg">
    <div class="asterisk-wrap">
        <span class="asterisk">* Required</span>
    </div>
    <form action="submit_program.php" method="post">
        <?php
        if ($program['id'])
            print "<input type='hidden' name='program_id' value='" . $program['id'] . "' />";
        ?>
        <div>
            <div style="width:700px">
                <table cellspacing="6" style="font-size:20px; width: 100%; color: #006699; padding: 1px;">
                    <tr>
                        <td align="right" class="t-h"><b>Program Name</b> <span class="asterisk">*</span> </td>
                        <td align="left">
                            <?php
                            $pname = $program['pro_name'];
                            $fn->printq("<input type=!<^@^>!text!<^@^>! size=!<^@^>!50!<^@^>! value=!<^@^>!$pname!<^@^>! id =!<^@^>!program!<^@^>! name=!<^@^>!program!<^@^>! class=!<^@^>!nice-field!<^@^>! />");
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td align="right" class="t-h"><b>Department</b></td>
                        <td>
                            <?php
                            include 'models/department.php';
                            $department = new Department();
                            
                            # Write the second drop down menu for selecting the departments.
                            if ($fn->is_admin($_SESSION["u_access"])) {
                                $dep_array = $fn->query($fn->get_depts());
                                $fn->generate_select_menu($dep_array, "dep", $program['department_id'], $program['dep_name']);
                            } else {
                                $dep = $department->_for_select($_SESSION["users_departments"]);
                                $fn->generate_select_menu($dep, "dep", $program['department_id'], $program['dep_name']);
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td align="right" class="t-h"> <b>Program Coordinator</b></td>
                        <td>
                            <input type="text" id="pro_coord" size="30" name="pro_coord" maxlength="200" <?php $fn->printq("value=!<^@^>!" . $program['pro_coordinator'] . "!<^@^>!"); ?> class="nice-field"/>
                        </td>
                    </tr>
                    <tr>
                        <td align="right" class="t-h"><b> Mailbox</b></td>
                        <td>
                            <input type="text" size="15" name="mailbox" maxlength="40" value= '<?php print $program['mailbox']; ?>'   class="nice-field"/>
                        </td>
                    </tr>
                </table>
                <hr/>
                <br />
            </div>
            <div style="float: left; width: 100%;">
                <?php $fn->savecancel("program"); ?>
            </div>
            <div style="clear:both"></div>
        </div>
    </form>
    <script type="text/javascript">
        jQuery(function($){
            $.get("sarge.php?cleared=youkn0wit", function(data){
                data = data.replace("[","");
                data = data.replace("]","");
                names = data.split(",");
                $( "#pro_coord" ).autocomplete({
                    source: names
                });
            });
        });
    </script>
</div>