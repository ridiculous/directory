<?php

/* Maui College Online Directory
 * ICS 420 Class
 * Fall 2010
 */

#Include class functions for accessing database layer
require_once "db/db.php";

class Chief extends Db {
    #Set commonly used session variables

    public function set_up_session_stuff($id) {
        include "models/user.php";
        include "models/division.php";
        $user = new Users();
        $division = new Division();
        $user->_update_last_login($id);
        $users_info = $this->nifty_query("SELECT username, " . parent::get_concat_person('u') . " as fullname, role FROM users u WHERE id = '$id'");
        $_SESSION["user_username"] = $users_info[0];
        $_SESSION["user_fullname"] = $users_info[1];
        $_SESSION["users_role"] = $users_info[2];

        // division access
        if ($_SESSION["users_role"] == 'division') {
            $d = $division->_find($_SESSION["u_access"]);
            $_SESSION["users_departments"] = implode(',', $division->_departments($_SESSION["u_access"]));
            $_SESSION["users_access_level"] = $d['name'];
        }
        // department
        elseif ($_SESSION["users_role"] == 'department') {
            $d = $this->find_access_level($_SESSION["u_access"]);
            $_SESSION["users_departments"] = $_SESSION["u_access"];
            $_SESSION["users_access_level"] = $d[0];
        }
        // admin
        else {
            $_SESSION["users_departments"] = $this->admin_code();
            $_SESSION["users_access_level"] = "Administrator";
        }
        $_SESSION["user_session"] = session_id();
    }

    /*
     * Function to find appropriate query for the users search results
     */

