<?php

session_start();
#Process the add_department.php and edit_department.php pages. Inserts and updates
include("chief.php");
include("models/department.php");
$fn = new Chief(); #access to chief functions
$department = new Department();
#Collect data from fields on add_program.php
$dep_name = $fn->sql_escape($_POST["dep"]);
$acr = $fn->sql_escape($_POST["acr"]);
$chair = $fn->sql_escape($_POST["chair"]);
$division = $fn->sql_escape($_POST["division"]);
$department_id = $_POST["department_id"];

#Determine purpose of request ( update or insert )
$action = ($department_id) ? "edit_department.php" : "add_department.php";
$action_msg = ($department_id) ? "updated" : "added";

if ($chair = trim($chair)) {    
    list($fname, $lname) = split(' ', $chair);
    $chair_id = $fn->find_person_id($fname, $lname);
} else {
    $chair_id = 0;
}

$acr = $acr ? $acr : $dep_name;

$attrs = $department->_new(array(
    'id' => $department_id,
    'dep_name' => $dep_name,
    'dep_acronym' => $acr,
    'dep_chair' => $chair_id,
    'division_id' => $division
));
$department_query = ($department_id) ? $department->_update($attrs) : $department->_save($attrs);

if (!$dep_name || !$acr || !$division) {
    $errorm;
    if (!$dep_name) {
        $errorm = "<b>Department,</b> ";
    }
    if (!$division) {
        $errorm .= "<b>Division</b> ";
    }
    $_SESSION["db_msg"] = $errorm . " can't be blank!";
    $_SESSION["db_status"] = 0;
    Header('HTTP/1.1 301 Moved Permanently');
    Header('Location:'.$action);
} else {
    $_SESSION["db_msg"] = "Department successfully $action_msg";
    $_SESSION["db_status"] = 1;
    $fn->query($department_query);
    Header('HTTP/1.1 301 Moved Permanently');
    Header('Location:'.$action);
}
?>