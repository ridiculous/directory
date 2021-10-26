<?php
ob_start();
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <?php
        include("chief.php");
        include("models/user.php");
        $fn = new Chief(); #access to chief functions
        $u = new Users();
        $fn->is_it_legit(); #Validate the session is established and redirect to login if not
        $fn->print_head('Directory | Edit User');
        ?>
    </head>
    <body>
        <?php
        $fn->is_admin($_SESSION["u_access"]) ? include 'partials/header_admin.php' : include 'partials/header.php';

        $fn->show_msgs();#show db messages and then clear session variables

        $users_id = isset($_POST["users_id"]) ? $fn->sql_escape($_POST["users_id"]) : null;
        if ($users_id != '') {
            $_SESSION["edit_by_uid"] = $users_id;
        }
        if ($users_id == '') {
            $users_id = $_SESSION["edit_by_uid"];
        }

        $user = $u->_find($users_id);#populate $user variable with specific user info
        print '<h1 class="theme-color" align="left">Edit user '.$user['username'].'</h1>
               <div id="password_reset_msg"></div>';
        #include the users form (shared for edit and inserts)
        include "partials/_users_form.php";
        ?>
        <script type="text/javascript" >
            jQuery(function($){
                $(document).ready(function(){
                    $('#pwreset').bind('click',function(){
                        var users_id = $(this).attr("name");
                        var answer = confirm ("Are you sure you want to reset users password?");
                        if (answer){
                            $.get("sarge.php?users="+users_id,function(temp){
                                $('#pwreset').unbind();
					  var e = $.trim($('#user_email').val()), u = $.trim($('#uname').val());

                                window.location = 'http://maui.hawaii.edu/mail_relay/_relay_email.php?_s_=1&d='+e+'&t=reset&p='+temp+'&u='+u;
                            });
                        }
                    });
                });
            });

        </script>
    </body>
    <hr />
    <div>
        <? include 'partials/footer.php'; ?>
    </div>
</html>