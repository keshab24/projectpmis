$(document).ready(function() {
    /*==========================================
     *
     *==========================================*/
    $('.click_menu').click(function(){
        var menu_id = $(this).attr('data-menu-id');
        var menu_level = $(this).attr('data-menu-level');
        var implementing_office_id = $('#implementing_office_id');
        var rate_id = $('#rate_id');
        var expenditure_topic_id = $('#expenditure_topic_id');
        var income_topic_id = $('#income_topic_id');
        var group_category_id = $('#group_category_id');
        var level = $('#level');
        var orderCounter = 0;
        var order = $('#menu_order');
        $('#voucher_no').val($(this).attr('data-voucher_no'));
        $('.click_menu').each(function(){
            $(this).removeClass('click_menu_active');
        });
        $(this).parent('li').find('ul>li>.click_menu').each(function(){
            if((Number($(this).attr('data-menu-level'))-1)== menu_level){
                orderCounter++;
            }
        });
        order.val(orderCounter+1);
        implementing_office_id.val(menu_id);
        rate_id.val(menu_id);
        expenditure_topic_id.val(menu_id);
        income_topic_id.val(menu_id);
        group_category_id.val(menu_id);
        level.val(Number(menu_level)+1);

        $(this).addClass('click_menu_active');
    });
});
