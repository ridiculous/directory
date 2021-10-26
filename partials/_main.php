<td width="100%"  valign="top">
    <div style="margin:20px">
        <div class="shiftcontainer">
            <div class="shadow">
                <div class="innerdiv">
                    <?php
                    include 'partials/header_public.php';
                    $newSearch = isset($_GET["searchtext"]) ? $fn->sql_escape($_GET["searchtext"]) : false ;
                    $search_param = isset($_GET["searchfield"]) ? $fn->sql_escape($_GET["searchfield"]) : false;
                    $lettersrch = isset($_GET["lettersrch"]) ? $fn->sql_escape($_GET["lettersrch"]) : false;
                    /*
                      This is the new (php v5.2-3) method for executing a sql query with MS SQL Server. Option for "scrollable" keyset is specified
                      >>sqlsrv_query($result, array(), array("Scrollable" => 'keyset') << sqlsrv
                      >>Get the number of rows in the result, as well as the first row  and the number of fields in the rows
                      >>$row = $fn->fetch($exec_query, SQLSRV_FETCH_ASSOC); #Fetch next row in associative form (k=>v)
                      >>sqlsrv_fetch_array($search_results, SQLSRV_FETCH_ASSOC); Fetch next row in associative form ($k=>$v)
                     */
                    #Find the correct query to use based on users search criteria
                    $result = (!$lettersrch) ? $fn->get_public_search_results($newSearch, $search_param) : $fn->get_public_letter_query($lettersrch, $search_param);
                    $search_results = $fn->query($result);
                    ?>
                    <?php if ($fn->has_rows($search_results)) : ?>
                        <table class='nice-table' cellspacing='1' cellpadding='1'>
                            <tr align = 'left' style='background: #CCCCCC;' class='header-row'>
                                <th style='width: 12%;'align = 'center' class='t-h'>Person</th>
                                <th style='width: 12%;'align = 'center' class='t-h'>Email</th>
                                <th style='width: 5%;' align = 'center' class='t-h'>Mail</th>
                                <th style='width: 7%;' align = 'center' class='t-h'>Phone</th>
                                <th style='width: 9%;' align = 'center' class='t-h'>Location</th>
                                <th style='width: 30%;' align = 'center' class='t-h'>Div/Dept/Program</th>
                                <th style='width: 10%;' align = 'center' class='t-h'>Position</th>
                            </tr>
                        <?php
                        #Print out rows returned from search query
                        $row_num = 0;
                        while ($row = $fn->fetch_hash($search_results)) {
                            $num_fields = $fn->fields($search_results);
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
                                $value = htmlspecialchars($values[$index]); #Get next value from current row array
                                if ($index == 0) {
                                    #This is where the details content is designed and the name is cleaned up
                                    $notes = $fn->find_person_details($value);
                                } elseif ($index == 1) {
                                    print "<td class='records'>$value";
                                    if (trim($notes[0]) != '' || trim($notes[1]) != '') {
                                        print "<br />
                                            <span class='helper' alt='View details for person' title='View details for person' style='font-size: 10px; color: blue; float: left;'>Details</span> ";
                                    }
                                    print "</td>";
                                } elseif ($index == 2) {
                                    print "<td class='records'><a href=mailto:" . $value . ">" . $value . "</a></td> ";
                                } elseif ($index == 7) {
                                    print "<td class='records'><div style='width: 90%; float: left;'>" . $value . "</td>";
                                } elseif ($index == 8) {
                                    print "";
                                } else {
                                    print "<td class='records'>" . $value . "</td> ";
                                }
                            }
                            print "</tr>";
                            #Build the details view for each person and send out details
                            if (trim($notes[0]) != '' || trim($notes[1]) != '') {
                                $fn->print_details($notes);
                            }
                        }
                        ?>
                    </table>
                    <?php
                        #Print the TOP link to page up, if rows > 11
                        if ($row_num > 11)
                            print "<br /><div style='text-align: center'><span style='color: #0099CC; font-size: 14px;'>>> <a href='#top'>Top</a> <<</span></div>";
                    ?>
                    <?php
                        else :
                            if ($lettersrch)
                                print "<p style='font-size:18px;margin-left:10px;'>No records found for letter search of \"$lettersrch\" in the <b>$search_param</b> category</p>";
                            else
                                print "<p style='font-size:18px;margin-left:10px;'>No records found for <u>\"$newSearch\"</u> in the <b>$search_param</b> category </p>";
                    ?>
                    <?php endif; ?>
                    
                    <br />
                </div>
            </div>
        </div>
    </div>
</td>
