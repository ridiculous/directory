<?php

session_start();
include("chief.php");
include("models/user.php");
$fn = new Chief();
$user = new Users();

$location_id = $fn->get_first_building_id(); #Get first building id to use if person doesn't exist and we create the person too
$usn = $fn->sql_escape($_POST["username"]);
$fname = $fn->sql_escape($_POST["first_name"]);
$lname = $fn->sql_escape($_POST["last_name"]);
$email = $fn->sql_escape($_POST["email"]);
$phone = $fn->sql_escape($_POST["phone"]);
$accesslvl_id = $_POST["access_level"];
$role = trim($_POST["role"]);
$division_id = $_POST["division"];
$users_id = isset($_POST["users_id"]) ? $_POST["users_id"] : false;

#Determine purpose of request ( update or insert )
$action = ($users_id) ? "edit_users.php" : "add_user.php";
$action_msg = ($users_id) ? "updated" : "added";

// set access level as either administrator (-1) | division (division id) | department (department id)
// set role name as administrator | division | department
if($role == -1){
    $accesslvl_id = -1;
    $role_name = "administrator";
}else if($role == 0){
    $accesslvl_id = $division_id;
    $role_name = "division" ;
}else{
    $role_name = "department";
}

// check to see if user in already in db
$username_id = $fn->find_user_id($usn);
if ($fname == '' || $usn == '' || $lname == '' || $email == '') {
    if (!$usn)
        $emsg = "<b>Username</b>, ";
    if (!$lname)
        $emsg .= "<b>Last Name</b>, ";
    if (!$fname)
        $emsg .= "<b>First Name</b>, ";
    if (!$email)
        $emsg .= "<b>Email</b> ";
    $_SESSION["db_msg"] = $emsg . " can't be blank";
    $_SESSION["db_status"] = 0;
    Header('HTTP/1.1 301 Moved Permanently');
    Header('Location:' . $action);
    exit;
}

# set attributes for person
$attrs = $user->_new(array(
            'id' => $users_id,
            'username' => $usn,
            'first_name' => $fname,
            'last_name' => $lname,
            'email' => $email,
            'phone' => $phone,
            'access_level' => $accesslvl_id,
            'password_reset' => 1,
            'role' => $role_name
        ));

if ($users_id) {
    $user_attrs = $user->_find($users_id);
    # override some attrs
    $attrs['password_reset'] = $user_attrs['password_reset'];
    $attrs['person_id'] = $user_attrs['person_id'];

    $_SESSION["db_msg"] = "User account successfully updated";
    $_SESSION["db_status"] = 1;
} else {
    if ($username_id != 0 || !empty($username_id)) {
        $_SESSION["db_msg"] = "The username:  <b>" . $usn . " </b> is not available!";
        $_SESSION["db_status"] = 0;
        Header('HTTP/1.1 301 Moved Permanently');
        Header('Location:' . $action);
        exit;
    }
    if ($fn->find_person_id_by_email($email) != '') {
        $person_id = $fn->find_person_id_by_email($email);
    } elseif ($fn->find_person_id($fname, $lname) != '') {
        $person_id = $fn->find_person_id($fname, $lname);
    } else {
        $fn->add_person($fname, $lname, $email, $usn, 0, $location_id[0], $accesslvl_id);
        $person_id = $fn->find_person_id($fname, $lname);
    }
    $attrs['person_id'] = $person_id;
    # message is set by email_relay.php on maui.hawaii.edu server
}

# update or new
$user_query = $users_id ? $user->_update($attrs) : $user->_save($attrs);

#Execute update or insert query
$fn->query($user_query);
if (!$users_id) {
#Get the id of the user account just created
    $get_user_id = "SELECT id FROM users WHERE username = '$usn'";
    $uresults = $fn->nifty_query($get_user_id);
    $u_id = $uresults[0];
    $temp_pw = $fn->createRandomPassword(); #Generate temp password
    $_SESSION['db_msg'] = $temp_pw;
    $temp_encrypt = $fn->encrypt_pw($temp_pw); #Encrypt temporary password using md5
    $input_pw = "INSERT INTO passwords(users_id, pw_encrypted) VALUES('$u_id','$temp_encrypt')";
    $fn->query($input_pw);
    header('location: http://maui.hawaii.edu/mail_relay/_relay_email.php?_s_=1&d=' . $email . '&t=new&p=' . $temp_pw . '&u=' . $usn . '&n=' . $fname);
    exit();
}
#Redirect after everything runs
Header('Location:' . $action);
exit();
?>