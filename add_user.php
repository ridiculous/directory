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
        $fn->is_authorized(); #Make sure user is administrator
        $fn->print_head('Directory | Add User');
        ?>
        <style type='text/css'>
            .clean-gray{
                border:solid 1px #DEDEDE;
                background:#B5B5B5;
                color:#222222;
                padding:4px;
                text-align:center;
            }
        </style>
    </head>
    <body>
        <?php
        #Include the header partial on the page
        $fn->is_admin($_SESSION["u_access"]) ? include 'partials/header_admin.php' : include 'partials/header.php';

        $fn->show_msgs(); #display messages from updates and inserts, then clear session variables used for messages
        $user = $u->_new();
        $user['dep_acronym'] = '';
        $user['program_id'] = '';
        $user['pro_name'] = '';
        ?>
        <h1 class="theme-color" align="left">Create User</h1>
        <?php include "partials/_users_form.php"; ?>

        <script type="text/javascript">
            document.getElementById("uname").focus();
        </script>
        <div>
            <?php include 'partials/footer.php' ?>
        </div>
    </body>
</html>
<?php ob_end_flush(); ?>