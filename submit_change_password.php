<?php

session_start();
include("chief.php");
$fn = new Chief();

$current = $fn->sql_escape($_POST["current"]);
$current_encrypted = $fn->encrypt_pw($current);
$new = $fn->sql_escape($_POST["new"]);
$confirm = $fn->sql_escape($_POST["confirm"]);
$user_id = $_SESSION["u_id"];
$password_id = $fn->find_password_id($current_encrypted);
if ($password_id != '') {
    if (strcmp(trim($new), trim($confirm)) == 0 && !empty($new) && !empty($confirm)) {
        $new_pw = $fn->encrypt_pw($new);
        $fn->change_password($password_id, $new_pw);
        $_SESSION["db_msg"] = "Password successfully changed";
        $_SESSION["db_status"] = 1;
        $fn->set_password_reset_off($user_id);
        header('Location:change_password.php');
        exit;
    } else {
        $_SESSION["db_msg"] = "The two new passwords do not match! Please try again";
        $_SESSION["db_status"] = 0;
        header('Location:change_password.php');
        exit;
    }
} else {
    $_SESSION["db_msg"] = "Incorrect current password. Please check your spelling";
    $_SESSION["db_status"] = 0;
    header('Location:change_password.php');
    exit;
}
?>