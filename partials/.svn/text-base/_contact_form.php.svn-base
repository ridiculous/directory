<?php

function fieldNames($txt) {
    print '<div style="width: 100px; float: left; text-align: left; padding-left: 20px;">';
    print $txt;
    print '</div>';
}

function field($fhtml) {
    print '<div style="width: 235px; float: left;">';
    print $fhtml;
    print '</div><div style="clear:both"></div>';
}

function submitBtn() {
    print '<div style="width: 100px; float: left; text-align: left; padding-left: 20px;height:2px;"></div>
           <div style="width: 235px; float: left;">
            <input type="submit" class="submit-contact-form" value="Submit Form" style="color:#0280BF;padding:4px;"/>&nbsp;&nbsp;or&nbsp;&nbsp;<span class="assist_close" style="text-decoration: underline;cursor:pointer;">cancel</span>
           </div>
           <div style="clear:both"></div>';
}
?>
<a href="javascript:void(0)" id="init_contact_form">Contact us</a>|
<div id="master-wrapper" style="display: none;float: right;padding-right: 424px;">
    <div style="font-family: Verdana, Arial, sans-serif;position:absolute;z-index:99999999;width: 400px;background: #FFF;border: 2px solid #028091;margin: auto;padding: 10px;-webkit-border-radius:5px;-moz-border-radius: 5px;border-radius:5px;-webkit-box-shadow:rgba(0, 0, 0, 0.4) -6px 5px 5px 2px;-moz-box-shadow:rgba(0, 0, 0, 0.4) -6px 5px 5px 2px;box-shadow:rgba(0, 0, 0, 0.4) -6px 5px 5px 2px;">
        <div id="general_contact_topics">
            <div style="color:#028091;padding:5px;font-size:20px;">How can we help you?</div><br/>
            <p style="text-align:center;font-size:12px;line-height: 0;font-family: Verdana, Arial, sans-serif;padding: 0 0 4px 0;">Please select your area of concern and fill out the form below</p><br/>
            <ul style="margin:0;padding:0;">
                <li style="float:left;width:32%;padding-left: 6px;list-style: none;">
                    <input type="radio" name="contact_options" title="student" id="student_contact_button"/>
                    <span style="font-size:14px"> Academic </span>
                </li>
                <li style="float:left;width:32%;list-style: none;">
                    <input type="radio" name="contact_options" title="technical" id="technical_contact_button"/>
                    <span style="font-size:14px"> Technical </span>
                </li>
                <li style="float:left;width:32%;list-style: none;">
                    <input type="radio" name="contact_options" title="employment" id="employment_contact_button"/>
                    <span style="font-size:14px"> Employment </span>
                </li>
            </ul>
            <div style="clear:both"></div>
        </div>
        <br/>

        <div class="contact-forms-wrap">
            <div class="solid-contact-form" rel="student_contact_button" style="display: none">
                <form action="process-form.php" method="post" name="student_contact">
                    <ul style="margin:0;padding:0;">
                        <li style="padding: 7px 0px;list-style: none;font-size:14px;line-height: 16px;">
                            <?php fieldNames('Name'); ?>
                            <?php field('<input type="text" size="20" name="full_name"/>') ?>
                        </li>

                        <li style="padding: 7px 0px;list-style: none;font-size:14px;line-height: 16px;">
                            <?php fieldNames('Email'); ?>
                            <?php field('<input type="text" size="20" name="email"/>') ?>
                        </li>
                        <li style="padding: 7px 0px;list-style: none;font-size:14px;line-height: 16px;">
                            <?php fieldNames('Subject'); ?>
                            <?php field('<select name="subject" title="Select a Subject" >
                            <option value="registration"> Registration </option>
                            <option value="schedule"> Class Schedule </option>
                            <option value="counseling"> Counseling </option>
                            <option value="facility"> Facility Use </option>
                            <option value="general"> General Question </option>
                        </select>') ?>
                        </li>
                        <li style="padding: 7px 0px;list-style: none;font-size:14px;line-height: 16px;">
                            <?php fieldNames('Message'); ?>
                            <?php field('<textarea rows="5" cols="30" name="comment"></textarea>') ?>
                        </li>
                        <li style="padding: 7px 0px;list-style: none;font-size:14px;line-height: 16px;">
                            <?php submitBtn(); ?>
                        </li>
                    </ul>
                </form>
                <span class="contact-form-error-msg" style="color : #0280BF;">*All fields required</span>
            </div>
            <div class="solid-contact-form" rel="technical_contact_button" style="display: none">
                <form action="process-form.php" method="post" name="technical_issues">
                    <ul style="margin:0;padding:0;">
                         <li style="padding: 7px 0px;list-style: none;font-size:14px;line-height: 16px;">
                            <?php fieldNames('Name'); ?>
                            <?php field('<input type="text" size="20" name="full_name"/>') ?>
                        </li>
                         <li style="padding: 7px 0px;list-style: none;font-size:14px;line-height: 16px;">
                            <?php fieldNames('Email'); ?>
                            <?php field('<input type="text" size="20" name="email"/>') ?>
                        </li>
                         <li style="padding: 7px 0px;list-style: none;font-size:14px;line-height: 16px;">
                            <?php fieldNames('What\'s wrong?'); ?>
                            <?php field('<textarea rows="5" cols="30" name="comment"></textarea>') ?>
                        </li>
                         <li style="padding: 7px 0px;list-style: none;font-size:14px;line-height: 16px;">
                            <?php submitBtn(); ?>
                        </li>
                    </ul>
                </form>
                <span class="contact-form-error-msg" style="color : #0280BF;">*All fields required</span>
            </div>
            <div class="solid-contact-form" rel="employment_contact_button" style="display: none;">
                <form action="process-form.php" method="post" name="employment_contact">
                    <ul style="margin:0;padding:0;">
                         <li style="padding: 7px 0px;list-style: none;font-size:14px;line-height: 16px;">
                            <?php fieldNames('Name'); ?>
                            <?php field('<input type="text" size="20" name="full_name"/>') ?>
                        </li>
                         <li style="padding: 7px 0px;list-style: none;font-size:14px;line-height: 16px;">
                            <?php fieldNames('Email'); ?>
                            <?php field('<input type="text" size="20" name="email"/>') ?>
                        </li>
                         <li style="padding: 7px 0px;list-style: none;font-size:14px;line-height: 16px;">
                            <?php fieldNames('Job Type'); ?>
                            <?php field('
                        <select name="subject" title="Select a Job Type" >
                            <option value="employment"> Student Employment </option>
                            <option value="faculty"> Faculty and Staff </option>
                            <option value="other"> Other </option>
                        </select>
                            ') ?>
                        </li>
                         <li style="padding: 7px 0px;list-style: none;font-size:14px;line-height: 16px;">
                            <?php fieldNames('Comments'); ?>
                            <?php field('<textarea rows="5" cols="30" name="comment"></textarea>') ?>
                        </li>
                         <li style="padding: 7px 0px;list-style: none;font-size:14px;line-height: 16px;">
                            <?php submitBtn(); ?>
                        </li>
                    </ul>
                </form>
                <span class="contact-form-error-msg" style="color : #0280BF;">*All fields required</span>
                <input type="hidden" value="<?php print $_SERVER['REQUEST_URI']; ?>" id="contact_request_uri" />
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    (function($) {
        $(document).ready(function() {
            init_contact_form();
        });
        function init_contact_form() {
            if (!$('#general_contact_topics').length)return;//exit function

            var wrap = $('#master-wrapper'), master = wrap.clone();
            $('#master-wrapper').remove();
            $('<div id="contact_form_container"></div>').insertAfter($('ul#topNav').parent());
            $('#contact_form_container').html(master);
            //bind click event
            var o = {
                contactForm  : $('#init_contact_form'),
                contactTypes : $('#general_contact_topics input[type=radio]'),
                parentLi     : '',
                marked       : 'marked-active'
            },
            f = {
                formToggle : function() {
                    o.parentLi = $(this).parent('li');
                    if (o.parentLi.hasClass(o.marked)) {
                        o.parentLi.removeClass(o.marked);
                        master.hide();
                    }
                    else {
                        o.parentLi.addClass(o.marked);
                        master.show();
                    }
                },
                formClose : function() {
                    o.contactForm.trigger('click');
                },
                clearAll  : function() {
                    o.contactTypes.each(function() {
                        this.checked = false;
                    });
                },
                validations: function() {
                    o.flag = true;
                    $('input[type=text], textarea, select', this).each(function() {
                        var b = $(this).css('border'), fieldname = $(this).attr('name'), fval = $(this).val();
                        if (!fval) {
                            o.flag = false;
                            $(this).css('border', '2px solid #0280BF');
                            $('.contact-form-error-msg').show();
                        }
                        else {
                            if(fieldname == 'email'){
                                if(f.checkEmail(fval) == true){
                                    o.fieldData[0][fieldname] = fval;
                                    $(this).css('border', b);
                                    $('.email-error-msg-wrap').remove();
                                }else{
                                    o.flag = false;
                                    $(this).css('border', '2px solid #0280BF');
                                    if(!$('.email-error-msg-wrap').length)$('<div class="email-error-msg-wrap" style="padding-bottom:4px;font-size:12px;color:#0280BF;">Please provide a valid email</div>').insertBefore($(this));
                                }
                            }else{
                                o.fieldData[0][fieldname] = fval;
                                $(this).css('border', b);
                            }//if(!$(this).parent().hasClass("email-error-msg-wrap"))
                        }
                    });
                    return o.flag;
                },
                checkEmail : function(email) {
                    var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                    if (!filter.test(email))
                        return false;
                    else
                        return true;
                }
            }
            f.clearAll();
            o.contactForm.bind('click', function() {
                f.formToggle.call(this);
            });
            $('.assist_close').bind('click', function() {
                o.contactForm.trigger('click');//f.formClose();
            });
            $('.solid-contact-form form').submit(function() {
                o.flag = true;
                o.fieldData = [
                    {
                        full_name   : '',
                        email       : '',
                        subject     : '',
                        comment     : '',
                        request_uri : $('#contact_request_uri').val(),
                        contact_type: $(this).attr('name')//form name
                    }
                ];
                if (f.validations.call(this)) {
                    $('.submit-contact-form', this).val('Sending...').unbind();
                    $('.contact-form-error-msg').hide();
                    $.ajax({
                        url: '/includes/submit_contact.php',
                        cache: false,
                        type: 'POST',
                        data: {fields: o.fieldData[0]},
                        success: function(full_name) {
                            master.find('div').html('<div style="font-size:16px;text-align: center;">Thank you for contacting us, '+full_name+'.</div>');
                            setTimeout(function() {
                                master.fadeOut();
                                o.parentLi.removeClass(o.marked);
                            }, 3000);
                        },
                        error: function() {
                            alert('Oops, something went wrong. Please refresh the page and try again.');
                        }
                    })
                }
                return false;
            })
            o.contactTypes.bind('click', function() {
                var self_id = $(this).attr('id');
                $('.contact-forms-wrap .solid-contact-form:not([rel=' + self_id + '])').slideUp();
                $('.contact-forms-wrap').find('.solid-contact-form[rel=' + self_id + ']').slideDown();
            });
        }
    })(jQuery);
</script>