<?php
ob_start();
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <?php
        include("chief.php");
        include("models/department.php");
        $fn = new Chief(); #access to chief functions
        $dep = new Department();
        $fn->is_it_legit(); #Validate the session is established and redirect to login if not
        $fn->is_authorized(); #make sure the user is admin
        $fn->print_head('Directory | New Department');
        ?>
    </head>
    <body>
        <?php
        $fn->is_admin($_SESSION["u_access"]) ? include 'partials/header_admin.php' : include 'partials/header.php'; #Include the header partial on the page
        $fn->show_msgs(); #display messages from updates and inserts, then clear session variables used for messages
        ?>
        <h1 class="theme-color" align="left">New Department </h1>
        <?php
        $department = $dep->_new();
        $department['division_name'] = '';
        include "partials/_department_form.php";
        include 'partials/footer.php'
        ?>
    </body>
</html>
<?php ob_end_flush(); ?>