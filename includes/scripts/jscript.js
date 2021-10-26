//Refresh the current page
function reloadPage(){
    location.reload(true);
}

//reminder function
function reminder(val,val2){
    if(val =='' && val2 !== 'noContact'){
        var posit = document.getElementById(val2);
        posit.value = val2;
        if(val2 == 'Position..')
            posit.style.color = 'grey';
    }
}
function clearit(id){
    var i = document.getElementById(id);
    i.value = '';

}
(function ($){
    $(document).ready(function() {
        add_titles();
        printOut();
        $('a').tipper({
            outerId: "outer_title",
            innerId: "inner_title",
            topOffset: 21,
            leftOffset: -5,
            delay:1200,
            speed: 200
        });//Replaces the default box for titles of links
        timesUp(); //hide alert messages after delay 
        init_enhance_btns(); //grow letter buttons on mouse over
        init_department_helper();
        init_user_role_helper();
        
    });
    function init_department_helper(){
        if(!$('.clickhere').length){
            return;//exit function
        }
        var openLink = $('.clickhere'),
        cancelLink = $('#_cancel'),
        hidden = $('#only_department_selected'),
        pro = $('#program_list'),
        depMenu = $('#department_list'),
        label = $('#program_label'), 
        fn = {
            cancel : function(){
                label.html('Program');
                $('#only_department').slideUp('fast', function() {
                    pro.removeAttr("disabled").show();
                    hidden.val(0);
                    $('#_department').show();
                });   
            },
            open : function(){
                pro.attr("disabled","disabled").hide();
                hidden.val(depMenu.val());
                $('#_department').hide();
                $('#only_department').slideDown('fast');
                label.html('Department');
            }
        }
        openLink.bind('click', function(e) {
            fn.open();  
        });
        cancelLink.bind('click',function(){
            fn.cancel();
        });
        depMenu.bind('change',function(){
            hidden.val(depMenu.val()); 
        });
        if(hidden.val() != 0) openLink.trigger('click');
    }
    
    function init_user_role_helper(){
        if(!$('#role_name').length){
            return;//exit function
        }
        var division_btn = $('#_division'),
        dept_btn = $('#_department'),
        hidden_field = $('#users_role'),
        division_wrap = $('.division-select'),
        department_wrap = $('.department-select'),
        label = $('#role_name'),
        admin_btn = $('#_admin'),
        select_wrap = $('.generic-form.select-wrap'),
        dept_list = $('#department_list'),
        div_list = $('#division_list'),
        f = {
            department : function(){
                division_wrap.slideUp('fast');
                select_wrap.slideDown('fast', function(){
                    department_wrap.slideDown('fast');
                });
                hidden_field.val(dept_list.val());
                label.html('(Department)');
            },
            division : function(){
                department_wrap.slideUp('fast');
                select_wrap.slideDown('fast', function(){
                    division_wrap.slideDown('fast');
                });
                label.html('(Division)');
                hidden_field.val(0);
            },
            admin   : function(){
                division_wrap.slideUp('fast');
                department_wrap.slideUp('fast');
                label.html('(Administrator)');
                select_wrap.slideUp('fast');
                hidden_field.val(-1);
            }
        }
        admin_btn.bind('click',function(){
            f.admin();
        });
        dept_btn.bind('click',function(){
            f.department();
        });
        division_btn.bind('click', function(e) {
            f.division();
        });
        div_list.bind('change',function(){
            hidden_field.val(0);
        });
        dept_list.bind('change',function(){
            hidden_field.val(dept_list.val());
        });
    }


    function timesUp(){
        setTimeout(function(){
            if($('#outer').length)$('#outer').fadeOut();
            if($('#outer-warn').length)$('#outer-warn').fadeOut();
        },4500);
    }
    
    function init_enhance_btns(){
        if(!$('#butts').length)return;
        var url  = window.location.toString(),
        btns = $('#butts .nice-btn'),
        cls  = 'enhance',
        f    = {
            addIt   : function(){
                $(this).addClass(cls);
            },
            removeIt: function (){
                $(this).removeClass(cls);
            }
        };
        
        btns.each(function(){
            var self = $(this).val(), pattern  = new RegExp('(lettersrch='+self+')$');
            if(url.match(pattern))f.addIt.call(this);
        });
        btns.bind('mouseover', function(){
            f.addIt.call(this);
        }).bind('mouseout', function(){
            var self = $(this).val(),pattern  = new RegExp('(lettersrch='+self+')$');
            if(!url.match(pattern))
                f.removeIt.call(this);
        });
    }
    
    function printOut(){
        if(!$('#dir').length)return;
        var delay = 4000;
        $('#dir').bind('mousedown', function (e){
            if(!$('#logged').length){
                var that = $(this);
                $(this).data("delay",
                {
                    "leavePage": true,
                    "todo": setTimeout(function (e) {
                        $("#lol").html("<a href='?g=login_att' target='_blank'>Log in</a>");
                        that.data("delay").leavePage = false;
                    }, delay)
                }
                )
            }
            return false;
        }).bind('click mouseleave', function () {
            clearTimeout($(this).data("delay").todo);
            return $(this).data("delay").leavePage;
        });
    }

    function add_titles(){
        var A = $('a');
        A.each(function(){
            var $title = $(this).attr("title"),
            $content = $(this).html(),
            img_alt = $(this).find('img').attr("alt");
            if(img_alt){
                $(this).attr("title", img_alt);
            }else if(!$title){
                $(this).attr("title", $content);
            }
        });
    }
    /*
     * Tipper - jQuery plugin 1.3
     * Author - UH Maui College IT
     *        - Ryan Buckley <rbuckley@hawaii.edu>
     *
     * Last Update - 7/5/2011
     *
     * The tool tip plugin, Tipper.
     * Takes a specified attribute <default : title> of an element,
     * Displays the text/html in a div that slides down relative to cursor position.

     * USAGE EXAMPLES:
   $('#rightNav a').tipper({
            outerId: "hover_outer",
            innerId: "hover_inner",
            otherAttr: "name",
            speed: 400
        });
     *
    $('a').tipper({
            outerId: "outer_title",
            innerId: "inner_title",
            topOffset: 21,
            leftOffset: -5,
            delay:1200,
            ignore: ["spotlight","rightNav","relatedLinks"], //ignore ID's
            ignoreCoverage: 7, //Searches 7 DOM levels for ID's to ignore
            speed: 200
        });
     */

    $.fn.tipper = function( opt, func ) {
        //Set default options
        var settings = {
            delay      : 500, //Delay slideDown() of tipper
            speed      : "fast", //slide down speed
            topOffset  : 0, //px
            leftOffset : 15, //px
            outerId    : "outer_tipper", //ID for the outer div of tipper
            innerId    : "inner_tipper",
            mainAttr   : "title", //Attribute that has content for $.tipper message
            otherAttr  : false, //Other Attribute to use as message
            ignore     : false, //ID of element for tipper to ignore
            ignoreCoverage : 5 //Number of parent elements to seach for ignore ID's'
        },
        //Merge settings with params into options
        options = $.extend( settings, opt ),
        //Helper methods return container divs for tipper message
        helpers = {
            findOuter   : function() {
                return $('#'+options.outerId);
            },
            findInner   : function() {
                return $('#'+options.innerId);
            },
            createDivs  : function() {
                this.append("<div id='"+options.outerId+"'><div id='"+options.innerId+"'></div></div>");//Create divs used as tipper container
            },
            cleanImg    : function(imgObj) {
                var a = imgObj.attr('alt'),t = imgObj.attr('title');
                imgObj.attr({
                    'alt':'',
                    'title':''
                });
                return a||t;
            },
            avoidThis       : function() {
                var tell = false,
                self = this,
                self_id   = " "+self.attr("id")+" ",
                parentals = self.parents(),
                checkIgnore = function( ignored ){
                    for( var i = 0; i < options.ignoreCoverage; i++){
                        if($(parentals[i]).attr('id') === ignored || $(parentals[i]).attr('id') === self_id) //Check for elements to ignore
                            return true;
                    }

                }
                if( options.ignore ){
                    if( $.isArray(options.ignore) ){
                        for(var x = 0; x < options.ignore.length;x++){
                            if ( checkIgnore(options.ignore[x]) ) {
                                tell = true;
                                break;
                            }
                        }
                    }else{
                        if ( checkIgnore(options.ignore)  ) tell = true;
                    }
                }
                return tell;
            }
        },
        //Primary methods of plugin
        methods = $.extend({
            reveal : function() {
                var self   = $(this);
                options.hasImg  = self.find('img');
                options.storedAttr = self.attr(options.mainAttr);
                self.attr(options.mainAttr,"");//Remove title after storing it ( even if not used );
                if( helpers.avoidThis.call( self ) ) return;//return;//Check for elements to ignore and exit if found
                if( options.hasImg.attr('alt') || options.hasImg.attr('title') ) options.storedImg = helpers.cleanImg( options.hasImg );
                if( !helpers.findOuter().length )helpers.createDivs.call( $('body') );
                options.content = options.otherAttr ? self.attr( options.otherAttr ) : options.storedAttr ? options.storedAttr : options.storedImg ;//Get attribute to show in tipper
                helpers.findInner().html( options.content );//Add content to the inner div
                options.gone = false
                methods.finale = setTimeout(function () {
                    if( options.gone == false && options.content ){ //IF ( cursor is still over element )
                        helpers.findOuter().slideDown(options.speed, function() { // show outer div
                            if( options.gone == true )helpers.findOuter().hide(); //IF ( cursor left element during slideDown ) hide outer div
                        });
                    }
                }, options.delay )
            },
            conceal : function(){
                var self = $(this);
                clearTimeout( methods.finale );//Disable reveal function if cursor has left
                if(self.attr( options.mainAttr ) == '')self.attr( options.mainAttr, options.storedAttr );//Restore title/mainAttr
                helpers.findOuter().hide();
                if( options.hasImg )options.hasImg.attr({
                    "alt" : options.storedImg ,
                    "title" : options.storedImg
                });
                options.gone = true;//Log the cursor left element
            },
            reposition : function(ev){//Re-position the div for mouse move
                var ev_page_x   = ev.pageX,
                ev_page_y   = ev.pageY,
                ev_client_y = ev.clientY,
                viewport_x  = $(document).width() > $(window).width() ? $(window).width() : $(document).width(),
                window_y    = $(window).height(),
                outer       = helpers.findOuter(),
                outer_width = outer.outerWidth(),
                outer_height= outer.outerHeight();
                outer.css({//switch position of tipper if close to edge of view port
                    'top': ev_client_y + outer_height + 5 > window_y ? ev_page_y - outer_height : ev_page_y + options.topOffset,
                    'left': ev_page_x + outer_width + options.leftOffset > viewport_x ? ev_page_x - outer_width - 10 : ev_page_x + options.leftOffset
                });
            }
        }, func);

        return this.each(function() {
            //reveal() on mouse enter, reposition() on mouse move and conceal() on exit )
            $(this)
            .hover( methods.reveal, methods.conceal )
            .mousemove( methods.reposition )
            .click(methods.conceal)
        });
    };
//End of tipper plugin code
})(jQuery);
