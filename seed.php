<?php

/*
 * Seed file is used for creating initial database records such as a user account
 * Also used for creating and dropping views used by app
 *
 * Following commands can be passed in ( seed.php?cmd=value ):
 * cmd              value
 * create_view    : "all"           => creates all of the views in db.php
 *                : <name of view>  => specify a view name to create
 *
 * drop_view      : "all"           => drops all views
 *                : <name of view>  => specify name of view to drop
 *
 * seed           :  1              => Create initial set of records w/ data below
 */

require_once "chief.php";

#Person and user data
$fname = "Super";
$lname = "User";
$phone = "984-3500";
$email = "uhmchelp@hawaii.edu";
$usn = "sudo";
$password = "access";
$person_id = 1;
$accesslvl_id = 24;
$room = "202";
$location_id = 1;
$program_id = 1;
$user_id = 1;
#program data
$pro_name = "Applied Business and Information Technology";
$coord_id = 1;
$dep = 1;
#department data
$dep_name = "Admin";
$acr = "admin";
$chair_id = 1;
$division = "Administrative Affairs";
#location data
$bldg = "Kaaike";
$mgmt_id = 1;

$fn = new Chief();
$seed = isset($_GET["seed"]) ? $_GET["seed"] : 0;
$create = isset($_GET["create_view"]) ? $_GET["create_view"] : false;
$drop = isset($_GET["drop_view"]) ? $_GET["drop_view"] : false;

if ($seed || $create || $drop) {

    if ($seed && $seed == 1) {
        $seed_queries = array(
            "INSERT INTO people(first_name, last_name,phone_number,email,room_number,location_id, program_id) VALUES('$fname','$lname','$phone','$email','$room','$location_id','$program_id')",
            "INSERT INTO users(first_name,last_name,email,username,person_id,access_level) VALUES('$fname','$lname','$email','$usn','$person_id','$accesslvl_id')",
            "INSERT INTO passwords (users_id,pw_encrypted) VALUES($user_id, '" . $fn->encrypt_pw($password) . "')",
            "INSERT INTO program(pro_name, pro_coordinator, department_id) VALUES('$pro_name','$coord_id','$dep')",
            "INSERT INTO department(dep_name, dep_acronym, dep_chair, division_id) VALUES('$dep_name','$acr','$chair_id',1)",
            "INSERT INTO location(building, building_manager) VALUES('$bldg','$mgmt_id')",
            "INSERT INTO division(name) VALUES('$division')"
            
        );
        foreach ($seed_queries as $q) {
            print $q . "<br />";
            $fn->query($q);
            print "Completed <br />";
        }
    }
    if ($create) {
        if ($create === "all")
            $fn->create_view();
        else
            $fn->create_view($create);
    }
    if ($drop) {
        if ($drop === "all")
            $fn->drop_all_views();
        else
            $fn->drop_view($drop);
    }
    print '<a href="seed.php">Back</a>';
}else{
    print '<h2>Awaiting input... </h2>';
    print '<a href="index.php">Cancel</a>';
}
?>
