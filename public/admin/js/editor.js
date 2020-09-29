$(document).ready(function(){
    $(document).on('click', '.assets td', function () {
        var currentRow=$(this).closest("tr");
        $variationId = $(this.parentNode).attr('data-id');
        $vopeDate=currentRow.find("td:eq(0)").text();
        $chosen=currentRow.find("td:eq(1)").attr('data-id');
        $verifiedfrom=currentRow.find("td:eq(2)").attr('data-id');
        $afterVerifiedAmount=currentRow.find("td:eq(3)").text();
        $remarks=currentRow.find("td:eq(4)").text();
        $('#variationModal').modal('show');

        $("#variation_update_id").val($variationId);
        $("#vope_date_update").val($vopeDate);
        $("#amountUpdate").val($afterVerifiedAmount);
        $("#remarksUpdate").val($remarks);

        $("#statusUpdate").val($chosen).change();
        $("#verified_fromUpdate").val($verifiedfrom).change();
    });
    $(document).on('click', '.timeextension td', function () {
        var currentRow=$(this).closest("tr");
        $timeextension_id = $(this.parentNode).attr('data-id');
        $("#extensionId_update").val($timeextension_id);
        $("#extensionId_delete").val($timeextension_id);

        $extendedAt=currentRow.find("td:eq(2)").text();
        $extendedFrom=currentRow.find("td:eq(0)").text();
        $liqudatedDamage=currentRow.find("td:eq(0)").attr('data-id');

        $extendedTo=currentRow.find("td:eq(1)").text();
        $verifiedfrom=currentRow.find("td:eq(3)").attr('data-id');
        $fine=currentRow.find("td:eq(4)").text();
        $remarks=currentRow.find("td:eq(5)").text();
        $('#timeExtensionModal').modal('show');

        $("#start_date_update").val($extendedFrom);
        $("#end_date_update").val($extendedTo);
        $("#fine_update").val($fine);

        $("#remarks_update").val($remarks);
        $("#extended_on_update").val($extendedAt);
        $("#verified_from_update_extend").val($verifiedfrom).change();

        $('.liquidated_damage_update_no').attr('checked', true);
        if($liqudatedDamage==1){
            $('.liquidated_damage_update')[1].checked = true;
        }else{
            $('.liquidated_damage_update')[0].checked = true;
        }
    });
    $( document ).scroll(function() {
        var scrollTop = $(window).scrollTop();
        var top = scrollTop + 30;
        $('.modal-dialog').css('top',top);
    });
    $('#status').on('change', function() {
        if( this.value !=0){
            $('.bonusMessage').text('१३% VAT  सहित');
        }else{
            $('.bonusMessage').text('१३% VAT & Contengency बाहेक');

        }
    })
    $('#statusUpdate').on('change', function() {
        if( this.value !=0){
            $('.bonusMessage').text('१३% VAT  सहित');
        }else{
            $('.bonusMessage').text('१३% VAT & Contengency बाहेक');

        }
    })
    $(document).on('click', '.liquidation_damages td', function () {
        var currentRow=$(this).closest("tr");
        $liquidation_damagesid = $(this.parentNode).attr('data-id');
        $("#liquidation_damage_id").val($liquidation_damagesid);

        $amount=currentRow.find("td:eq(0)").text();
        $collected_date=currentRow.find("td:eq(1)").text();
        $remarks=currentRow.find("td:eq(2)").text();

        $('#liquidationDamageModal').modal('show');

        $("#liquidation_update_amount").val($amount);
        $("#liquidation_collected_date").val($collected_date);
        $("#liquidation_remakrs").val($remarks);

    });
    $(document).on('click', '.papers td', function () {
        var currentRow=$(this).closest("tr");
        $activity_id = $(this.parentNode).attr('data-id');
        $type = $(this.parentNode).attr('data-type');
        $("#paper_id").val($activity_id);
        $paperTitle=currentRow.find("td:eq(1)").first().first().find('.papertitle').text();



        $paperDetail=currentRow.find("td:eq(1)").find('p').text();
        $paperDate=currentRow.find("td:eq(0)").first().text();

        $('#papersEdit').modal('show');

        $("#paper_title").val($.trim($paperTitle));
        $("#dateUpdate_").val($.trim($paperDate));
        $("#paperremarks_update").val($.trim($paperDetail));
        $(".paperSelectorEdit").val($type).change();
    });
});
