<?php

session_start();
#This page is used for the add_program.php and edit_program.php pages ( Inserts and updates )
include("chief.php");
include("models/program.php");
$fn = new Chief(); #access to chief functions
$pro = new Program();
#Collect data from fields on add_program.php
$pro_name = $fn->sql_escape($_POST["program"]);
$dep = $fn->sql_escape($_POST["dep"]);
$coordinator = $fn->sql_escape($_POST["pro_coord"]);
$mailb = $fn->sql_escape($_POST["mailbox"]);
$program_id = $_POST["program_id"];

#Determine purpose of request ( update or insert )
$action = ($program_id) ? "edit_program.php" : "add_program.php";
$action_msg = ($program_id) ? "updated" : "added";

//Split the contact persons name into first and last name and use that to find the id.
if ($coordinator = trim($coordinator)) {
    list($name_f, $name_l) = split(' ', $coordinator);
    $coord_id = $fn->find_person_id($name_f, $name_l);
}else
    $coordinator = 0;

$attrs = $pro->_new(array(
            'id' => $program_id,
            'pro_name' => $pro_name,
            'mailbox' => $mailb,
            'department_id' => $dep,
            'pro_coordinator' => $coord_id
        ));

#Check if the action is an update or insert
$program_query = $program_id ? $pro->_update($attrs) : $pro->_save($attrs);

if (!$pro_name) {
    $errorm = "<b>Program</b> ";
    $_SESSION["db_msg"] = $errorm ? ($errorm . " can't be blank!") : "Oops, something went wrong";
    $_SESSION["db_status"] = 0;
    Header('HTTP/1.1 301 Moved Permanently');
    Header('Location:' . $action);
} else {
    $_SESSION["db_msg"] = "Program successfully $action_msg";
    $_SESSION["db_status"] = 1;
    $fn->query($program_query);
    Header('HTTP/1.1 301 Moved Permanently');
    Header('Location:' . $action);
}
?>