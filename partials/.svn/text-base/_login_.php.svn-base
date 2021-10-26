<td width="996"  valign="top" style="background-color: #C0D5DA;">
    <div style="margin-left:270px;margin-right:220px;margin-top:100px;margin-bottom: 100px;">
        <div class="shiftcontainer">
            <div class="shadow" style="width:422px;height:222px;">
                <div class="innerdiv" style="width:400px;height:200px;border-color:grey">
                    <div>
                        <a href="index.php">&laquo;  Back</a>
                    </div>
                    <div style="text-align:center">
                        <span style="font-size:22px; color:grey">Administrator</span>
                    </div>
                    <br/>
                    <div id="warning">Username/Password cannot be left blank</div>
                    <?php
                    $login_attempt = isset($_SESSION["login_fail"]) ? $_SESSION["login_fail"] : 0;
                    $last_username = isset($_SESSION["last_uname"]) ? $_SESSION["last_uname"] : '';
                    if ($login_attempt == 1) {
                        print "<div id='login-fail' style='margin-bottom:-10px;font-size:14px;color:red;background-color:#F7D0A2;text-align:center'>
                            " . $_SESSION["login_msg"] . "
                            </div>";
                    }
                    ?>
                    <br/>
                    <form action="admin.php" method="post">
                        <div>
                            <div style="float:left;width:150px;text-align:right">
                                <!--username field turnoff auto complete-->
                                <p style="text-align:left">
                                    <span style="margin-left: 30px;padding-right:20px;font-size: 20px; font-family: arial; color: #145260;"> Username:</span>
                                </p>
                                <p style="text-align:left">
                                    <span style="margin-left: 30px;padding-right:20px;font-size: 20px; font-family: arial; color: #145260;"> Password: </span>
                                </p>
                            </div>

                            <div id="login" style="float:left;width:200px">
                                <p>
                                    <input type="text" name="uname" size="20" id="user_id" autocomplete="off" value="<?php print $last_username; ?>" />
                                </p>
                                <p>
                                    <input type="password" name="pword" size="20" autocomplete="off"/>
                                </p>
                            </div>
                            <div style="clear:both;"></div>
                            <div>
                                <p style="text-align:center">
                                    <input type="submit" value="Log in" style="color: blue;"/>
                                </p>
                            </div>
                        </div>
                    </form>
                    <script type="text/javascript">
                        var setfocuz = document.getElementById('user_id');
                        setfocuz.focus();
                        jQuery(function($){
                            $('form').submit( function() {
                                var check = true;
                                $('#login-fail').hide();
                                $('#login input').each(function()
                                {
                                    $(this).removeClass("marked");
                                    if(!$(this).val())
                                    {
                                        $(this).addClass("marked");
                                        check = false;
                                    }

                                })
                                if(!check){
                                    $('#warning').show();
                                }
                                return check;
                            });
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
    <?php
                    session_unset();
                    session_destroy();
    ?>
</td>