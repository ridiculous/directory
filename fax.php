<?php
ob_start();
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <?php
        include("chief.php");
        $fn = new Chief(); #access to chief functions
        $fn->is_it_legit(); #Validate the session is established and redirect to login if not
        $fn->is_authorized(); #make sure the user is admin
        $fn->print_head('Directory | Fax Machines');
        ?>
    </head>
    <body>
        <?php
        include 'partials/header_admin.php';
        $fn->show_msgs(); #display messages from updates and inserts, then clear session variables used for messages


        $statement = "SELECT id, fax_machine, fax_number FROM fax ORDER BY fax_machine";
        $result = $fn->query($statement);
        $row_num = 0;

        # Get the number of rows in the result, as well as the first row
        #  and the number of fields in the rows
        ?>
        <?php if ($result) : ?>
            <div class="clean-gray">
                <p>
                    Click Search or buttons above to begin (box can be empty). You can select from the drop down menu a category to search/manage. From there you may edit and/or delete the various records.
                </p>
            </div>
            <h1 class="theme-color" align="left">Fax Machines </h1>
            <hr />
            <div>
                <table cellspacing="3" id="mainTable" style="font-size: 18px;" frame='below' width="100%">
                    <tr>
                        <th class="t-h" width="8%">
                            <div style="padding-left:7px;padding-top:10px;height:30px;width:150px;">
                                <div>
                                    <a href="add_fax.php" title="Add fax machine" class="btn success">New Fax Machine</a>
                                </div>
                            </div>
                        </th>
                        <th class="t-h" width="40%"><b> Fax Machine</b></th>
                        <th class="t-h" width="40%"><b> Fax Number</b></th>
                    </tr>
                    <?php while ($faxmachine = $fn->fetch_hash($result)) : ?>
                        <?php $bcolor = ($row_num % 2) == 1 ? "#FFFFFF" : "#E2E0DC"; ?>
                        <tr style="background: <?php print $bcolor; ?>" onmouseover="(this.style.background='#AFCBD3')" onmouseout="(this.style.background='<?php print $bcolor; ?>')">    
                            <td align="center">
                                <form action="edit_fax.php" method="post">
                                    <input type="hidden" name="fax_id" id="fax_del_id" value="<?php print $faxmachine[0]; ?>" />
                                    <input type="image" src="images/edit-pencil.gif" name="" title="Edit record" alt="Edit record" />&nbsp;&nbsp;
                                    <input type="button" id="delete_fax" class="delete-btn" title="Delete record" alt="Delete record" />
                                </form>
                            </td>
                            <td align="left">
                                <?php print $faxmachine['fax_machine']; #Fax machine ?>
                            </td>
                            <td align="left">
                                <?php print $faxmachine['fax_number'];$row_num++; #Fax number ?>
                            </td>
                        </tr>
                    <?php endwhile; ?> 
                </table>
                <br /><div style='text-align: center'><span style='color: #0099CC; font-size: 18px;'> &raquo; <a href='#top'>Top</a> &laquo; </span></div>
                <br />
            </div>
        <?php else : ?>


            <h1>Oops, something went wrong</h1>


        <?php endif; ?>
        <script type="text/javascript">
            jQuery(function($){
                $(document).ready(function() {
                    init_fax_cut();
                });
                function init_fax_cut(){
                    if(!$('input#delete_fax').length)return;
                    $('input#delete_fax').bind('click', function() {
                        var answer = confirm("Are you sure you want to delete this fax number?");
                        if (answer){
                            var self = $(this);
                            $.get("sarge.php?delete_fax="+self.siblings('#fax_del_id').val(), function(data){
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
    </body>
    <br />
    <br />
    <?php include 'partials/footer.php'; ?>
</html>

