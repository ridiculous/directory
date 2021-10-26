<?php

session_start();
#Process the add_person.php and edit_person.php pages. Inserts and updates
include("chief.php");
include("models/people.php");

$fn = new Chief(); #access to chief functions
$person = new People(); #person object

$fname = $fn->sql_escape($_POST["first_name"]);
$lname = $fn->sql_escape($_POST["last_name"]);
$mbox = $fn->sql_escape($_POST["mail"]);
$phone = $fn->sql_escape($_POST["phone"]);
$program_id = $_POST["program"]; #Don't want to sql_escape drop menu cuz it screws up find_* functions
$contact = trim($fn->sql_escape($_POST["contact"]));
$location_id = $_POST["location"]; #We don't want to sql escape the drop downs
$room = $fn->sql_escape($_POST["room_number"]);
$mi = $fn->sql_escape($_POST["mi"]);
$notes = trim($fn->sql_escape($_POST["notes"]));
$email = $fn->sql_escape($_POST["email"]);
$position = $fn->sql_escape($_POST["position"]);
$website = $fn->sql_escape($_POST["website"]);
$department_id = $_POST['department']; # just get the id
$person_id = isset($_POST["person_id"]) ? $_POST["person_id"] : false;

#Determine type of request ( update or insert )
$action = ($person_id) ? "edit_person.php" : "add_person.php";
$action_msg = ($person_id) ? "updated" : "added";

#See if the check box is set for permanent position
if (isset($_POST['perm']) && $_POST['perm'] == 'yes') {
    $perm = 1;
} else {
    $perm = 0;
    $_POST["person_id"];
}
//Split the contact persons name into first and last name and use that to find the id.
if ($contact) {
    $contact = trim($contact);
    list($name_f, $name_l) = split(' ', $contact);
    $contact_id = $fn->find_person_id($name_f, $name_l);
}
#change program_id to 0 if department_id is set
$program_id = ($department_id != 0) ? 0 : $program_id;

#
# merge person attributes w/ new attributes
#
$attrs = $person->_new(array(
    'id' => $person_id,
    'first_name' => $fname,
    'last_name' => $lname,
    'middle_init' => $mi,
    'email' => $email,
    'mailbox' => $mbox,
    'phone_number' => $phone,
    'room_number' => $room,
    'website' => $website,
    'permanent_position' => $perm,
    'program_id' => $program_id,
    'department_id' => $department_id,
    'location_id' => $location_id,
    'comments' => (!empty($notes) && $notes != ' ') ? $notes : null,
    'contact_id' => isset($contact_id) ? ((trim($contact_id)) ? $contact_id : null) : null,
    'position' => ($position) ? $position : null
        ));

#
# Find the action based on presence of person id
#
$person_query = $person_id ? $person->_update($attrs) : $person->_save($attrs);

#
# Basic validation
#
if (!$lname || !$fname || !$email) {
    if (!$lname) {
        $emsg = "<b>Last Name</b> ";
    }
    if (!$fname) {
        $emsg .= "<b>First Name</b> ";
    }
    if (!$email) {
        $emsg .= "<b>Email</b> ";
    }
    $_SESSION["db_msg"] = "$emsg  can't be blank";
    $_SESSION["db_status"] = 0;
} elseif (!$person_id && $fn->find_person_id($fname, $lname, $email) != '') {
    $_SESSION["db_msg"] = "Person is already in the database";
    $_SESSION["db_status"] = 0;
} else {
    if ($fn->query($person_query)) {
        $_SESSION["db_msg"] = $lname . ", " . $fname . " has been $action_msg";
        $_SESSION["db_status"] = 1;
    } else {
        $_SESSION["db_msg"] = "Whoops, something went wrong";
        $_SESSION["db_status"] = 0;
    }
}

Header('HTTP/1.1 301 Moved Permanently');
Header('Location:' . $action);
?>