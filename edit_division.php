<?php
ob_start();
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <?php
        include("chief.php");
        include("models/division.php");
        $fn = new Chief(); #access to chief functions
        $div = new Division();
        $fn->is_it_legit(); #Validate the session is established and redirect to login if not
        $fn->is_authorized(); #make sure the user is admin
        $fn->print_head('Directory | Edit Division');
        ?>
    </head>
    <body>
        <?php
        #include head partial based on access level
        $fn->is_admin($_SESSION["u_access"]) ? include 'partials/header_admin.php' : include 'partials/header.php';

        $fn->show_msgs(); #use values from session variables to display update and insert messages, then clear

        $id = isset($_GET["id"]) ? $_GET["id"] : $_SESSION["division_id"];
        if (!empty($id) && is_numeric($id)) {
            $_SESSION["division_id"] = $id;
            $division = $div->_find($id);
        }
        ?>
        <h1 class="theme-color" align="left">Edit Division </h1>
        <div>
            <?php
            include "partials/_division_form.php";
            ?>
        </div>
        <div>
            <?php include 'partials/footer.php'; ?>
        </div>
    </body>
</html>