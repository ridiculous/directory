<?php
session_start();
ob_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns = "http://www.w3.org/1999/xhtml">
    <head>
        <?php
        include("chief.php");
        $fn = new Chief(); #access to chief functions
        $fn->print_head('Directory | Admin'); #Print title, links, scripts, and meta tags
        ?>
    </head>
    <body>
        <a name="top"></a>
        <?php
        $username_input = isset($_POST["uname"]) ? $fn->sql_escape($_POST["uname"]) : false; #Grab username entered on login.php
        $pw_input = isset($_POST["pword"]) ? $fn->sql_escape($_POST["pword"]) : false; #Post password from login
        if ($username_input && $pw_input) {
            #Authenticate with username and password
            $_SESSION["u_id"] = $fn->find_user_id($username_input);
            $_SESSION["u_access"] = $fn->find_users_access_level($_SESSION["u_id"]);
            $auth_results = $fn->authenticate_user($_SESSION["u_id"], $fn->encrypt_pw($pw_input));
        }
        #
        $pw_reset = $fn->is_password_reset($_SESSION["u_id"]);
        if (isset($pw_reset))
            $_SESSION["pw_reset"] = $pw_reset;
        if (!isset($_SESSION["user_session"]) || empty($_SESSION["u_id"])) {
            if (!$fn->fetch($auth_results)) {
                $_SESSION["login_fail"] = 1;
                $_SESSION["login_msg"] = "Log in failed. Incorrect username/password";
                $_SESSION["last_uname"] = $username_input;
                Header('HTTP/1.1 301 Moved Permanently');
                Header('Location:index.php?g=login_att');
                exit();
            } elseif ($pw_reset == 1) {
                $fn->set_up_session_stuff($_SESSION["u_id"]);
                Header('HTTP/1.1 301 Moved Permanently');
                Header('Location:change_password.php');
                exit();
            } else {
                $fn->set_up_session_stuff($_SESSION["u_id"]);
            }
        } elseif (isset($_SESSION["user_session"])) {
            if ($pw_reset == 1) {
                Header('HTTP/1.1 301 Moved Permanently');
                Header('Location:change_password.php');
                exit();
            }
        }
        $fn->is_it_legit(); #Validate the session is established and redirect to login if not
        $fn->unset_msgs();
#Include the header partial depending on is_admin condition
        $fn->is_admin($_SESSION["u_access"]) ? include 'partials/header_admin.php' : include 'partials/header.php';
        if (isset($_GET["user_msg"])) {
            $m = $_GET["user_msg"];
            if ($m == 'reset') {
                $fn->show_msgs(true, 'User password reset! They have been sent a temporary password');
            }else
                $fn->show_msgs(true, 'User created! They have been sent an email with login credentials');
        }
        ?>
        <div class="clean-gray" >
            <p>
                Click Search or buttons above to begin (box can be empty). You can select from the drop down menu a category to search/manage. From there you may edit and/or delete the various records.
            </p>
        </div>
        <?php
        $newSearch = isset($_GET['searchtext']) ? $fn->sql_escape($_GET['searchtext']) : '';
        $search_param = isset($_GET['searchfield']) ? $fn->sql_escape($_GET['searchfield']) : '';


        $lettersrch = isset($_GET['lettersrch']) ? $fn->sql_escape($_GET['lettersrch']) : '';
        if ($newSearch)
            $_SESSION["last_searched"] = $newSearch;
        if ($lettersrch)
            $_SESSION["last_ls"] = $lettersrch;
#find appropriate query based on search criteria
        $result = !$lettersrch ? $fn->get_search_results($newSearch, $search_param, $_SESSION["users_departments"]) : $fn->get_letter_query($lettersrch, $search_param, $_SESSION["users_departments"]);
# Execute query returned in $result
        $search_results = $fn->query($result); //($result, array(), array("Scrollable" => 'keyset') << sqsrv
