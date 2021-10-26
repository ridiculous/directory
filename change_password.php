<?php
ob_start();
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <?php
        include("chief.php"); #Include chief functions
        $fn = new Chief(); #Access to chief functions
        $fn->is_legit_password_change(); ##Authorize page and/or redirect user
        if (isset($_SESSION["db_status"]) && $_SESSION["db_status"] == 1) {
            print '<meta http-equiv="refresh" content="2;URL=admin.php" />';
        }
        $fn->print_head('Directory | Change Password');
        ?>
        <style type="text/css">
            .heads td {font-size: 16px; font-family: arial; color: #145260;}
        </style>
    </head>
    <body>
        <div>
            <a href="http://maui.hawaii.edu"><img src="mauiCC_identity.gif" alt="Home" id="logo-image" border="0"/></a>
        </div>
        <?php
        $user_id = $_SESSION["u_id"];
        $fn->show_msgs(); #display messages from updates and inserts, then clear session variables used for messages
        ?>
        <div class="content" align = "center"><hr /> </div>
        <div id="change_pw_container" class="basic-form basic-bg" style="margin-left:auto;margin-right: auto;width:85%;padding: 0 19px 19px 19px">
            <form action="submit_change_password.php" method="post" >
                <h1 class="theme-color" align="left" style="text-decoration: underline;">Change Password<span style="font-size:26px;font-style: italic;text-decoration: none;">&nbsp;(<?php print $_SESSION["user_username"]; ?>)</span></h1>
                <div>
                    <table class="heads" cellspacing="4">
                        <tr>
                            <th></th>
                            <th></th>
                        </tr>
                        <tr>
                            <td>
                                Current Password
                            </td>
                            <td>
                                <input type="text" size="15px" name="current" id="current" class="nice-field" autocomplete="off"/>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2"><hr/></td>
                        </tr>
                        <tr>
                            <td>
                                New Password
                            </td>
                            <td>
                                <input type="password" size="15px" name="new" class="nice-field"/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Retype New Password
                            </td>
                            <td>
                                <input type="password" size="15px" name="confirm" class="nice-field"/>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2"><hr/></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <?php
                                print '<input type="submit" value ="Confirm New" class="btn success" />';
                                if (!$fn->is_password_reset($_SESSION["u_id"])) {
                                    print "&nbsp;&nbsp;or&nbsp;&nbsp;<a href='admin.php' class='btn primary'>Home</a>";
                                }
                                ?>
                            </td>
                        </tr>

                    </table>
                </div>
            </form>
        </div>
        <script type="text/javascript" >
            $("#current").focus();
        </script>
    </body>
</html>
<?php ob_end_flush(); ?>