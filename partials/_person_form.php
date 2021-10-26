<hr />
<div class="basic-form basic-bg">
    <div class="asterisk-wrap">
        <span class="asterisk">* Required</span>
    </div>
    <form action="submit_person.php" method="post">
        <?php if (isset($person['id']))
            print "<input type='hidden' name='person_id' value='" . $person['id'] . "' />"; ?>
        <div>
            <div style="float: left; width: 50%;">
                <table cellspacing="6" style="font-size:20px; width: 100%; color: #006699; padding: 1px;">
                    <tr style="font-size:22px;">
                        <th width="30%"></th>
                        <th width="70%"></th>
                    </tr>
                    <tr>
                        <td width="30%" align="right" class="t-h">
                            <div style="width:160px;">
                                <b>First Name</b> <span class="asterisk">*</span> 
                            </div>
                        </td>
                        <td>
                            <input type="text" id = "fname" size="20" maxlength="55" name="first_name"
                            <?php if ($person['first_name'])
                                $fn->printq("value= !<^@^>!" . $person['first_name'] . "!<^@^>! "); ?>
                                   class="nice-field"/>
                        </td>
                    </tr>
                    <tr>
                        <td align="right" class="t-h"><b>Last Name</b> <span class="asterisk">*</span> </td>
                        <td><input type="text" size="20" maxlength="55" name="last_name" <?php $fn->printq("value= !<^@^>!" . $person['last_name'] . "!<^@^>! "); ?> class="nice-field"/></td>
                    </tr>
                    <tr>
                        <td align="right" class="t-h"> <b>M.I.</b></td>
                        <td><input type="text" id = "mis" size="4" name="mi" maxlength="1" value= '<?php print $person['middle_init']; ?>' class="nice-field"/></td>
                    </tr>
                    <tr>
                        <td align="right" class="t-h"><b> Email</b> <span class="asterisk">*</span></td>
                        <td>
                            <input type="text" size="20" name="email" maxlength="75" value= '<?php print $person['email']; ?>'   class="nice-field"/>
                        </td>
                    </tr>
                    <tr>
                        <td align="right" class="t-h"><b> Phone </b></td>
                        <td><input type="text" size="20" maxlength="55" name="phone" value= '<?php print $person['phone_number']; ?>'  class="nice-field"/></td>
                    </tr>
                    <tr>
                        <td align="right" class="t-h"><b> Mailbox </b></td>
                        <td><input type="text" size="10" maxlength="20" name="mail" value= '<?php print $person['mailbox']; ?>' class="nice-field"/></td>

                    </tr>
                    <tr>
                        <td align="right" class="t-h"><b>Select a Building</b></td>
                        <td>
                            <?php
                            # Write the second drop down menu, this time for selecting the building name.
                            $bldg_array = $fn->query($fn->get_buildings());
                            $fn->generate_select_menu($bldg_array, "location", $person['location_id'], $person['location_name']);
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td align="right" class="t-h"><b>Room Number </b></td>
                        <td><input type="text" size="10" maxlength="30" name="room_number" value= '<?php print $person['room_number']; ?>'  class="nice-field"/></td>
                    </tr>
                    <tr>
                        <td align="right" class="t-h"><b> Position </b></td>
                        <td>
                            <input type="text" size="25" maxlength="55" name="position" value= '<?php print $person['position']; ?>' class="nice-field"/>
                            <input type="checkbox" name="perm" value="yes" <?php $person['permanent_position'] == 1 ? print "checked"  : print ""; ?> />
                            <span style="font-size: 14px; text-align: left;"> Permanent</span>
                        </td>
                    </tr>

                    <tr>
                        <td align="right" class="t-h"><b>Website </b><small>(optional)</small></td>
                        <td><input type="text" size="25" maxlength="75" name="website" value= '<?php print $person['website']; ?>'  class="nice-field"/></td>
                    </tr>
                    <tr>
                        <td align="right" class="t-h"><b> Contact Person </b></td>
                        <td><input type="text" size="25" maxlength="60" name="contact" id="contact" value= '<?php print $person['contact']; ?>'  class="nice-field"/></td>
                    </tr>
                    <tr>
                        <td align="right" class="t-h"><span id="program_label" style="font-weight:bold;"> Program </span></td>
                        <td style="padding-top:10px;">
                            <div>
                                <?php
                                $program_array = $fn->query($fn->get_programs());
                                $fn->generate_select_menu($program_array, "program", $person['program_id'], $person['program_name'], 'program_list');
                                ?>
                            </div>
                            <div>
                                <div style="padding-left:3px;">
                                    <a href="javascript:void(0);" id="_department" class="basic clickhere">They don't have a Program?</a>
                                </div>
                                <div id="only_department">
                                    <?php
                                    include 'models/department.php';
                                    $department = new Department();
                                    # Write the second drop down menu for selecting the departments.
                                    if ($fn->is_admin($_SESSION["u_access"])) {
                                        $dep = $person['department_id'];
                                        $dep_array = $fn->query($fn->get_depts());
                                        $fn->generate_select_menu($dep_array, "dep", $person['department_id'], $person['dep_name'], 'department_list');
                                    } else {
                                        $dep = $_SESSION["u_access"];
                                        $users_depts = $department->_for_select($_SESSION["users_departments"]);
                                        $fn->generate_select_menu($users_depts, "dep", $person['department_id'], $person['dep_name'], 'department_list');
                                    }
                                    if ($person['department_id'] != 0) {
                                        print "<input type='hidden' id='only_department_selected' name='department' value='$dep' />";
                                    } else {
                                        print "<input type='hidden' id='only_department_selected' name='department' value='0' />";
                                    }
                                    ?>
                                    <div style="padding-left:3px;">
                                        <a href="javascript:void(0);" class="basic" id="_cancel">They have a Program?</a>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>
                <hr/>
                <br />
            </div>
            <div style="float: left; width: 50%; height: 80%; text-align: left;">
                <div style="position: relative; left: 2%; bottom: 2%;">
                    <br/>
                    <span class="theme-color" style="font-size: 20px;"><b>Notes:</b></span><br/>
                    <?php
                    print "<textarea name='notes' cols='50' rows='8'>" . $person['comments'] . "</textarea>";
                    ?>
                    <br/>
                </div>
            </div>
        </div>
        <!-- Add in the save and cancel buttons. Stored externally for easy changing -->
        <div style="float: left; width: 100%;">
            <?php $fn->savecancel("person"); ?>
        </div>
        <div style="clear:both"></div>
    </form>
    <script type="text/javascript">
        jQuery(function($){
            $.get("sarge.php?cleared=youkn0wit", function(data){
                data = data.replace("[","");
                data = data.replace("]","");
                names = data.split(",");
                $( "#contact" ).autocomplete({
                    source: names
                });
            });
        });
    </script>
</div>