#Get the number of rows in the result, as well as the first row and the number of fields in the rows
        $row = $fn->fetch_hash($search_results); #Fetch next row in associative form (k=>v)


        $num_fields = $fn->fields($search_results);


        if (is_array($row))
            $keys = array_keys($row);


        if (isset($keys)) {
            if ($search_param == 'person') {
                print "<table id='mainTable' cellspacing='3' cellpadding='2' frame='below' >";
                print "<tr align = 'left'>";
                for ($index = 0; $index < $num_fields; $index++) {
                    if ($index == 0) {
                        print "<th width='10%'align = 'center' class='t-h'> Edit/Delete </th>";
                    } elseif ($index == 1) {
                        print "<th style='width: 10%;'align = 'center' class='t-h'>" . $keys[$index] . "</th>";
                    } elseif ($index == 2) {
                        print "<th style='width: 15%;' align = 'center' class='t-h'>" . $keys[$index] . "</th>";
                    } elseif ($index == 3) {
                        print "<th style='width: 5%;' align = 'center' class='t-h'>" . $keys[$index] . "</th>";
                    } elseif ($index == 4) {
                        print "<th style='width: 7%;' align = 'center' class='t-h'>" . $keys[$index] . "</th>";
                    } elseif ($index == 5) {
                        print "<th style='width: 9%;' align = 'center' class='t-h'>" . $keys[$index] . "</th>";
                    } elseif ($index == 6) {
                        print "<th style='width: 24%;' align = 'center' class='t-h'>" . $keys[$index] . "</th>";
                    } elseif ($index == 7) {
                        print "<th style='width: 10%;' align = 'center' class='t-h'>" . $keys[$index] . "</th>";
                    } elseif ($index == 8) {
                        print "<th style='width: 10%;' align = 'center' class='t-h'>" . $keys[$index] . "</th>";
                    }
                }
            } elseif ($search_param == 'users') {
                print "<table id='mainTable' cellspacing='3' cellpadding='2' frame='below'>";
                print "<tr align = 'left'>";
                for ($index = 0; $index < $num_fields; $index++) {
                    if ($index == 0) {
                        print "<th width='7%'align = 'center' class='t-h'> Edit/Delete </th>";
                    } elseif ($index == 1) {
                        print "<th style='width: 12%;'align = 'center' class='t-h'>" . $keys[$index] . "</th>";
                    } elseif ($index == 2) {
                        print "<th style='width: 15%;' align = 'center' class='t-h'>" . $keys[$index] . "</th>";
                    } elseif ($index == 3) {
                        print "<th style='width: 9%;' align = 'center' class='t-h'>" . $keys[$index] . "</th>";
                    } elseif ($index == 4) {
                        print "<th style='width: 10%;' align = 'center' class='t-h'>" . $keys[$index] . "</th>";
                    } elseif ($index == 5) {
                        print "<th style='width: 10%;' align = 'center' class='t-h'>" . $keys[$index] . "</th>";
                    } elseif ($index == 6) {
                        print "<th style='width: 10%;' align = 'center' class='t-h'>" . $keys[$index] . "</th>";
                    }
                }
            } elseif ($search_param == 'program' || $search_param == 'location') {
                print "<table id='mainTable' width='100%' cellspacing='3' cellpadding='2'frame='below'>";
                print "<tr align = 'left'>";
                for ($index = 0; $index < $num_fields; $index++) {
                    if ($index == 0) {
                        print "<th align = 'center' class='t-h'> Edit/Delete </th>";
                    }
                    else
                        print "<th align = 'center' class='t-h'>" . $keys[$index] . "</th>";
                }
            } else {
                print "<table id='mainTable' width='100%' cellspacing='3' cellpadding='2'frame='below'>";
                print "<tr align = 'left'>";
                for ($index = 0; $index < $num_fields; $index++) {
                    if ($index == 0) {
                        print "<th align = 'center' class='t-h'> Edit/Delete </th>";
                    }
                    else
                        print "<th align = 'center' class='t-h'>" . $keys[$index] . "</th>";
                }
            }
            print "</tr>";
            #Write the table out using record set from query. Alternating bg colors
            #And edit buttons next to each person with id as the name
            $row_num = 0;
            while ($row) {
                $row_num++;
                if (($row_num % 2) == 1) {
                    $sclass = "norm";
                    $bcolor = "#FFFFFF";
                } else {
                    $sclass = "alt";
                    $bcolor = "#E2E0DC";
                }
                print "<tr align = 'left' class='$sclass' onmouseover='(this.style.background=\"#AFCBD3\")' onmouseout='(this.style.background=\"$bcolor\")'>";
                $values = array_values($row);
                for ($index = 0; $index < $num_fields; $index++) {
                    $value = htmlspecialchars($values[$index]);
                    if ($index == 0) {#First column printed out is the EDIT/DELETE buttons INDEX 0
                        if ($_SESSION["query_type"] == 'person') {
                            $role = $fn->is_user($value) ? "user" : "person";
                            $person_fullname = htmlspecialchars($values[1]);
                            print "<td align = 'center'>
                            <form name='edit_person' action='edit_person.php' method='post'>
                            <input type='hidden' name='person_id' id='person_del_id' value='$value' />
                            <input type='image' src='images/edit-pencil.gif' title='Edit this record' alt='Edit this record' name ='' />&nbsp;
                            <input type='button' class='delete-btn' id='delete_person' name='$person_fullname' alt='Delete this person' />
                            <input type='hidden' name='$role' id='person_info' />
                            </form>
                            </td>";
                        } elseif ($_SESSION["query_type"] == 'users') {
                            print "<td align = 'center'>
                            <form name='edit_users' action='edit_users.php' method='post'>
                            <input type='hidden' name='users_id' id='users_del_id' value='$value' />
                            <input type='image' src='images/edit-pencil.gif' title='Edit this record' alt='Edit this record' name ='' />&nbsp;
                            <input type='button' class='delete-btn' id='delete_user' alt='Delete this user' />
                            </form>
                            </td>";
                        } elseif ($_SESSION["query_type"] == 'department') {
                            print "<td align = 'center'>
                            <form name='edit_department' action='edit_department.php' method='post'>
                            <input type='hidden' name='department_id' id='dept_del_id' value='$value' />
                            <input type='image' src='images/edit-pencil.gif' title='Edit this record' alt='Edit this record' name ='' />&nbsp;
                            <input type='button' class='delete-btn' id='delete_department' alt='Delete this department' />
                            </form>
                            </td>";
                        } elseif ($_SESSION["query_type"] == 'program') {
                            print "<td align = 'center'>
                            <form name='edit_program' action='edit_program.php' method='post'>
                            <input type='hidden' name='pro_id' value='$value' />
                            <input type='image' src='images/edit-pencil.gif' title='Edit this record' alt='Edit this record' name ='' />&nbsp;
                            <input type='button' class='delete-btn' id='delete_program' name='$value' alt='Delete this program' />
                            </form>
                            </td>";
                        } elseif ($_SESSION["query_type"] == 'location') {
                            print "<td align = 'center'>
                            <form name='edit_location' action='edit_location.php' method='post'>
                            <input type='hidden' name='location_id' id='location_del_id' value='$value' />
                            <input type='image' src='images/edit-pencil.gif' title='Edit this record' alt='Edit this record' name ='' />&nbsp;
                            <input type='button' class='delete-btn' id='delete_location' alt='Delete this Location' />
                            </form>
                            </td>";
                        }
                    }
                    else
                        print "<td class='records'>" . $value . "</td> ";
                }
                print "</tr>";
                $row = $fn->fetch_hash($search_results); #Fetch next row in associative form (k=>v)
            }
            print "</table>";
            if ($row_num > 11) {
                print "<br /><div style='text-align: center'><span style='color: #0099CC; font-size: 18px;'> &raquo; <a href='#top'>Top</a> &laquo; </span></div>";
            }
        } else {
            print "<h2>No records found</h2>";
        }
        ?>
        <script type="text/javascript">
           
            jQuery(function($){
                $(document).ready(function() {
                    init_delete_person();
                    init_user_cut();
                    init_location_cut();
                    init_program_cut();
                    init_dept_cut();
                });
                function init_delete_person(){
                    if(!$('input#delete_person').length)return;
                    $('input#delete_person').bind('click', function() {
                        var self = $(this);
                        if(self.siblings('#person_info').attr("name") == "person"){
                            var answer = confirm ("Are you sure you want to delete " + self.attr("name") + "?");
                            if(answer){
                                //Delete person from database
                                $.get("sarge.php?persons_id="+$(this).siblings('#person_del_id').val(), function(data){
                                    if(data){
                                        alert("Sorry, delete failed "+data);
                                    }else{
                                        self.parent().parent().parent().remove();
                                    }
                                });
                            }
                        }else{
                            alert("This person is also a user! You must first delete their user account.");
                        }


                    });
                }
                function init_user_cut(){
                    if(!$('input#delete_user').length )return;
                    $('input#delete_user').bind('click', function() {
                        var self = $(this),
                        answer   = confirm ("Are you sure you want to delete this user?");
                        if (answer){
                            $.get("sarge.php?delete_user="+self.siblings('#users_del_id').val(), function(){//Delete person from database
                                alert("User has been deleted!");
                                self.parent().parent().parent().remove();
                            });
                        }
                    });
                }
                
                function init_location_cut(){
                    if(!$('input#delete_location').length)return;
                    $('input#delete_location').bind('click', function() {
                        var answer = confirm("Are you sure you want to delete this location?");
                        if (answer){
                            var self = $(this);
                            $.get("sarge.php?delete_location="+self.siblings('#location_del_id').val(), function(data){
                                if(data){
                                    alert("Delete failed. Location is likely still in use. "+data);
                                }else{
                                    self.parent().parent().parent().remove();
                                }
                            });
                        }
                    });
                }
                function init_program_cut(){
                    if(!$('input#delete_program').length)return;
                    $('input#delete_program').bind('click', function() {
                        var answer = confirm("Are you sure you want to delete this program?");
                        if (answer){
                            var t = $(this);
                            $.get("sarge.php?delete_program="+t.attr('name'), function(data){
                                if(data){
                                    alert("Sorry, delete failed!");
                                }else{
                                    t.parent().parent().parent().remove();
                                }
                            });
                        }
                    });
                }
                function init_dept_cut(){
                    if(!$('input#delete_department').length)return;
                    $('input#delete_department').bind('click', function() {
                        var answer = confirm("Are you sure you want to delete this Department?");
                        if (answer){
                            var self = $(this);
                            $.get("sarge.php?delete_dept="+self.siblings('#dept_del_id').val(), function(data){
                                if(data){
                                    alert("Sorry, delete failed!");
                                }else{
                                    self.parent().parent().parent().remove();
                                }
                            });
                        }
                    });
                }
            });
        </script>
        <?php include 'partials/footer.php' ?>
    </body>
</html>
<?php ob_end_flush(); ?>
