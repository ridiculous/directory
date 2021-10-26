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
        $fn->print_head('Directory | Edit Program'); #meta, link, script tags
        ?>
    </head>
    <body>
        <?php
        #include header partial based on access level
        $fn->is_admin($_SESSION["u_access"]) ? include 'partials/header_admin.php' : include 'partials/header.php';

        $fn->show_msgs(); #use values from session variables to display update and insert messages, then clear

        $pro_id = isset($_POST["pro_id"]) ? $fn->sql_escape($_POST["pro_id"]) : false;
        if ($pro_id) {
            $_SESSION["pro_id"] = $pro_id;
        }
        if ( ! $pro_id ) {
            $pro_id = $_SESSION["pro_id"];
        }
        $coord_id = $fn->nifty_query("SELECT pro_coordinator FROM program WHERE id = '$pro_id'");

        $program = $pro->_find($coord_id[0], $pro_id);
        ?>
        <h1 class="theme-color" align="left">Edit Program </h1>
        <?php include "partials/_program_form.php"; ?>
        <div>
            <?php include 'partials/footer.php'; ?>
        </div>
    </body>
</html>