    function get_search_results($newSearch, $search_param, $department_id) {
        $findview = "peopleA";
        $newSearch = trim($newSearch);
        !$newSearch ? '' : @list($name_f, $name_l) = explode(' ', $newSearch);
        if ($this->is_admin($department_id)) {
            if ($search_param == "person") {
                $_SESSION["query_type"] = 'person';
                if (strlen($newSearch) == 1) {
                    $query = "SELECT * FROM all_tables WHERE SUBSTRING(person,1,1) = '$newSearch'";
                } elseif (empty($newSearch)) {
                    $query = 'SELECT * FROM ' . $findview . ' ORDER BY person';
                } else {
                    $query = "SELECT * FROM all_tables WHERE SUBSTRING(person,1,1) = 'a'";
                    if (strpbrk($newSearch, ' ') == false || strstr($newSearch, ',')) {
                        $query = "SELECT * FROM all_tables WHERE person like '%$newSearch%' ORDER BY person";
                    } else {
                        if (empty($name_l)) {
                            $query = "SELECT id, " . parent::concat_fullname('none') . " as Person, Email, Mailbox, Phone, Location, " . parent::wrap() . "Dep/Program" . parent::wrap() . " , Position FROM all_tables_person WHERE first_name like '%$name_f%' ORDER BY last_name";
                        } else {
                            $query = "SELECT id, " . parent::concat_fullname('none') . " as Person, Email, Mailbox, Phone, Location, " . parent::wrap() . "Dep/Program" . parent::wrap() . ", Position  FROM all_tables_person WHERE first_name like '%$name_f%' and last_name like '%$name_l%' ORDER By last_name";
                        }
                    }
                }
            } elseif ($search_param == "department") {
                $_SESSION["query_type"] = 'department';
                $findview = "department_view";
                if (strlen($newSearch) == 1) {
                    $query = "SELECT * FROM department_view WHERE SUBSTRING(department,1,1) = '$newSearch'";
                } elseif ($newSearch == '') {
                    $query = 'SELECT * FROM ' . $findview . ' ORDER BY department';
                } else {
                    $query = "SELECT * FROM department_view WHERE department like '%$newSearch%' or acronym = '$newSearch'";
                }
            } elseif ($search_param == "location") {
                $_SESSION["query_type"] = 'location';
                if (strlen($newSearch) == 1) {
                    $query = "SELECT * FROM location_view WHERE SUBSTRING(building,1,1) = '$newSearch' ORDER BY building";
                } elseif ($newSearch == '') {
                    $query = "SELECT * FROM location_view ORDER BY building";
                } else {
                    $query = "SELECT * FROM location_view WHERE building like '%$newSearch%'";
                }
            } elseif ($search_param == "users") {
                $_SESSION["query_type"] = 'users';
                if (strlen($newSearch) == 1) {
                    $query = "SELECT * FROM users_view WHERE SUBSTRING(username,1,1) = '$newSearch' ORDER BY username DESC";
                } elseif ($newSearch == '') {
                    $query = "SELECT * FROM  users_view ORDER BY username";
                } else {
                    $query = "SELECT * FROM users_view WHERE name like '%$newSearch%' or username like '%$newSearch%' ORDER BY username DESC";
                }
            } elseif ($search_param == "program") {
                $_SESSION["query_type"] = 'program';
                $findview = "program_view_a";
                if (strlen($newSearch) == 1) {
                    $query = "SELECT * FROM program_view WHERE SUBSTRING(program,1,1) = '$newSearch'";
                } elseif ($newSearch == '') {
                    $query = 'SELECT * FROM ' . $findview . ' ORDER BY program';
                } else {
                    $query = "SELECT * FROM program_view WHERE program like '%$newSearch%'";
                }
            } else {
                $_SESSION["query_type"] = 'person';
                $query = "SELECT * FROM all_tables WHERE SUBSTRING(person,1,1) = 'a'";
            }
        } else {#Not admin access level
            if ($search_param == "person") {
                $_SESSION["query_type"] = 'person';
                if (strlen($newSearch) == 1) {
                    $query = "SELECT id, Person, Email, Mailbox, Phone, location, " . parent::wrap() . "Dep/Program" . parent::wrap() . ", Position FROM all_tables_user WHERE SUBSTRING(person,1,1) = '$newSearch' and department_id in ($department_id)";
                } elseif (empty($newSearch)) {
                    $query = "SELECT id, Person, Email, Mailbox, Phone, location," . parent::wrap() . "Dep/Program" . parent::wrap() . ", Position FROM all_tables_user  WHERE department_id in ($department_id) ORDER BY person";
                } else {
                    if (strpbrk($newSearch, ' ') == false || strstr($newSearch, ',')) {
                        $query = "SELECT id, Person, Email, Mailbox, Phone, location, " . parent::wrap() . "Dep/Program" . parent::wrap() . ", Position FROM all_tables_user WHERE person like '%$newSearch%' and department_id in ($department_id)";
                    } else {
                        if (empty($name_l)) {
                            $query = "SELECT id, " . parent::concat_fullname('none') . " as Person, Email, Mailbox, Phone, Location, " . parent::wrap() . "Dep/Program" . parent::wrap() . " FROM all_tables_person_user WHERE first_name like '%$name_f%' and department_id in ($department_id)";
                        } else {
                            $query = "SELECT id, " . parent::concat_fullname('none') . " as Person, Email, Mailbox, Phone, Location, " . parent::wrap() . "Dep/Program" . parent::wrap() . " FROM all_tables_person_user WHERE first_name like '%$name_f%' and last_name like '%$name_l%' and department_id in ($department_id)";
                        }
                    }
                }
            } elseif ($search_param == "department") {
                $_SESSION["query_type"] = 'department';
                $query = "SELECT department_id, Department, Acronym, Chair, Division FROM department_view_user WHERE department_id in ($department_id)";
            } elseif ($search_param == "program") {
                $_SESSION["query_type"] = 'program';
                if (strlen($newSearch) == 1) {
                    $query = "SELECT id, Program, Department," . parent::wrap() . "Program Coordinator" . parent::wrap() . ", Mailbox from program_view_user WHERE SUBSTRING(program,1,1) = '$newSearch'  and department_id in ($department_id) ORDER BY program";
                } elseif ($newSearch == '') {
                    $query = "SELECT id, Program, Department, " . parent::wrap() . "Program Coordinator" . parent::wrap() . ", Mailbox from program_view_user WHERE department_id in ($department_id) ORDER BY program";
                } else {
                    $query = "SELECT id, Program, Department, " . parent::wrap() . "Program Coordinator" . parent::wrap() . ", Mailbox from program_view_user WHERE program like '%$newSearch%' and department_id in ($department_id) ORDER BY program";
                }
            } else {
                $_SESSION["query_type"] = 'person';
                $query = "SELECT id, Person, Email, Mailbox, Phone, location, " . parent::wrap() . "Dep/Program" . parent::wrap() . ", Position FROM all_tables_user WHERE department_id in ($department_id)";
            }
        }
        return $query;
    }

