/*==========================================
 * 
 /*========================================
 * Use this script to fire all actions that
 * are common to each page.
 */
$(document).ready(function(e) {
    $('.focus_field').focus().select();
    /*==========================================
     * APPEAR ANIMATION
     *==========================================*/

     $('[data-appear-animation]').appear(function(){
         var delay = $(this).attr('data-appear-delay');
         var $el = $(this);
         if(delay){
             setTimeout(function(){
                 $el.addClass("animated " + $el.attr('data-appear-animation'));
             }, delay);
         } else {
           $el.addClass("animated " + $el.attr('data-appear-animation'));
         }
     });



    /*==========================================
     * SHOW TOOLTIP
     *==========================================*/
    $(".showToolTip").tooltip();

    /*==========================================
     * SHOW POPOVER
     *==========================================*/
    $('[data-toggle="popover"]').popover();

    $(".firstInput").focus();

    /*==========================================
     * DELETE CONFIRMATION BOX FOR DATA
     *==========================================*/
    $('.confirmButton').click(function(e){
        var $form_id = $(this).attr('data-form-id');
        e.preventDefault();
        swal({
                title: "Are you sure?",
                text: "You will not be able to recover this data!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: '#d9534f',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: "No, cancel plz!",
                closeOnConfirm: false,
                closeOnCancel: false
            },
            function(isConfirm){
                if (isConfirm){
                    $("#"+$form_id).trigger('submit');
                    //swal("Deleted!", "Your data has been deleted!", "success");
                } else {
                    swal("Cancelled", "Your data is safe :)", "error");
                }
            }
        );
    });

    /*==========================================
     * DELETE CONFIRMATION BOX FOR fILE
     *==========================================*/
    $('.pro_delete_file_button').click(function(e){
        var $form_id = $(this).attr('data-delete-form-id');
        e.preventDefault();
        swal({
                title: "Are you sure?",
                text: "You will not be able to recover this file!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: '#d9534f',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: "No, cancel plz!",
                closeOnConfirm: false,
                closeOnCancel: false
            },
            function(isConfirm){
                if (isConfirm){
                    $(".pro_do_task #"+$form_id).trigger('submit');
                    //swal("Deleted!", "Your data has been deleted!", "success");
                } else {
                    swal("Cancelled", "Your data is safe :)", "error");
                }
            }
        );
    });

    /*==========================================
     * TOGGLE ADVANCE AND LESS CONTENT
     *==========================================*/
    $('.pro_advance_button_footer').click(function(){
        var $this = $(this);
        $this.closest('form').find('.pro_advance_info').toggle('slow');
        var $toggle_id = $this.attr('data-toggle-id');
        if($toggle_id == 1){
            $this.attr('data-toggle-id','2');
            $this.html('Less <span class="glyphicon glyphicon-upload faa-tada animated-hover"></span>');
        }else{
            $this.attr('data-toggle-id','1');
            $this.html('Advance <span class="glyphicon glyphicon-download faa-tada animated-hover"></span>');
        }
    });

    $('form.showSavingOnSubmit').submit(function(){
        var $btn = $(this).find('button:submit');
        $btn.attr('disabled','disabled');
        $btn.button('loading');
    });

    //
    $('.focusInput').click(function(){
       $(this).nextAll('input').focus();
    });

    /*==========================================
     * MENU SCRIPT
     *==========================================*/
    $('.proHeaderMenuAdmin').dropit();
    var $lateral_menu_trigger = $('#cd-menu-trigger'),
        $content_wrapper = $('.cd-main-content'),
        $navigation = $('header');

    //open-close lateral menu clicking on the menu icon
    $lateral_menu_trigger.on('click', function(event){
        event.preventDefault();

        $lateral_menu_trigger.toggleClass('is-clicked');
        $navigation.toggleClass('lateral-menu-is-open');
        $content_wrapper.toggleClass('lateral-menu-is-open').one('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend', function(){
            // firefox transitions break when parent overflow is changed, so we need to wait for the end of the trasition to give the body an overflow hidden
            $('body').toggleClass('overflow-hidden');
        });
        $('#cd-lateral-nav').toggleClass('lateral-menu-is-open');

        //check if transitions are not supported - i.e. in IE9
        if($('html').hasClass('no-csstransitions')) {
            $('body').toggleClass('overflow-hidden');
        }
    });

    //close lateral menu clicking outside the menu itself
    $content_wrapper.on('click', function(event){
        if( !$(event.target).is('#cd-menu-trigger, #cd-menu-trigger span') ) {
            $lateral_menu_trigger.removeClass('is-clicked');
            $navigation.removeClass('lateral-menu-is-open');
            $content_wrapper.removeClass('lateral-menu-is-open').one('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend', function(){
                $('body').removeClass('overflow-hidden');
            });
            $('#cd-lateral-nav').removeClass('lateral-menu-is-open');
            //check if transitions are not supported
            if($('html').hasClass('no-csstransitions')) {
                $('body').removeClass('overflow-hidden');
            }

        }
    });

    //open (or close) submenu items in the lateral menu. Close all the other open submenu items.
    $('.item-has-children').children('a').on('click', function(event){
        event.preventDefault();
        $(this).toggleClass('submenu-open').next('.sub-menu').slideToggle(200).end().parent('.item-has-children').siblings('.item-has-children').children('a').removeClass('submenu-open').next('.sub-menu').slideUp(200);
    });


    $(".pro_submit_form").change(function(){
        $("#pro_helper_form").trigger('submit');
    });
});


