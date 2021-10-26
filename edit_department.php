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
        $fn->print_head('Directory | Edit Department');#title, meta, script, link tags
        ?>
    </head>
    <body>
        <?php
        $fn->is_admin($_SESSION["u_access"]) ? include 'partials/header_admin.php' : include 'partials/header.php';

        $fn->show_msgs(); #use values from session variables to display update and insert messages, then clear
        
        $department_id = isset($_POST["department_id"]) ? $fn->sql_escape($_POST["department_id"]) : '';
        if ($department_id != '') {
            $_SESSION["dep_id"] = $department_id;
        }
        if ($department_id == '') {
            $department_id = $_SESSION["dep_id"];
        }
        $_dep = $fn->nifty_query("SELECT dep_chair, division_id FROM department WHERE id = '$department_id'");
        $department = $dep->_find($department_id,$_dep[0],$_dep[1]);
        ?>
        <h1 class="theme-color" align="left">Edit Department </h1>
        <?php include "partials/_department_form.php"; ?>
        
        <script type="text/javascript" src="jscript.js"></script>
    </body>
    <br />
    <br />
    <hr />
    <?php include 'partials/footer.php'; ?>
</html>