    function is_it_legit() {
        $pw_reset = isset($_SESSION["pw_reset"]) ? $_SESSION["pw_reset"] : 0;
        $user_session = $_SESSION["user_session"];
        if (!isset($user_session)) {
            if ($pw_reset == 1) {
                Header('HTTP/1.1 301 Moved Permanently');
                Header('Location:change_password.php');
            } else {
                Header('HTTP/1.1 301 Moved Permanently');
                Header('Location:index.php?g=login_att');
            }
        } else {
            if ($pw_reset == 1) {
                Header('HTTP/1.1 301 Moved Permanently');
                Header('Location:change_password.php');
            }else
                return true;
        }
    }

    function is_legit_password_change() {
        $uses = $_SESSION["user_session"];
        if (empty($uses) && $pw_reset != 1) {
            if ($pw_reset == 1) {
                Header('HTTP/1.1 301 Moved Permanently');
                Header('Location:login.php');
                exit;
            }
        }
    }

    function get_letter_query($newSearch, $search_param, $department_id) {
        if ($this->is_admin($department_id)) {
            if ($newSearch == "Show All") {
                $_SESSION["query_type"] = 'person';
                if ($search_param == "person") {
                    $_SESSION["query_type"] = 'person';
                    $query = "SELECT * FROM all_tables ORDER BY person"; #Use this search if user clicks "All" button
                } elseif ($search_param == "department") {
                    $_SESSION["query_type"] = 'department';
                    $query = "SELECT * FROM department_view ORDER BY department";
                } elseif ($search_param == "program") {
                    $_SESSION["query_type"] = 'program';
                    $query = "SELECT * FROM program_view ORDER BY program";
                } elseif ($search_param == "users") {
                    $_SESSION["query_type"] = 'users';
                    $query = "SELECT * FROM users_view ORDER BY username";
                } elseif ($search_param == "location") {
                    $_SESSION["query_type"] = 'location';
                    $query = "SELECT * FROM location_view ORDER BY building";
                } else {
                    $query = "SELECT * FROM all_tables ORDER BY person";
                }
            } elseif ($search_param == "person") {
                $_SESSION["query_type"] = 'person';
                $query = "SELECT * FROM all_tables WHERE SUBSTRING(person,1,1) = '$newSearch'";
            } elseif ($search_param == "department") {
                $_SESSION["query_type"] = 'department';
                $query = "SELECT * FROM department_view WHERE SUBSTRING(department,1,1) = '$newSearch'";
            } elseif ($search_param == "location") {
                $_SESSION["query_type"] = 'location';
                $query = "SELECT * FROM location_view WHERE SUBSTRING(building,1,1) = '$newSearch' ORDER BY building DESC";
            } elseif ($search_param == "program") {
                $_SESSION["query_type"] = 'program';
                $query = "SELECT * FROM program_view WHERE SUBSTRING(program,1,1) = '$newSearch' ORDER BY program DESC";
            } elseif ($search_param == "users") {
                $_SESSION["query_type"] = 'users';
                $query = "SELECT * FROM users_view WHERE SUBSTRING(username,1,1) = '$newSearch' ORDER BY username DESC";
            } else {
                $query = "SELECT * FROM all_tables WHERE SUBSTRING(person,1,1) = '$newSearch'";
            }
        } else { //Not admin
            #Use this search if user clicks "All" button
            if ($newSearch == "Show All") {
                $_SESSION["query_type"] = 'person';
                if ($search_param == "person") {
                    $_SESSION["query_type"] = 'person';
                    $query = "SELECT id, Person, Email, Mailbox, Phone, location, " . parent::wrap() . "Dep/Program" . parent::wrap() . ", Position FROM all_tables_user WHERE department_id in ($department_id) ORDER BY person";
                } elseif ($search_param == "department") {
                    $_SESSION["query_type"] = 'department';
                    $query = "SELECT department_id, Department, Acronym, Chair, Division FROM department_view_user WHERE department_id in ($department_id)";
                } elseif ($search_param == "program") {
                    $_SESSION["query_type"] = 'program';
                    $query = "SELECT id, Program, Department, " . parent::wrap() . "Program Coordinator" . parent::wrap() . ", Mailbox FROM program_view_user WHERE department_id in ($department_id) ORDER BY program DESC";
                } else {
                    $query = "SELECT id, Person, Email, Mailbox, Phone, location, " . parent::wrap() . "Dep/Program" . parent::wrap() . ", Position FROM all_tables_user WHERE department_id in ($department_id) ORDER BY person";
                }
            } elseif ($search_param == "person") {
                $_SESSION["query_type"] = 'person';
                $query = "SELECT id, Person, Email, Mailbox, Phone, location, " . parent::wrap() . "Dep/Program" . parent::wrap() . ", Position FROM all_tables_user WHERE SUBSTRING(person,1,1) = '$newSearch' and department_id in ($department_id)";
            } elseif ($search_param == "department") {
                $_SESSION["query_type"] = 'department';
                $query = "SELECT department_id, Department, Acronym, Chair, Division FROM department_view_user WHERE SUBSTRING(department,1,1) = '$newSearch'  and department_id in ($department_id)";
            } elseif ($search_param == "program") {
                $_SESSION["query_type"] = 'program';
                $query = "SELECT id, Program, Department, " . parent::wrap() . "Program Coordinator" . parent::wrap() . ", Mailbox FROM program_view_user WHERE SUBSTRING(program,1,1) = '$newSearch' and department_id in ($department_id) ORDER BY program";
            } else {
                $query = "SELECT id, Person, Email, Mailbox, Phone, location, " . parent::wrap() . "Dep/Program" . parent::wrap() . ", Position FROM all_tables_user WHERE SUBSTRING(person,1,1) = '$newSearch' and department_id in ($department_id)";
            }
        }
        return $query;
    }

//The method for finding a persons id. If the first query is empty then it tries to the id using a view.
//TODO: Find a way to improve the search so it finds by email
    function find_person_id($fname, $lname, $email = null) {
        $find_back = "SELECT id from people where first_name = '$fname' and last_name = '$lname'";
        if ($email)
            $find_back .= " and email = '$email'";
        $person = parent::query($find_back);
        $p_id = parent::fetch($person);
        $id = $p_id[0];
        if (!empty($id)) {
            return $id;
        } else {
            $full_name = $fname . ' ' . $lname;
            $alt = $this->nifty_query("SELECT id FROM ajax_person WHERE person like '%$full_name%'");
            return $alt[0];
        }
    }

