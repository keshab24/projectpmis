var $base_path = $('base').attr('href');
var $home_path = $base_path;
$(document).ready(function(e) {
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

    $('form.showSavingOnSubmit').submit(function(){
        var $btn = $(this).find('button:submit');
        $btn.attr('disabled','disabled');
        $btn.button('loading');
    });
    /*==========================================
     * Sticky Navigation
     *==========================================*/
    $(".stickyDiv").sticky({topSpacing:0});

    $(".pro_login_cancel").click(function(e){
        e.preventDefault();
        $("#pro_login_close").trigger('click');
        $("#first_name").focus().select();
    });

    document.addEventListener('scroll', show_page_up);
    $(".pro_page_up").click(function(){
        $('html, body').animate({'scrollTop':0});
    });

    $(".pro_enable_me").removeAttr('disabled');
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
        data = " Your data has been successfully stored!!";
    swal({
        title:'Successfully Stored!',
        text: data,
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
    if(!data)
        data = " these data";
    swal({   title: "Your are redirected Here!!",   text: "Please add "+data+" with active status or check whether there is active record then try your previous task :).",   imageUrl: $home_path+"/public/images/smile1.png" });
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
    $('#'+$target).html('<i class="fa fa-refresh fa-spin"></i>');
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
            var $url = $base_path+'/pro_admin/ajax/helper';
            simpleRequest($url,$data,options.target);
        });
        options.selector.on('tokenfield:removedtoken',function(e){
            var $req = 'required="required"';
            if(options.required==false) $req = '';
            $("#"+options.target).html('<input placeholder="'+options.alternative+'" class="form-control" disabled="disabled" '+$req+' />');
        });
    }
}

function getOrder($stock_master_id, $title, $url, $extra){
    $('body').append('<div id="pro_pop_shadow" onclick="removePop()"></div><div id="pro_pop_window"><div id="pro_pop_head"><h2>'+$title+' <i class="fa fa-close pro_window_close" onclick="removePop()"></i></h2><hr /></div><div id="pro_pop_body"><div class="spinner-loader pro_pop_loader">Loading... </div></div></div>');
    $("#pro_pop_shadow").fadeIn('fast');
    $('#pro_pop_window').fadeIn('fast');
    var $dat = 'stock_master_id='+$stock_master_id;
    $.ajax({
        url: $url,
        dataType: 'html',
        data: $dat,
        type: 'GET',
        cache: false,
        success: function(rval){
            $('#pro_pop_window').find('#pro_pop_body').html(rval);
        }
    });
}
function removePop(){
    $('#pro_pop_shadow, #pro_pop_window').remove();
}

function addToCart($data, $url, $cartUrl, $extra){
    if(!$extra){
        $extra={
          'alert':true
        };
    }else{

    }
    $.ajax({
        url: $url,
        dataType: 'json',
        data: $data,
        type: 'GET',
        cache: false,
        success: function(rval){
            removePop();
            var $target = $("#pro_cart_info");
            var $target1 = $(".pro_cart_info");
            var $category_target = $("#pro_category_cart_info");
            $target.find('ul').remove();
            $target.append(rval[1]);
            $category_target.html(rval[1]);
            if(rval[2] != 0){
                $target.find('span').html(rval[2]);
                $target1.find('span').html(rval[2]);
            }
            if($extra.alert == true){
                if(rval[0] == 1) {
                    swal({
                            title: "Added To Cart Successfully!!",
                            text: "Do you want to go to cart manager?",
                            type: "success",
                            showCancelButton: true,
                            confirmButtonColor: '#d9534f',
                            confirmButtonText: 'Go To Cart',
                            cancelButtonText: "Continue Shopping",
                            closeOnConfirm: false,
                            closeOnCancel: false
                        },
                        function (isConfirm) {
                            if (isConfirm) {
                                window.location = $cartUrl;
                            } else {
                                swal({
                                    title: 'Enjoy Shopping More!!',
                                    text: "Please Continue!!",
                                    timer: 1,
                                    imageUrl: $home_path+"/public/images/smile1.png"
                                });
                            }
                        }
                    );
                }else{
                    swal({
                        title: 'Oops!! Something Wrong!!',
                        text: "Please Try Again!!",
                        type: 'error',
                        timer: 4000
                    });
                }
            }
        }
    });
}

function show_page_up(){
    var currentY = window.pageYOffset;
    var page_up_button = $('.pro_page_up');
    if(currentY > 60){
        page_up_button.fadeIn('fast');
    }else{
        page_up_button.fadeOut('fast');
    }
}

function remove_cart($id, $selector, $data){
    $.ajax({
        url: $base_path+'/ajax/order/remove',
        dataType: 'json',
        data: 'session_id='+$id,
        type: 'GET',
        cache: false,
        success: function(rval){
            if(rval == 1){
                window.location.reload();
                /*if($selector == 1){
                    $data.parent('tr').remove();
                    calculate_total_price();
                    alert(1);
                }
                var $cartUrl = $base_path+'/ajax/order';
                addToCart('quantity=1&stock_master_id=0', $cartUrl,"", {'alert':false});*/
            }
        }
    });
}

function calculate_total_price(){
    var $totalAmount = 0;
    $('#pro_order_table tr.pro_order_data_row').each(function(){
        var $price = $(this).find('td.pro_order_price').html();
        var $qty = $(this).find('input[type=number]').val();
        var $total = (Number($price) * Number($qty)).toFixed(2);
        $(this).find('td.pro_order_total').html($total);
        $totalAmount += Number($total);
    });
    $("#pro_order_table .pro_grand_total").html($totalAmount.toFixed(2));
    $("#pro_order_table .pro_grand_total").val($totalAmount.toFixed(2));
}
function get_postal_code_response($postal_code, $amount,$url){
    $("#pro_postal_code_response").html('<div class="whirly-loader pro_pop_loader">Loading... </div>').addClass('well');
    $.ajax({
        url: $url,
        dataType: 'json',
        data: 'postal_code='+$postal_code+'&total_amount='+$amount,
        type: 'GET',
        cache: false,
        success: function(rval){
            if(rval[2] == 1){
                $("#pro_postal_code_response").html(rval[0]).removeAttr('class').addClass('pro_text_center alert alert-'+rval[1]);
                if($("#shipping_as_payment").is(':checked')){
                    if(rval[1]=='success'){
                        $("#order_form").find('button[type=submit]').removeAttr('disabled');
                    }else{
                        $("#order_form").find('button[type=submit]').attr('disabled','disabled');
                    }
                }else{
                    $("#order_form").find('button[type=submit]').removeAttr('disabled');
                }
            }else{
                $("#pro_postal_code_response").html('<i class="fa fa-frown-o"></i> Data Not Receiving!!');
            }
        }
    });
}

function rank_nepali($index){
    alert($index);
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