<?php
#Functions and variables used in the site
$current_server = $_SERVER['SERVER_NAME'];
$current_url = $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF'];
$target_host = ($current_server == "localhost") ? "localhost/xampp/uhmc_website/" : "maui.hawaii.edu";

/* The functions below are for printing out commonly used html blocks */

#Prints the top portion of the basic div container
function box_start() {
    print '<div class="basic-box">
            <div class="box-left">
                 <div class="box-right">
                   <div class="box-top">
                    <div class="box-top_right">
                     <div class="box-top_left">
                     </div>
                    </div>
                   </div>';
}
#Prints the bottom divs for the basic container
function box_end() {
    print ' <div class="box-bottom">
                  <div class="box-bottom_right">
                     <div class="box-bottom_left">
                     </div>
                    </div>
                   </div>
                  </div>
                 </div>
                </div>';
}
#Print out the footer row
function footer($column_span,$footer_width){
    print "<td colspan='" . $column_span . "'><table width='" . $footer_width . "px' border='0' cellpadding='0' cellspacing='0'>
                            <tr>
                                <td valign='top' class='footer'>
                                    <b>©2007 - 2011 University of Hawaii Maui  College</b><br />
    	310 Ka'ahumanu Ave | Kahului, HI 96732-1617  | Phone: (808) 984-3500 | <a href='http://maui.hawaii.edu/?s=faculty&p=emergency_proc'>Emergency Procedures</a> | <a href='http://www.mauicc.biz/helpdesk/step_one.asp' target='_blank'>Feedback Form</a><br />
    	An Equal Opportunity/Affirmative Action Institution<br />
    	People requiring an alternate format, call (808) 984-3267 for assistance
         <br />
        Comments or questions? Please direct them to <a href='mailto:uhmchelp@hawaii.edu'>uhmchelp@hawaii.edu</a>
                                </td>
                                <td valign='top' class='footer'>
                                <b>Campus Security (808) 984-3255<br />
                                 <a href='http://maui.hawaii.edu/admserv/UHMC%20Annual%20Crime%20Report%202010.pdf' target='_blank' title='Safety/Security Crime Report'>Safety/Security Crime Report</a><br />
                                        Disabiltiy Resources</b>
                                </td>
                            </tr>
                        </table>
                        </td>";
}
?>