    function find_person_id_with_email($fname, $lname, $email) {
        $find_back = "SELECT id from people where first_name = '$fname' and last_name = '$lname' and email = '$email'";
        $p_id = $this->nifty_query($find_back);
        $id = $p_id[0];
        if (!empty($id)) {
            return $id;
        } else {
            $full_name = $fname . ' ' . $lname;
            $altern = "SELECT id FROM ajax_person WHERE person like '%$full_name%'";
            $alt_array = $this->nifty_query($altern);
            return $alt_array[0];
        }
    }

    function find_person_fullname($id) {
        $person = $this->nifty_query("SELECT " . parent::get_concat_person() . " as Name FROM people p WHERE p.id = '$id'");
        return $person[0];
    }

    function find_person_id_by_email($email) {
        $person = $this->nifty_query("SELECT id from people where email = '$email'");
        return $person[0];
    }

    function add_person($fname, $lname, $email, $usn, $pro_id, $loc_id, $dep_id = 0) {
        parent::query("INSERT INTO people (first_name,last_name, email,username,program_id,location_id, department_id) VALUES ('$fname','$lname','$email','$usn','$pro_id','$loc_id', $dep_id)");
    }

    function createRandomPassword() {

        $chars = "0123456789AaBbCcDdEeFfGgHhIiJjKkLMmNnOoPpQqRrSsTtUuVvWwXxYyZz0123456789";
        srand((double) microtime() * 1000000);
        $i = 0;
        $pass = '';
        while ($i <= 8) {
            $num = rand() % 33;
            $tmp = substr($chars, $num, 1);
            $pass = $pass . $tmp;
            $i++;
        }
        return $pass;
    }

