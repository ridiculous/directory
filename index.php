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
        $fn->print_head('University of Hawaii Maui College | Directory'); #Print title, links, scripts, and meta tags
        ?>
        <meta name="Description" content="University of Hawaii Maui College online directory was built by students of Applied Business and Information Technology." />
        <link rel="stylesheet" type="text/css" href="includes/homestyle.css" />      
        <style type="text/css">
            body {
                background: url(images/sandWave_bg.jpg) bottom no-repeat fixed;
            }
            .simplemodal-overlay{
                background-color: grey;
                z-index: 10000
            }
            .link-arrows li {
                padding-left: 5px;
                margin-bottom: 3px;
                margin-top: 3px;
                list-style: none;
            }
            .link-arrows a {
                text-decoration: none;
                color: #045FB4;
                font-weight: bold;
                font-size: 13px; /* 11px */
                padding-left: 16px; /* 12px */
                margin-bottom: 4px;
                margin-top: 3px;
                background: url('images/taro_links.gif') top left no-repeat; /* url('../images/arrowblue_over.gif') */
            }

            .link-arrows a:visited {
                text-decoration: none;
                color: #045FB4;
            }

            .link-arrows a:hover {
                text-decoration: underline;
                color: #026B9F;
                font-weight: bold;
            }
            .hover-arrow {
                background-position: bottom left !important; /* url('../images/arrowblue.gif') */
            }
            .inline li{
                display: inline;
            }
            #fax-numbers{
                z-index: 1;
                width:700px;
                display:none;
            }
            #fax-numbers, #fax-numbers div{
                background-color: #FFF;
            }
            #close-tag{
                height:20px;
                width:60px;
                background: url('images/x_button_close.png') left center no-repeat;
                padding-top: 10px;
                text-align: right;
            }
            #closeModal{
                padding-left: 0;
                color: #006699
            }
            .directory{
            }
            #add-links {
                position:absolute;
                right:25px;
                top:5px;
                width: 100%;
                text-align: right;
            }
            #add-links ul{
                margin : 0;
                padding: 0;
            }
            .marked {
                border-color:#BA3D10;
            }
            #warning{
                margin-bottom:-10px;
                font-size:14px;
                color:red;
                background-color:#F7D0A2;
                text-align:center;
                display:none;
            }
            #outer_title{
                display: none;
                width:auto;
                height:auto;
                border: 1px solid #0281b8;
                padding:5px;
                background: rgb(229,236,249);
                background: rgba(229,236,249,0.8);
                position:absolute;
                z-index: 999999999999;
                -moz-border-radius: 6px;
                -webkit-border-radius: 6px;
                border-radius: 6px;
            }
            .footer{
                background: url('images/footer_tile.jpg') repeat-x white;
                height:90px;
                font-family: Verdana, Arial, Helvetica, sans-serif;
                font-size: .8em;
                color: #333333;
                padding-top: 16px;
                padding-left: 15px;
            }
            #shade {
                width:1014px;
                height:100%;
                margin-left: auto;
                margin-right: auto;
            }
        </style>
        <script type="text/javascript">

            var _gaq = _gaq || [];
            _gaq.push(['_setAccount', 'UA-28257671-1']);
            _gaq.push(['_trackPageview']);

            (function() {
                var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
                ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
                var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
            })();

        </script>
    </head>
    <body>
        <div id="shade">
            <div id="container"><a name="top"></a>
                <table border="0" cellpadding="0" cellspacing="0" id="containing-table">
                    <tr>
                        <td colspan="3" valign="top" style="height:75px;">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td valign="top" width="1000" height="75">
                                        <div style="height:75px;">
                                            <div class="small-seal">
                                                <a href="http://maui.hawaii.edu/" title="UHMC Home">
                                                    <img src="images/small_seal2.jpg" id="small_seal" alt="UHMC Home" />
                                                </a>
                                            </div>
                                            <div>
                                                <ul id="topNav">
                                                    <li><a href="https://myuh.hawaii.edu/cp/home/displaylogin" tabindex="6" target="_blank" title="MyUH Portal">MyUH</a></li>
                                                    <li><a href="https://laulima.hawaii.edu/portal" target="_blank" tabindex="5" title="Laulima">Laulima</a>|</li>
                                                    <li><a href="https://mail.google.com/a/hawaii.edu" tabindex="4" target="_blank">Email</a>|</li>
                                                    <li><a href="/" tabindex="3" title="College directory">Directory</a>|</li>
                                                    <li><a href="http://maui.hawaii.edu/?s=student&p=schedule" tabindex="1" title="Check available classes">Classes</a>|</li>
                                                    <li><a href="http://maui.hawaii.edu/" tabindex="0" title="Home page">Home</a>|</li>
                                                </ul>
                                            </div>
                                            <div style="text-align:right;padding-right:20px; padding-top:10px;">
                                                <form action="http://maui.hawaii.edu/search/searched.php" class="search-site" id="cse-search-box">
                                                    <div>
                                                        <input type="hidden" name="cx" value="014270164843273891507:z3n7b4f82bg" />
                                                        <input type="hidden" name="cof" value="FORID:11" />
                                                        <div style="float:right;margin-top:-3px;">
                                                            <input type="image" src="images/search-button.png" style="border:none" name="sa" tabindex="8" title="Search Maui College web site" alt="Search Maui College web site" height="20" width="60" border="0" id="submit_search" />
                                                        </div>
                                                        <div style="float:right">
                                                            <input type="text" id="search_box" name="q" tabindex="7" title="Enter search criteria" alt="Enter search criteria" size="25" />
                                                        </div>
                                                        <div class="reset"></div>
                                                    </div>
                                                </form>
                                                <script type="text/javascript" src="http://www.google.com/coop/cse/brand?form=cse-search-box&lang=en"></script>
                                            </div>
                                        </div>
                                        <div style="height:14px;width:195px;background: url(images/accordion_shade.jpg) no-repeat; background-position: -6px 0;float:left">
                                        </div>
                                        <div style="height:14px;width:190px;background: url(images/accordion_shade.jpg) no-repeat; background-position: -11px 0;float:left">
                                        </div>
                                        <div style="height:14px;width:190px;background: url(images/accordion_shade.jpg) no-repeat; background-position: -11px 0;float:left">
                                        </div>
                                        <div style="height:14px;width:190px;background: url(images/accordion_shade.jpg) no-repeat; background-position: -11px 0;float:left">
                                        </div>
                                        <div style="height:14px;width:190px;background: url(images/accordion_shade.jpg) no-repeat; background-position: -11px 0;float:left">
                                        </div>
                                        <div style="height:14px;width:45px;background: url(images/accordion_shade.jpg) no-repeat; background-position: -11px 0;float:left">
                                        </div>
                                        <div style="clear:both;"></div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <?php
                        if (isset($_GET["g"]) && $_GET["g"]) {
                            switch ($_GET["g"]) {
                                case "login_att";
                                    include "partials/_login_.php";
                                    break;
                                default:
                                    include "partials/_main.php";
                            }
                        }else
                            include "partials/_main.php";
                        ?>
                    </tr>
                    <tr>
                        <?php
                        include "includes/config.php";
                        $footer_width = 1000;
                        $column_span = 3;
                        footer($column_span, $footer_width);
                        ?>
                    </tr>
                </table>
            </div>
        </div>
        <script type="text/javascript" >
            jQuery(function($){

                $(document).ready( function(){

                    init_detail_helper();
                    init_modal_viewer();
                    reveal_shadow();//Reveal the shadow on container div
                    checkSearchBox();//stop search if empty
                    $('a, .helper, #srch').tipper({
                        outerId: "outer_title",
                        innerId: "inner_title",
                        topOffset: 21,
                        leftOffset: -5,
                        delay:1200,
                        speed: 200
                    });//Replaces the default box for titles of links
                                       
                });

                function init_detail_helper(){
                    if(!$('.helper').length){
                        return;
                    }else{
                        $('.helper').bind('click', function(){
                            var $dom = $(this).parent(".records").parent("tr").next();
                            if($dom.hasClass("showed")){
                                $dom.removeClass("showed").hide();
                                $(this).attr("alt",'View details for person');
                                $(this).attr("title",'View details for person');
                            }else{
                                $dom.addClass('showed').show("slow");
                                $(this).attr("alt", 'Hide details');
                                $(this).attr("title", 'Hide details');
                            }
                        });
                    }
                }


                function checkSearchBox() {
                    if (!$('#submit_search').length)return;
                    $('#submit_search').bind('click', function(e) {
                        if (!$.trim($('#search_box').val())) {
                            e.preventDefault();
                            return false;
                        }
                    });
                }
                function reveal_shadow(){
                    if(!$('div#shade').length){
                        return
                    }else{
                        $('#shade').css("background","url('images/shade.png') center repeat-y");
                    }
                }
                function init_modal_viewer(){
                    if(!$('#view-fax-no').length){
                        return;
                    }else{
                        $('#view-fax-no').bind('click', function(){
                            $("#fax-numbers").load('_fax_machine_and_numbers_list.php', function(response, status, xhr){
                                if (status == "error") {
                                    var msg = "<p style='font-size:24px;color:white;'>Oops, fax numbers are unavailable</p>";
                                    $("#fax-numbers").html(msg);
                                }
                            }).modal(
                            {
                                overlayClose:true,
                                minHeight: "90%",
                                maxHeight: "90%",
                                minWidth: 700,
                                maxWidth: 725,
                                onOpen: function (dialog) {
                                    dialog.overlay.fadeIn(300, function () {
                                        dialog.container.slideDown(300, function () {
                                            dialog.data.fadeIn(300);
                                        });
                                    });

                                },
                                onClose: function (dialog) {
                                    dialog.data.fadeOut(200, function () {
                                        dialog.container.hide(200, function () {
                                            dialog.overlay.slideUp('slow', function () {
                                                $.modal.close();
                                            });
                                        });
                                    });
                                }
                            });
                        });
                    }
                }
            });
        </script>
    </body>
</html>
<?php ob_end_flush(); ?>