<?php
ob_start();
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <?php
        include("chief.php");
        include("models/location.php");
        $fn = new Chief(); #access to chief functions
        $l = new Location();
        $fn->is_it_legit(); #Validate the session is established and redirect to login if not
        $fn->print_head('Directory | Edit Location');
        ?>
    </head>
    <body>
        <?php
        $fn->is_admin($_SESSION["u_access"]) ? include 'partials/header_admin.php' : include 'partials/header.php';

        $fn->show_msgs(); #use values from session variables to display update and insert messages, then clear

        $location_id = isset($_POST["location_id"]) ? $_POST["location_id"] : ''; #Get location id
        #If location ID is not found, get it from the session variable
        if ($location_id != '') {
            $_SESSION["location_id"] = $location_id;
        }
        if ($location_id == '') {
            $location_id = $_SESSION["location_id"];
        }
        #Query to find location that will be edited
        $location = $l->_find($location_id);
        ?>
        <h1 class="theme-color" align="left">Edit Location </h1>
        <?php
        include "partials/_location_form.php";
        include 'partials/footer.php';
        ?>
    </body>
</html>
<?php ob_end_flush(); ?>