    #Used by sarge.php for ajax requests

    function find_program_name($id) {
        $find_pro_name = $this->nifty_query("SELECT pro_name FROM program WHERE id = '$id'");
        return $find_pro_name[0];
    }

    function find_user_id($usn) {
        $u = $this->nifty_query("SELECT id FROM users WHERE CAST(username as BINARY) = CAST('$usn' as BINARY)");
        return $u[0];
    }

    function find_access_level($id) {
        $access = $this->nifty_query("SELECT dep_acronym as Access, id, dep_name FROM department WHERE id = '$id'");
        return $access;
    }

    function is_password_reset($id) {
        $results = $this->nifty_query("SELECT password_reset FROM users WHERE id = '$id'");
        return $results[0];
    }

    function find_password_id($password) {
        $parray = $this->nifty_query("SELECT id FROM passwords WHERE pw_encrypted = '$password'");
        return $parray[0];
    }

    function change_password($id, $new) {
        parent::query("UPDATE passwords SET pw_encrypted = '$new' WHERE id = '$id'");
    }

    function set_password_reset_off($u_id) {
        parent::query("UPDATE users SET password_reset = 0 WHERE id = '$u_id'");
    }

    function set_password_reset_on($u_id) {
        parent::query("UPDATE users SET password_reset = 1 WHERE id = '$u_id'");
    }

//Get the public search results. This function relies on a few views stored in db
    function get_public_search_results($newSearch, $search_param) {
        $findview = "peopleA_public";
        $newSearch = trim($newSearch);

        @list($name_f, $name_l, $mi, $fourth) = explode(' ', $newSearch);

        if ($search_param == "person") {
            $_SESSION["query_type"] = 'person';
            if (strlen($newSearch) == 1) {
                $query = "SELECT * FROM all_tables_public WHERE SUBSTRING(person,1,1) = '$newSearch'";
            } elseif ($newSearch == '') {
                $query = 'SELECT * FROM ' . $findview . ' ORDER BY person';
            } else {
                if (strpbrk($newSearch, ' ') == false || strstr($newSearch, ',')) {
                    $query = "SELECT * FROM all_tables_public WHERE person like '%$newSearch%' ORDER BY person";
                } elseif ($fourth) {
                    $fourth = $name_f . ' ' . $name_l . ' ' . $mi . ' ' . $fourth;
                    $query = "SELECT id, " . parent::concat_fullname('none') . " as Person, Email, Mailbox, Phone, Location, " . parent::wrap() . "Div/Dep/Program" . parent::wrap() . ", Position, perm  FROM all_tables_person_public WHERE " . parent::get_concat_person() . " like '%$fourth%'";
                } elseif (!empty($mi)) {
                    $query = "SELECT id, " . parent::concat_fullname('none') . " as Person, Email, Mailbox, Phone, Location, " . parent::wrap() . "Div/Dep/Program" . parent::wrap() . ", Position, perm  FROM all_tables_person_public WHERE first_name like '%$name_l%' and last_name like '%$mi%' ORDER By last_name";
                } else {
                    if (empty($name_l)) {
                        $query = "SELECT id, " . parent::concat_fullname('none') . " as Person, Email, Mailbox, Phone, Location, " . parent::wrap() . "Div/Dep/Program" . parent::wrap() . ", Position, perm FROM all_tables_person_public WHERE first_name like '%$name_f%' ORDER BY last_name";
                    } else {
                        $query = "SELECT id, " . parent::concat_fullname('none') . " as Person, Email, Mailbox, Phone, Location, " . parent::wrap() . "Div/Dep/Program" . parent::wrap() . ", Position, perm FROM all_tables_person_public WHERE first_name like '%$name_f%' and last_name like '%$name_l%' ORDER By last_name";
                    }
                }
            }
        } elseif ($search_param == "department") {
            if (strlen($newSearch) == 1) {
                $query = "SELECT * FROM all_tables_public WHERE substring(" . parent::wrap() . "Div/Dep/Program" . parent::wrap() . ",(" . parent::get_strindex() . "('-'," . parent::wrap() . "Div/Dep/Program" . parent::wrap() . ") +2), 1) = '$newSearch' ORDER BY person";
            } elseif ($newSearch == '') {
                $query = 'SELECT * FROM ' . $findview . ' ORDER BY person';
            } else {
                $query = "SELECT * FROM all_tables_public WHERE " . parent::wrap() . "Div/Dep/Program" . parent::wrap() . " like '%$newSearch%' ORDER BY person";
            }
        } elseif ($search_param == "location") {
            if (strlen($newSearch) == 1) {
                $query = "SELECT * FROM all_tables_public WHERE SUBSTRING(location,1,1) = '$newSearch' ORDER BY person";
            } elseif ($newSearch == '') {
                $query = "SELECT * FROM " . $findview . " ORDER BY person ORDER BY person";
            } else {
                $query = "SELECT * FROM all_tables_public WHERE location like '%$newSearch%' order by person";
            }
        } elseif ($search_param == "phone") {
            if (strlen($newSearch) == 1) {
                $query = "SELECT * FROM all_tables_public WHERE phone like '%$newSearch' ORDER BY person";
            } elseif ($newSearch == '') {
                $query = 'SELECT * FROM ' . $findview . ' ORDER BY person';
            } else {
                $query = "SELECT * FROM all_tables_public WHERE phone like '%$newSearch%' ORDER by person";
            }
        } elseif ($search_param == "email") {
            if (strlen($newSearch) == 1) {
                $query = "SELECT * FROM all_tables_public WHERE email like '%$newSearch' ORDER BY person";
            } elseif ($newSearch == '') {
                $query = 'SELECT * FROM ' . $findview . ' ORDER BY person';
            } else {
                $query = "SELECT * FROM all_tables_public WHERE email like '%$newSearch%' ORDER BY person";
            }
        } else {
            if (strlen($newSearch) == 1) {
                $query = "SELECT * FROM all_tables_public WHERE SUBSTRING(person,1,1) = '$newSearch' ORDER BY person";
            } elseif ($newSearch == '') {
                $query = 'SELECT * FROM ' . $findview . ' ORDER BY person';
            } else {
                $query = "SELECT * FROM all_tables_public WHERE person like '%$newSearch%' or " . parent::wrap() . "Div/Dep/Program" . parent::wrap() . " like '%$newSearch%' or location like '%$newSearch%' ORDER BY person";
            }
        }
        return $query;
    }

