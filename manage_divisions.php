<?php
ob_start();
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <?php
        include("chief.php");
        include("models/division.php");
        $fn = new Chief(); #access to chief functions
        $div = new Division();
        $fn->is_it_legit(); #Validate the session is established and redirect to login if not
        $fn->print_head('Directory | Divisions'); #meta, link, script tags
        ?>
    </head>
    <body>
        <?php
        #include header partial based on access level
        $fn->is_admin($_SESSION["u_access"]) ? include 'partials/header_admin.php' : include 'partials/header.php';

        $fn->show_msgs(); #use values from session variables to display update and insert messages, then clear
        $divisions = $div->_all();
        $row_num = 0;
        ?>
        <div class="clean-gray">
            <p>
                Click Search or buttons above to begin (box can be empty). You can select from the drop down menu a category to search/manage. From there you may edit and/or delete the various records.
            </p>
        </div>
        <h1 class="theme-color" align="left">Divisions </h1>
        <hr />
        <div>
            <?php if ($divisions) : ?>
                <table cellspacing="3" id="mainTable" style="font-size: 18px;" frame='below' width="100%">
                    <tr>
                        <th class="t-h" width="8%">
                            <div style="padding-left:7px;padding-top:10px;height:30px;width:150px;">
                                <div>
                                    <a href="add_division.php" title="Add division" class="btn success">New Division</a>
                                </div>
                            </div>
                        </th>
                        <th class="t-h" width="40%"><b> Division Name</b></th>
                    </tr>
                    <?php while ($division = $fn->fetch_hash($divisions)) : ?>
                        <?php $bcolor = ($row_num % 2) == 1 ? "#FFFFFF" : "#E2E0DC"; ?>
                        <tr style="background: <?php print $bcolor; ?>" onmouseover="(this.style.background='#AFCBD3')" onmouseout="(this.style.background='<?php print $bcolor; ?>')">
                            <td align="center">
                                <a href="edit_division.php?id=<?php print $division['id']; ?>" title="Edit record" alt="Edit record" style="text-decoration:none">
                                    <image src='images/edit-pencil.gif' title='Edit this record' alt='Edit this record' name ='' />
                                </a>
                                &nbsp;&nbsp;
                                <input type="button" class="delete-btn" rel="<?php print $division['id']; ?>" title="Delete record" alt="Delete record" />
                            </td>
                            <td align="left">
                                <?php print $division['name'];
                                $row_num++; ?>
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
        <div>
<?php include 'partials/footer.php'; ?>
        </div>
        <script type="text/javascript">
            jQuery(function($){
                $(document).ready(function() {
                    init_division_delete();
                });
                function init_division_delete(){
                    $('input.delete-btn').bind('click', function() {
                        var division = $(this), division_id = division.attr('rel');
                        if (confirm("Are you sure you want to delete this Division?")){                         
                            $.get("sarge.php?delete_division="+division_id, function(data){
                                if(data != ''){
                                    alert("Division cannot be deleted. It is likely still in use by a department");
                                }else{
                                    division.parent().parent().remove();
                                }
                            });
                        }
                    });
                }
            });
        </script>
    </body>
</html>