/*==========================================
 * DELETE SUCCESSFUL INFO
 *==========================================*/
function delete_success_info(data){
    if(!data)
        data = " the data";
    swal({
        title:'Successfully Deleted!',
        text: "You just deleted the "+data+"!",
        type: 'success',
        timer: 4000
    });
}

/*==========================================
 * RECORD SUCCESSFUL INFO
 *==========================================*/
function store_success_info(data){
    if(!data)
        data = " the data";
    swal({
        title:'Successfully Stored!',
        text: "You just stored the "+data+"!",
        type: 'success',
        timer: 8000
    });
}

/*==========================================
 * UPDATE SUCCESSFUL INFO
 *==========================================*/
function update_success_info(data){
    if(!data)
        data = " the data";
    swal({
        title:'Successfully Updated!',
        text: "You just updated the "+data+"!",
        type: 'success',
        timer: 4000
    });
}

/*==========================================
 * FAIL INFO
 *==========================================*/
function fail_info(data){
    if(!data)
        data = " the data";
    swal({
        title:'Failed!',
        text: data,
        type: 'error',
        timer: 4000
    });
}


/*==========================================
 * UPDATE UNSUCCESSFUL INFO
 *==========================================*/
function update_unsuccess_info(data){
    if(!data)
        data = " The Update couldn't be successful!!";
    swal("Update Process Failed !!", data, 'error');
}

/*==========================================
 * Something Went Wrong
 *==========================================*/
function something_went_wrong(data){
    if(!data)
        data = " The request couldn't be successful!!";
    swal("Something Went Wrong !!", data, 'error');
}

/*==========================================
 * DELETE FILE SUCCESSFUL INFO
 *==========================================*/
function delete_file_success_info(data){
    if(!data)
        data = " the file";
    swal("Successfully Deleted!", "You just deleted the "+data+"!", "success");
}

/*==========================================
 * REDIRECTED TO
 *==========================================*/
function redirect_to(data){
    var $mainUrl = $('#homePath').val();
    if(!data)
        data = " these data";
    swal({   title: "Your are redirected Here!!",   text: "Please add "+data+" with active status or check whether there is active record then try your previous task :).",   imageUrl: $mainUrl+"/public/images/smile1.png" });
}


/*==========================================
 * PAGE LINKER
 *==========================================*/
function page_linker(link1){
    swal({
            title: "Where do you want to go?",
            text: "Please choose the destination you want to go :) !",
            type: "success",
            showCancelButton: true,
            confirmButtonColor: '#d9534f',
            confirmButtonText: 'Lets create bill!',
            cancelButtonText: "Stay Here!",
            closeOnConfirm: false,
            closeOnCancel: false
        },
        function(isConfirm){
            if (isConfirm){
                window.location = link1;
            } else {
                swal("Data Stored Successfully!!", "Please go ahead", "success");
            }
        }
    );
}

/*==========================================
 * RESTORE INFO
 *==========================================*/
function restore_info(data){
    if(!data)
        data = " the data";
    swal({
        title:'Successfully Restored!',
        text: "You just restored the "+data+"!",
        type: 'success',
        timer: 4000
    });
}

/*==========================================
 * SEARCH AND SHOW RESULT
 *==========================================*/
function searchData($url, $val, $selector){
    $.ajax({
        url: $url,
        dataType: 'html',
        data: 'input='+$val+'&selector='+$selector,
        type: 'GET',
        cache: false,
        success: function(rval){
            $('#search_result').html(rval);
        }
    });
}

/*==========================================
 * SIMPLE AJAX REQUEST
 *==========================================*/