    function get_public_letter_query($newSearch, $search_param) {
        if ($search_param == "person") {
            $_SESSION["query_type"] = 'person';
            $query = "SELECT * FROM all_tables_public WHERE SUBSTRING(person,1,1) = '$newSearch' ORDER BY person";
        } elseif ($search_param == "program") {
            $_SESSION["query_type"] = 'program';
            $query = "SELECT r.id, " . parent::get_person_fullname() . " as Person,p.Email, p.Mailbox,p.phone_number as Phone,
                " . parent::get_concat_location() . " as Location," . parent::get_concat_ddp() . " as " . parent::wrap() . "Div/Dep/Program" . parent::wrap() . ",
                    p.Position, p.Permanent_position
                    FROM people p
                    INNER JOIN location l on l.id = p.location_id
                    INNER JOIN program r on r.id = p.program_id
                    INNER JOIN department d on d.id = r.department_id
                    WHERE SUBSTRING(r.pro_name,1,1) = '$newSearch' ORDER BY person";
        } elseif ($search_param == "email") {
            $_SESSION["query_type"] = 'email';
            $query = "SELECT * FROM all_tables_public WHERE substring(email,1,1) = '$newSearch' ORDER BY person";
        } elseif ($search_param == "location") {
            $_SESSION["query_type"] = 'location';
            $query = "SELECT * FROM all_tables_public WHERE SUBSTRING(location,1,1) = '$newSearch' ORDER BY person";
        } elseif ($search_param == "department") {
            $_SESSION["query_type"] = 'department';
            $query = "SELECT * FROM all_tables_public WHERE substring(" . parent::wrap() . "Div/dep/program" . parent::wrap() . ",(" . parent::get_strindex() . "('-'," . parent::wrap() . "Div/dep/program" . parent::wrap() . ") +2), 1) = '$newSearch' ORDER BY person";
        } else {
            $query = "SELECT * FROM all_tables_public WHERE SUBSTRING(person,1,1) = '$newSearch' ORDER BY person";
        }
        return $query;
    }

//find the users access level
    function find_users_access_level($id) {
        $q = $this->nifty_query("SELECT access_level FROM users WHERE id = '$id'");
        return $q[0];
    }

//Check if the current user is an admin
    function is_admin($level) {
        if ($level == $this->admin_code()) {
            return 1;
        }else
            return false;
    }

