<?php

#Process the add_fax.php and edit_fax.php form submissions. Inserts and Updates are handled by this page
session_start();
include("chief.php");
include("models/faxclass.php");
$fn = new Chief(); #access to chief functions
$fax = new Fax();

$fax_name = $fn->sql_escape($_POST["fax_machine"]);
$fax_num = $fn->sql_escape($_POST["fax_number"]);
$fax_id = isset($_POST["fax_id"]) ? $_POST["fax_id"] : false;

$action = ($fax_id) ? "edit_fax.php" : "add_fax.php";
$action_msg = ($fax_id) ? "updated" : "added";

$attrs = $fax->_new(array(
    'id' => $fax_id,
    'fax_machine' => $fax_name,
    'fax_number' => $fax_num
        ));

$fax_query = $fax_id ? $fax->_update($attrs) : $fax->_save($attrs);

if (empty($fax_name) || empty($fax_num)) {
    $_SESSION["db_msg"] = "Fields can't be left blank";
    $_SESSION["db_status"] = 0;
} else {
    if ($fn->query($fax_query)) {
        $_SESSION["db_msg"] = "Fax machine successfully $action_msg";
        $_SESSION["db_status"] = 1;
    } else {
        $_SESSION["db_msg"] = "Oops, something went wrong";
        $_SESSION["db_status"] = 0;
    }
}
Header('HTTP/1.1 301 Moved Permanently');
Header('Location:' . $action);
?>