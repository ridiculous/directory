<hr />
<div class="basic-form basic-bg">
    <div class="clean-gray" >
        <p>
            <b>All fields are required.</b>
            A temporary password will be generated for this user and displayed below. The user will be prompted to change it the first time they sign in.
            You may also change your password at any time with the "Change My Password" link on the top menu. The access level corresponds to a department.</p>
    </div>
    <form action="submit_user.php" method="post">
        <?php
        # assume its an update if users id is present
        if ($user['id'])
            print "<input type='hidden' name='users_id' value='" . $user['id'] . "' />";
        ?>
        <div>
            <div style="width:700px">
                <table cellspacing="6" style="font-size:20px; width: 100%; color: #006699; padding: 1px;">
                    <tr style="font-size:22px;">
                        <th width="190px"></th>
                        <th width="450px"></th>
                    </tr>
                    <tr>
                        <td align="right" class="t-h" valign="top" style="padding-top:23px;"><b>Access level </b><small id="role_name"><?php if($u->Role){print "(".ucwords($u->Role).")";} ?></small></td>
                        <td>
                            <div>
                                <p>

                                    <input <?php print($u->_is_admin() ? 'checked="checked"' : ''); ?> id="_admin" name="select_role" type="radio" value="admin">
                                    <label for="_admin"><b>Administrator</b></label>
                                    &nbsp;|&nbsp;
                                    <input <?php print($u->_is_department() ? 'checked="checked"' : ''); ?> id="_department" name="select_role" type="radio" value="department">
                                    <label for="_department"><b>Department</b></label>
                                    &nbsp;|&nbsp;
                                    <input <?php print($u->_is_division() ? 'checked="checked"' : ''); ?>id="_division" name="select_role" type="radio" value="division">
                                    <label for="_division"><b>Division</b></label>
                                </p>
                            </div>
                            <div class="generic-form select-wrap" style="<?php if($u->_division_or_department()){ print 'display:block;';} ?>">
                                <div class="department-select" style="<?php if($u->_is_department()){ print 'display:block;';} ?>">
                                    <span>Department Access</span>
                                    <?php
                                    $dept_array = $fn->query($fn->get_depts(true)); #true parameter returns departments as acronyms
                                    $fn->generate_select_menu($dept_array, "access_level", $u->Dept_id, $u->Dept_name, 'department_list');
                                    ?>
                                </div>
                                <div class="division-select" style="<?php if($u->_is_division()){print 'display:block;';} ?>">
                                    <span>Division Access</span>
                                    <?php
                                    include('models/division.php');
                                    $division = new Division();
                                    $fn->generate_select_menu($division->_all(), "division", $u->Div_id, $u->Div_name, 'division_list');
                                    ?>
                                </div>
                            </div>
                            <?php
                                    $_role = $u->_is_division() ? 0 : $user['access_level'];
                                    print "<input type='hidden' id='users_role' name='role' value='$_role' />";
                            ?>
                                </td>
                            </tr>
                            <tr>
                                <td align="right" class="t-h"><b>Username</b> <span class="asterisk">*</span> </td>
                                <td>
                                    <input type="text" id = "uname" size="20" maxlength="55" name="username" value= '<?php print $user['username']; ?>' class="nice-field"/>
                                </td>
                            </tr>
                            <tr>
                                <td align="right" class="t-h"><b>First Name</b> <span class="asterisk">*</span> </td>
                                <td>
                                    <input type="text" id = "fname" size="20" maxlength="55" name="first_name" <?php if ($user['first_name'])
                                        print "value= '" . $user['first_name'] . "' "; ?> class="nice-field"/>
                         </td>
                     </tr>
                     <tr>
                         <td align="right" class="t-h"><b>Last Name</b> <span class="asterisk">*</span> </td>
                         <td><input type="text" size="20" maxlength="55" name="last_name" value= '<?php print $user['last_name']; ?>' class="nice-field"/></td>
                     </tr>
                     <tr>
                         <td align="right" class="t-h"><b> Email</b> <span class="asterisk">*</span></td>
                         <td>
                             <input type="text" size="20" id="user_email" name="email" maxlength="75" value= '<?php print $user['email']; ?>'   class="nice-field"/>
                         </td>
                     </tr>
                     <tr>
                         <td align="right" class="t-h"><b> Phone </b></td>
                         <td><input type="text" size="20" maxlength="55" name="phone" value= '<?php print $user['phone']; ?>'  class="nice-field"/></td>
                     </tr>
                     <tr>
                         <td align="right" class="t-h"><b> Position </b></td>
                         <td>
                             <input type="text" size="25" maxlength="55" name="position" value= '<?php print $user['position']; ?>' class="nice-field"/>
                         </td>
                     </tr>
                 </table>
                 <hr/>
                 <br />
             </div>
             <div style="float: left; width: 100%;">
                <?php $fn->savecancel("users", $user['id']); ?>
            </div>
            <div style="clear:both"></div>
        </div>
    </form>
</div>