    function admin_code() {
        return -1;
    }

#Escape the dreaded single quotes

    function sql_escape($string) {
#Escape the dreaded single quotes
        $fix_str = stripslashes($string);
        $fix_str = str_replace("'", "''", $string);
        return $fix_str;
    }

#Checks proper access level

    function is_authorized() {
        if ($_SESSION["u_access"] != $this->admin_code()) {
            $_SESSION["db_msg"] = "You're not authorized!";
            $_SESSION["db_status"] = 0;
            header("location:admin.php");
        }
    }

#Substitutes the !<^@^>! sequence for double-quotes before printing

    function qq($text) {
        return str_replace('!<^@^>!', '"', $text); #Uses a random pattern to match against. here its !<^@^>!
    }

    function printq($text) {
        print $this->qq($text);
    }

//    private function fix_amp($s) {#Fixes the ampersand issue
//        $s = str_replace('&', 'and', $s);
//        $s = str_replace('amp;', '', $s);
//        return $s;
//    }

    function find_person_details($id) {
        $rows = $this->nifty_query(parent::query_person_details($id));
        return $rows;
    }

    public function print_details($notes) {
        if (empty($notes))
            return;
        $dmsg = "<tr class='details'><td colspan='5'><div class='detail-container'>";
        @list($f, $l, $m) = explode(' ', $notes[1]);
        $search = $f . '+' . $l . '+' . $m . '&searchfield=person';
        $f = str_replace('.', '', $f);
        if (trim($notes[0]) != '' && trim($notes[1]) != '') #If both comments and contact
            $dmsg .= "<p>Notes: $notes[0] </p><p> Contact/phone: &nbsp;<a href='index.php?searchtext=$search' title='Find $f $l' alt='Find $f $l'>$notes[1]</a>  $notes[2]</p></div></td></tr>";
        elseif (trim($notes[0]) != '')#Print just comments
            $dmsg .= "<p>Notes: $notes[0] </p></div></td></tr>";
        elseif (trim($notes[1]) != '')#print just contact
            $dmsg .= "<p>Contact/phone: &nbsp;<a href='index.php?searchtext=$search' title='Find $f $l' alt='Find $f $l'>$notes[1]</a>  $notes[2]</p></div></td></tr>";
        print $dmsg;
    }

//Function is used by form partials
    function generate_select_menu($option_array, $element_name, $item_id = null, $item_name = null, $id = null) {
        $_id = $id ? "id='$id'" : '';
        print "<select $_id name='$element_name' class='nice-field'>";
        if ($item_id)
            $this->printq("<option value=!<^@^>!$item_id!<^@^>!>$item_name</option>");
        if (is_array($option_array)) {
            foreach ($option_array as $v) {
                $item_id != $v ? $this->printq("<option value= !<^@^>!$v!<^@^>!>$v</option>") : print "";
            }
        } else {
            while ($row = parent::fetch($option_array)) {
                if ($item_id) {
                    if ($item_id != $row[0])
                        $this->printq("<option value=!<^@^>!$row[0]!<^@^>!>$row[1]</option>");
                } else {
                    $this->printq("<option value= !<^@^>!$row[0]!<^@^>!>$row[1]</option>");
                }
            }
        }
        print "</select>";
    }

    function print_head($title) {
        print '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>' . htmlspecialchars($title) . '</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" type="text/css" href="styles/uhmstyles.css" />
        <script type="text/javascript" src="includes/scripts/jquery.min.js"></script>
        <script type="text/javascript" src="includes/scripts/jquery.ui.core.js"></script>
        <script type="text/javascript" src="includes/scripts/jquery.ui.widget.js"></script>
        <script type="text/javascript" src="includes/scripts/jquery.ui.position.js"></script>
        <script type="text/javascript" src="includes/scripts/jquery.ui.autocomplete.js"></script>
        <script type="text/javascript" src="includes/scripts/simplemodal.js"></script>
        <script type="text/javascript" src="includes/scripts/jscript.js" ></script>';
    }

