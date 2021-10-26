<?php
ob_start();
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <?php
        include("chief.php");
        include('models/people.php');
        $fn = new Chief(); #access to chief functions
        $person = new People(); #person object
        
        $fn->print_head("Directory | Edit Person"); #Print title, meta, js, and style tags
        $fn->is_it_legit(); #Validate the session is established and redirect to login if not
        ?>
    </head>
    <body>
        <?php
        #include partil based on access level of user
        $fn->is_admin($_SESSION["u_access"]) ? include 'partials/header_admin.php' : include 'partials/header.php';
        $fn->show_msgs(); #use values from session variables to display update and insert messages, then clear
        $p_id = isset($_POST["person_id"]) ? $fn->sql_escape($_POST["person_id"]) : '';
        if ($p_id != '') {
            $_SESSION["p_id"] = $p_id;
        }
        if ($p_id == '') {
            $p_id = $_SESSION["p_id"];
        }
        $contact_id = $fn->nifty_query("SELECT contact_id FROM people WHERE id = '$p_id'");

        $person = $person->_find($contact_id[0], $p_id);
        $department_id = $person['department_id'];
        $deps = $fn->nifty_query("SELECT dep_name FROM department WHERE id = '$department_id'");
        $person['dep_name'] = $deps[0];

        print '<h1 class="theme-color" align="left">Edit ' . $person['first_name'] . '\'s record</h1>';

        # Query for the building drop down box
        $result_bldg = $fn->query("SELECT id, Building FROM location ORDER BY building");

        include "partials/_person_form.php";
        ?>
        <div>
            <?php include 'partials/footer.php'; ?>
        </div>
    </body>
</html>
<?php ob_end_flush(); ?>