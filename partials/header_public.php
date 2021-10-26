<form action="index.php" name="a_form" method="get" class="directory">
    <span style='font-size: 34px;' id="dir" class='theme-color'>Directory</span>

    <div id="add-links" class='link-arrows inline'>
        <ul>
            <li id='lol'></li>
            <li><a href="#view-fax" id="view-fax-no">Fax Numbers</a></li>
            <li><a href="fall-2011-campus-directory.pdf" target="_blank">Download as PDF</a></li>
            <li><a href="http://www.hawaii.edu/dir/" target="_blank">UH System Directory</a></li>
        </ul>
    </div>
    <div id="fax-numbers">
        <a href="#" id="closeModal"><div id="close-tag">Close</div></a>
    </div>
    <p>
        Search for:
        <input type="text" id="srch" name="searchtext" value="<?php $_justsearched = isset($_GET['searchtext']) ? $_GET['searchtext'] : '';
print $_justsearched; ?>"  class="nice-field" size="22" alt="Enter in search criteria" title="Enter in search criteria" /> Category
        <select name="searchfield" class="nice-field" title="Pick a category to search by">
            <?php
            /*
             * This block of code creates the drop down menu for search parameters
             */
            #Initialize the array to hold the search params
            $categories = array("person" => "Person", "department" => "Department", "program" => "Program", "location" => "Location", "email" => "Email", "phone" => "Phone");
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

    <div id="butts">
        <br/>
        <?php include '_buttons.php'; ?>
    </div>
    <div style="clear:both;"></div>

    <div class="clean-gray" >
        <p>
            Enter search criteria, select a category and click "Search". Categories include: Person, Department, Program, Location, Email or Phone. Use the buttons to search a category alphabetically.</p>
    </div>
    <script type="text/javascript" >
        //Place mouse in search box
        $(document).ready(function(){
            $("#srch").focus();
            $('#fax-numbers').css('height',$(window).height()+'px');
            $('.link-arrows a').mouseenter(function() {
                $(this).addClass("hover-arrow");
            }).mouseleave(
            function() {
                $(this).removeClass("hover-arrow");
            }).click(function() {
                $(this).removeClass("hover-arrow")
            });
        });
    </script>
</form>