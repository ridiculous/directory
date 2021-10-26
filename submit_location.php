<?php
session_start();
include("chief.php");
$fn = new Chief(); #Access to the chief functions and db
$bldg = $fn->sql_escape($_POST["building"]);
$location_id = $_POST["location_id"];

#Determine purpose of request ( update or insert )
$action = ($location_id) ? "edit_location.php" : "add_location.php";
$action_msg = ($location_id) ? "updated" : "added";

if ($location_id)
    $location_query = "UPDATE location SET building = '$bldg' WHERE id = $location_id";
else
    $location_query = "INSERT INTO location(building) VALUES('$bldg')";

if (empty($bldg)){
    $_SESSION["db_msg"] = "Building name can't be blank";
    $_SESSION["db_status"] = 0;
    Header('HTTP/1.1 301 Moved Permanently');
    Header('Location:'.$action);
} else {
    $_SESSION["db_msg"] = "Location successfully $action_msg";
    $_SESSION["db_status"] = 1;
    $fn->query($location_query);
    Header('HTTP/1.1 301 Moved Permanently');
    Header('Location:'.$action);
}
?>