<?php
ob_start();
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <?php
        include("chief.php");
        include("models/program.php");
        $fn = new Chief(); #access to chief functions
        $pro = new Program();
        $fn->is_it_legit(); #Validate the session is established and redirect to login if not
        $fn->print_head('Directory | New Program');
        ?>
    </head>
    <body>
        <?php
        #Include the header partial on the page
        $fn->is_admin($_SESSION["u_access"]) ? include 'partials/header_admin.php' : include 'partials/header.php';

        $fn->show_msgs(); #display messages from updates and inserts, then clear session variables used for messages
        ?>
        <h1 class="theme-color" align="left">New Program </h1>
        <?php
        $program = $pro->_new();
        $program['dep_name'] = '';
        include "partials/_program_form.php";
        ?>
        <div>
            <?php include 'partials/footer.php' ?>
        </div>
    </body>
</html>
<?php ob_end_flush(); ?>