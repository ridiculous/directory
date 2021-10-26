<?php

//Back end of the Ajax code in the jscript.js file
include("chief.php");
$fn = new Chief();
$users_id = isset($_GET["users"]) ? $fn->sql_escape($_GET["users"]) : '';
//If not empty, execute the reset password statement using the users id
if (!empty($users_id)) {
    $fn->set_password_reset_on($users_id);
    $temp = $fn->createRandomPassword();
    $encry_temp = $fn->encrypt_pw($temp);
    $update_users_pw = "UPDATE passwords SET pw_encrypted = '$encry_temp' WHERE users_id = '$users_id'";
    $fn->query($update_users_pw);
    print $temp;
}
$p_id = isset($_GET["persons_id"]) ? $fn->sql_escape($_GET["persons_id"]) : '';
//If not empty, execute the delete statement on people table
if ($p_id) {
    $delete_statement = "DELETE FROM people WHERE id = '$p_id'";
    $fn->query($delete_statement);
    $person_name = $fn->find_person_fullname($p_id);
    if ($person_name)
        print $person_name;
}
$delete_user_id = isset($_GET["delete_user"]) ? $fn->sql_escape($_GET["delete_user"]) : '';
if (!empty($delete_user_id)) {
    $delete_user_pw = "DELETE FROM passwords WHERE users_id = '$delete_user_id'";
    $delete_user_stat = "DELETE FROM users WHERE id = '$delete_user_id'";
    $fn->query($delete_user_pw);
    $fn->query($delete_user_stat);
}
$persons_name = isset($_GET["person_name"]) ? $fn->sql_escape($_GET["person_name"]) : '';
if (!empty($persons_name)) {
    list($name_f, $name_l) = split(' ', $persons_name);
    if (strpbrk($persons_name, ' ') == false) {
        if (strlen($persons_name) == 1) {
            $find_persons = "SELECT " . $fn->get_concat_person() . " as fullname FROM people p WHERE substring(p.first_name,1,1) = '$persons_name'";
        } else {
            $find_persons = "SELECT " . $fn->get_concat_person() . " as fullname FROM people p WHERE p.first_name like '$persons_name%'";
        }
    } else {
        if (empty($name_l)) {
            $find_persons = "SELECT " . $fn->get_concat_person() . " as fullname FROM people p WHERE p.first_name like '%$persons_name%'";
        } else {
            $find_persons = "SELECT " . $fn->get_concat_person() . " as fullname FROM people p WHERE p.first_name like '%$name_f%' and p.last_name like '%$name_l%'";
        }
    }
    $results = $fn->nifty_query($find_persons);
    print $results[0];
}
$location_id = isset($_GET["delete_location"]) ? $fn->sql_escape($_GET["delete_location"]) : '';
//If not empty, execute the delete statement on people table
if ($location_id) {
    $delete_statement = "DELETE FROM location WHERE id = '$location_id'";
    if (!$fn->query($delete_statement))
        print "Oops, location failed delete";
}
#block of code for deleting the program request sent by the admin.php deleteProgram function
$program_id = isset($_GET["delete_program"]) ? $fn->sql_escape($_GET["delete_program"]) : '';
if ($program_id) {
    $delete_statement = "DELETE FROM program WHERE id = '$program_id'";
    $fn->query($delete_statement);
    $p = $fn->find_program_name($program_id);
    if ($p) {
        print $p;
    }
}
$dept_id = isset($_GET["delete_dept"]) ? $fn->sql_escape($_GET["delete_dept"]) : '';
if ($dept_id) {
    $del = "DELETE FROM department WHERE id = '$dept_id'";
    if (!$fn->query($del))
        print "Failed";
}
$fax_id = isset($_GET["delete_fax"]) ? $fn->sql_escape($_GET["delete_fax"]) : '';
if ($fax_id) {
    $del = "DELETE FROM fax WHERE id = '$fax_id'";
    if (!$fn->query($del))
        print "Failed";
}

$division_id = isset($_GET["delete_division"]) ? $fn->sql_escape($_GET["delete_division"]) : '';
if ($division_id) {
    $_check = $fn->nifty_query("SELECT id FROM department WHERE division_id = $division_id");
    if (!empty($_check)) {
        print 'Failed';
    } else {
        $s = "DELETE FROM division WHERE id = $division_id";
        if (!$fn->query($s))
            print "Failed";
    }
}

//This block of code is only called from the add_person.php page and is the backend to the
//primative Ajax call for finding the contact_person id.
$contact_name = isset($_GET["contact_name"]) ? $fn->sql_escape($_GET["contact_name"]) : '';
if (!empty($contact_name)) {
    list($name_f, $name_l) = split(' ', $contact_name);

    if (strpbrk($contact_name, ' ') == false) {
        if (strlen($contact_name) == 1) {
            $find_persons = "SELECT " . $fn->get_concat_person() . " as fullname FROM people p WHERE substring(p.first_name,1,1) = '$contact_name'";
        } else {
            $find_persons = "SELECT " . $fn->get_concat_person() . " as fullname FROM people p WHERE p.first_name like '$contact_name%'";
        }
    } else {
        if (empty($name_l)) {
            $find_persons = "SELECT " . $fn->get_concat_person() . " as fullname FROM people p WHERE p.first_name like '%$contact_name%'";
        } else {
            $find_persons = "SELECT " . $fn->get_concat_person() . " as fullname FROM people p WHERE p.first_name like '%$name_f%' and p.last_name like '%$name_l%'";
        }
    }
    $results = $fn->nifty_query($find_persons);
    $person = $results[0];
    if (!empty($person))
        print $person;
    else {
        $altern = "SELECT person FROM ajax_person WHERE person like '%$contact_name%'";
        $alt_results = $fn->nifty_query($altern);
        print $alt_results[0];
    }
}
//this code block is almost identical to the uppe one. This is the backend to the call from edit_person
//decided to split it into two incase the other one is being calleda t the same time
$con_fullname = isset($_GET["contact_first_last"]) ? $fn->sql_escape($_GET["contact_first_last"]) : '';
if (!empty($con_fullname)) {
    list($firsname, $lasname) = split(' ', $con_fullname);

    if (strpbrk($con_fullname, ' ') == false) {
        if (strlen($con_fullname) == 1) {
            $query_p = "SELECT " . $fn->get_concat_person() . " as fullname FROM people p WHERE substring(p.first_name,1,1) = '$con_fullname'";
        } else {
            $query_p = "SELECT " . $fn->get_concat_person() . " as fullname FROM people p WHERE p.first_name like '$con_fullname%'";
        }
    } else {
        if (empty($lasname)) {
            $query_p = "SELECT " . $fn->get_concat_person() . " as fullname FROM people p WHERE p.first_name like '%$con_fullname%'";
        } else {
            $query_p = "SELECT " . $fn->get_concat_person() . " as fullname FROM people p WHERE p.first_name like '%$firsname%' and p.last_name like '%$lasname%'";
        }
    }
    $p = $fn->nifty_query($query_p);
    if (!empty($p[0]))
        print $p[0];
    else {
        $alternate = $fn->nifty_query("SELECT person FROM ajax_person WHERE person like '%$con_fullname%'");
        print $alternate[0];
    }
}

$go = isset($_GET["cleared"]) ? $fn->sql_escape($_GET["cleared"]) : '';
if ($go == "youkn0wit") {
    $find_persons = "SELECT " . $fn->get_concat_person() . " as full_name FROM people p";
    $results = $fn->query($find_persons);
    print "[";
    while ($row = $fn->fetch($results)) {
        print ($row[0] . ",");
    }
    print "]";
}
?>
