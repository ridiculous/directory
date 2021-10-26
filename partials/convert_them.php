<?php

session_start();
include("chief.php");
$ds = connect_to_directory_db();

$q = "SELECT id, pw_encrypted FROM passwords";

$results = sqlsrv_query($ds, $q, array(), array("Scrollable" => 'keyset'));
$row = sqlsrv_fetch_array($results, SQLSRV_FETCH_ASSOC);
$num_rows = count($row);
if ($_GET["do"]) {
    while ($row) {
        $row = array_values($row);
        for ($index = 0; $index < $num_rows; $index++) {
            if ($index == 0) {
                $id = $row[$index];
                print $id . "<br />";
            } elseif ($index == 1) {
                $pw = $row[$index];
                print $pw . "<br />";
            }
        }
        $new_q = "UPDATE passwords SET pw_encrypted = '" . md5($pw) . "' WHERE id =" . $id;
        sqlsrv_query($ds, $new_q);
        $row = sqlsrv_fetch_array($results, SQLSRV_FETCH_ASSOC);
    }
    print "FINISHED!";
} elseif ($_GET["dont"]) {
    while ($row) {
        $row = array_values($row);
        for ($index = 0; $index < $num_rows; $index++) {
            if ($index == 0) {
                $id = $row[$index];
                print $id . "<br />";
            } elseif ($index == 1) {
                $pw = $row[$index];
                print $pw . "<br />";
            }
        }
        $row = sqlsrv_fetch_array($results, SQLSRV_FETCH_ASSOC);
    }
}
?>
