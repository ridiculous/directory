<div style="padding-bottom:10px;">
    <div style="float:right;padding:10px;"> <a href="logout.php" class="btn danger">Logout</a></div>
    <a href="http://maui.hawaii.edu"><img src="mauiCC_identity.gif" alt="Home" id="logo-image" border="0"/>
    </a>
</div>
<div class="content" align = "center">
    <ul class="main-menu">
        <li> <a href="admin.php" class="btn success">Home</a> </li>
        <li> <a href="add_person.php" class="btn success">Add Person</a> </li>
        <li> <a href="add_program.php?searchfield=program" class="btn success">Add Program</a> </li>
        <li> <a href="add_department.php?searchfield=department" class="btn success">Add Department</a> </li>
        <li> <a href="add_location.php?searchfield=location" class="btn success">Add Location</a> </li>
        <li> <a href="add_user.php?searchfield=users" class="btn success">Add User</a> </li>
        <li> <a href="fax.php" class="btn success">Fax Machines</a> </li>
        <li> <a href="manage_divisions.php" class="btn success">Divisions</a> </li>
        <li> <a href="change_password.php" class="btn success">Change My Password</a> </li>
    </ul>
    <div class="clear"></div>
</div>
<hr />
<?php print "<span style='font-size: 34px;' class='theme-color'>Administrator (</span><span style='font-size: 24px; color: #208EDD;'>" . $_SESSION["user_fullname"] . "</span><span style='font-size: 34px;' class='theme-color'>)</span>"; ?>
<form action = "admin.php" name="form_a" method="get">
    <p>
        <span style="color: #2C3C75;"> Search for:</span>
        <input type="text" id="srch" name="searchtext" value="<?php $_justsearched = isset($_GET['searchtext']) ? $_GET['searchtext'] : '';
print $_justsearched; ?>"  class="nice-field" size="30" onDblClick="clearit(this.id)"/><span style="color: #2C3C75;"> Category</span>
        <!--The values for each option are case sensitive and the get_search_results() relies on it -->
        <select name="searchfield" class="nice-field">
            <?php
            $categories = array("person" => "Person", "users" => "Users", "department" => "Department", "program" => "Program", "location" => "Location");
            $selected_category = $_GET["searchfield"];
            if ($selected_category) {
                $s = ucwords($selected_category); #First word to upper case (uc)
                print "<option  value='$selected_category'>$s</option>";
                foreach ($categories as $k => $v) {
                    if ($k !== $selected_category)
                        print "<option value='$k'>$v</option>";
                }
            }
            else {
                foreach ($categories as $k => $v) {
                    print "<option value='$k'>$v</option>";
                }
            }
            ?>
        </select>
        <input type="submit" name="submit" value="Search" class="btn primary"/>
    </p>
    <br />
    <div id="butts">
        <div style="width: 90px; float:left;"><input type="submit" value="Show All" name="lettersrch" class="btn primary" /></div>
        <?php include '_buttons.php'; ?>
    </div>
    <div style="clear:both"></div>
    <script type="text/javascript" >
        //Place mouse in search box
        $("#srch").focus();
    </script>
</form>