    function unset_msgs() {
        unset($_SESSION["db_status"]);
        unset($_SESSION["db_msg"]);
    }

    function encrypt_pw($pw) {
        return md5($pw);
    }

    public function nifty_query($statement) {
        return parent::fetch(parent::query($statement));
    }

    public function get_programs() {
        $_SESSION["users_role"];
        if ($this->is_admin($_SESSION["u_access"])) {
            $s = "SELECT id, pro_name FROM program ORDER BY pro_name";
        } else {
            $s = "SELECT p.id, p.pro_name FROM program p INNER JOIN department d ON d.id = p.department_id WHERE d.id in (" . $_SESSION["users_departments"] . ")";
        }
        return $s;
    }

    public function savecancel($field, $users_id = null, $href = null) {
        $letter_search = isset($_SESSION["last_ls"]) ? $_SESSION["last_ls"] : "";
        $text = isset($_SESSION["last_searched"]) ? $_SESSION["last_searched"] : "";
        $width = $users_id ? 400 : 190;
        print '<div style="width:' . $width . 'px;margin: 0;">
               <div class="basic-form" style="padding:10px;top:-20px;">
               <input type="submit" value="Save" name ="save" class="btn success" />&nbsp;&nbsp;or&nbsp;&nbsp;';
        #IF $users_id then print password reset button
        if ($users_id)
            print "<input type='button' id='pwreset' value = 'Reset Password' name='$users_id' class='btn' />or&nbsp;&nbsp;";#reset password
        if ($href)
            $link = '<a href="' . $href . '" class="btn" >Cancel</a>';
        else
            $link = '<a href="admin.php?searchtext=' . $text . '&searchfield=' . $field . '&lettersrch=' . $letter_search . '" class="btn" >Cancel</a>';
        print $link;
        print '</div></div>';
    }

    #Print out error messages stored in session variables and then clear them

    public function show_msgs($custom = false, $msg = '') {
        if ($custom) {
            print "<div id='outer'>";
            print $msg;
            print "</div>";
        } else {
            if (isset($_SESSION["db_msg"]) && $_SESSION["db_msg"] != '') {
                if ($_SESSION["db_status"] == 1) {
                    print "<div id='outer'>";
                    print $_SESSION["db_msg"];
                    print "</div>";
                } else {
                    print "<div id='outer-warn'>";
                    print $_SESSION["db_msg"];
                    print "</div>";
                }
            }
        }
        $this->unset_msgs(); #clear variables
    }

    public function is_user($id) {
        $user_query = "SELECT person_id FROM users WHERE person_id = '$id'";
        return $this->nifty_query($user_query);
    }

    public function get_first_building_id() {
        return parent::get_first_building();
    }

    function is_valid_date_time($dateTime) {
        if (preg_match("/^(\d{4})-(\d{2})-(\d{2}) ([01][0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9])$/", $dateTime, $matches)) {
            if (checkdate($matches[2], $matches[3], $matches[1])) {
                return true;
            }
        }
        return false;
    }

    public function how_long_ago($date) {
        $result = "";
        if ($date) {
            $diff = date_diff(date_create(date('Y-m-d H:i:s')), date_create($date));
//            if ($diff < 1) {
//                $result = "Less than a minute";
//            } else if ($diff < 60) {
//                $result = intval($diff) . " minute" . ($diff < 2 ? "" : "s");
//            } else if (intval($diff / 60) <= 48) {
//                $result = ($diff / 60) + " hour" + ((diff / 60) . to_i < 2 ? "" : "s");
//            } else if ((intval(diff / 60) / 24) > 365) {
//                $result = ((intval($diff / 60) / 24) / 365) + " year(s)";
//            } else {
//                $result = (intval($diff / 60) / 24) + " days";
//            }
        }else
            $result = "Never";
        return $diff;
    }

}

?>