<?php

#Process the "edit_division.php" : "add_division.php" form submissions. Inserts and Updates are handled by this page
session_start();
include("chief.php");
include("models/division.php");
$fn = new Chief(); #access to chief functions
$division = new Division();

$division_name = $fn->sql_escape($_POST["div_name"]);
$division_id = isset($_POST["division_id"]) ? $_POST["division_id"] : false;

$action = ($division_id) ? "edit_division.php" : "add_division.php";
$action_msg = ($division_id) ? "updated" : "added";

$attrs = $division->_new(array('id' => $division_id,'name' => $division_name));

$division_query = $division_id ? $division->_update($attrs) : $division->_save($attrs);

if (empty($division_name)) {
    $_SESSION["db_msg"] = "Fields can't be left blank";
    $_SESSION["db_status"] = 0;
} else {
    if ($fn->query($division_query)) {
        $_SESSION["db_msg"] = "Division successfully $action_msg";
        $_SESSION["db_status"] = 1;
    } else {
        $_SESSION["db_msg"] = "Oops, something went wrong";
        $_SESSION["db_status"] = 0;
    }
}
Header('HTTP/1.1 301 Moved Permanently');
Header('Location:' . $action);
?>