function simpleRequest($url, $dat, $target){
    $.ajaxSetup({
        headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
    });
    $('#'+$target).html('<img src="' + $home_path + '/public/admin/images/loadingbar.gif" style="max-width: 100%;max-height:34px" /> <i class="fa fa-refresh fa-spin"></i>');
    $.ajax({
        url: $url,
        dataType: 'html',
        data: $dat,
        type: 'GET',
        cache: false,
        success: function(rval){
            $('#'+$target).html(rval);
        }
    });
}
/*==========================================
 * PREVENT DUPLICATE TOKEN
 *==========================================*/
function preventDuplicate($item){
    $item.on('tokenfield:createtoken', function (event) {
        var existingTokens = $(this).tokenfield('getTokens');
        $.each(existingTokens, function(index, token) {
            if (token.value === event.attrs.value)
                event.preventDefault();
        });
    });
}

/*==========================================
 * TOKEN FIELD DISPLAY
 *==========================================*/
function getMyToken(options){
    if(options.limit > 0){options.limit = options.limit}else{options.limit = 0}
    options.selector.tokenfield({
        autocomplete: {
            source: options.source,
            delay: 100
        },
        limit:options.limit,
        createTokensOnBlur:true,
        showAutocompleteOnFocus: true
    });
    preventDuplicate(options.selector);
    if(options.data_selector > 0){
        if(options.select_only == true){
            options.selector.on('tokenfield:createtoken',function(e){
                if(options.source.indexOf(e.attrs.value)<0)
                e.preventDefault();
            });
        }
        options.selector.on('tokenfield:createdtoken',function(e){
            var $input_value = e.attrs.value;
            var $data = 'return_blank=true&selector='+options.data_selector+'&q='+$input_value;
            if(options.required == false) {$data += '&required=false';}else{$data+='&required=true';}
            var $url = $base_path+'/ajax/helper';
            simpleRequest($url,$data,options.target);
        });
        options.selector.on('tokenfield:removedtoken',function(e){
            var $req = 'required="required"';
            if(options.required==false) $req = '';
            $("#"+options.target).html('<input placeholder="'+options.alternative+'" class="form-control" disabled="disabled" '+$req+' />');
        });
    }
}

function toggle_page_content($val){
    if($val == ''){
        $(".ckeditor").parent('div').fadeIn('slow');
        $("#pro_collapse_images").addClass('in');
    }else{
        $(".ckeditor").parent('div').fadeOut('slow');
        $("#pro_collapse_images").removeClass('in');
    }
}

function rank_nepali($index){
    var $return;
    if($index=="1")
    {$return="प्रथम";}
    else if($index=="2")
    {$return="दोश्रोय";}
    else if($index=="3")
    {$return="तेश्रो";}
    else if($index=="4")
    {$return="चौथो";}
    else if($index=="5")
    {$return="पाँचौ";}
    else if($index=="6")
    {$return="छैठौ";}
    else if($index=="7")
    {$return="सातौं";}
    else if($index=="8")
    {$return="आठौँ";}
    else if($index=="9")
    {$return="नवौं";}
    return $return;
}

$('.magic-update').on('click', function () {
    var $this = $(this);
    var $input = $('<input>', {
        value: $this.text(),
        type: 'text',
        style: 'width: 100%',
        class: 'form-control',
        blur: function () {
                proUpdateValue($this);
        },
        keyup: function (e) {
            if (e.which === 13) {
                $input.blur();
                proUpdateValue($this);
            }
        }
    }).appendTo($this.empty()).focus();
});


function proUpdateValue($this) {
    var $data;
    $data = 'model=' + $this.attr('data-model');
    $data += '&id=' + $this.attr('data-id');
    $data += '&field=' + $this.attr('data-field');
    if (typeof $this.attr('data-value') !== 'undefined') {
        $data += '&value=' + $this.attr('data-value');
    } else {
        $data += '&value=' + $this.find('input').val();
        $this.html('')
    }
    $.ajax({
        url: $base_path + '/proadmin/ajax/update',
        dataType: 'html',
        data: $data,
        type: 'GET',
        cache: false,
        success: function (response) {
            response = ($.parseJSON(response));
            if ($('document').find('.pro_alert_footer')) {
                $('.pro_alert_footer').slideUp();
            }
            $('body').prepend('<div class="pro_text_right pro_alert_footer" style="position:fixed; z-index:1000;bottom:20px; padding:30px 50px; right:20px;background: rgba(0,0,0,0.95);color:white;"><i class="fa fa-check-circle"></i> ' +
                response.message +
                '</div>');
            setTimeout(function () {
                $('.pro_alert_footer').fadeOut();

            }, 5000);
            if (typeof $this.attr('data-value') !== 'undefined') {
                $this.attr('data-value', $this.attr('data-value') == 1 ? 0 : 1);
            } else {
                $this.html(response.newValue);
            }

        }, error: function (error) {
            alert(error)
        }
    });
    return true;
}
