$(document).ready(function(){
    var select_all = $('#select_all');
    select_all.change(function(){
        if(select_all.is(":checked")){
            $('.select_me').each(function(){
                this.checked=true;
            });
            showMassAction();
        }else{
            $('.select_me').each(function(){
                this.checked=false;
            });
            hideMassAction();
        }
    });

    $('.select_me').change(function(){
        var count = 0;
        $('.select_me').each(function(){
            if($(this).is(':checked')){
                count++;
            }
        });
        if(count > 0){
            showMassAction();
        }else{
            hideMassAction();
        }
    });

    $('.massActionWrapper a').click(function(e){
        var menu_id_array = [];
        var actionVal = $('select#massAction').val();
        var model = $(this).attr('data-model-name');
        e.preventDefault();
        $('.select_me').each(function(){
            if($(this).is(':checked')){
                menu_id_array.push($(this).attr('data-menu-id'));
            }
        });
        if( actionVal == 2 || actionVal == 3 ){
            var hard_delete = (actionVal == 3)?'yes':'no';
            var move_delete = (actionVal == 3)?'delete it':'move to trash';

            var my_form = document.createElement('form');
            my_form.setAttribute('method','post');
            my_form.setAttribute('action','delete_mass');

            for(var $i=0;$i<menu_id_array.length;$i++){
                var my_input = document.createElement('input');
                my_input.setAttribute('name','mass_name[]');
                my_input.setAttribute('type','hidden');
                my_input.setAttribute('value',menu_id_array[$i]);
                my_form.appendChild(my_input);
            }
            var my_token = document.createElement('input');
            my_token.setAttribute('name','_token');
            my_token.setAttribute('type','hidden');
            my_token.setAttribute('value',$('meta[name=_token]').attr('content'));
            my_form.appendChild(my_token);

            var my_model = document.createElement('input');
            my_model.setAttribute('name','model');
            my_model.setAttribute('type','hidden');
            my_model.setAttribute('value',model);
            my_form.appendChild(my_model);

            var my_hard_delete = document.createElement('input');
            my_hard_delete.setAttribute('name','hard_delete');
            my_hard_delete.setAttribute('type','hidden');
            my_hard_delete.setAttribute('value',hard_delete);
            my_form.appendChild(my_hard_delete);

            document.getElementsByTagName('body')[0].appendChild(my_form);
            swal({
                    title: "Are you sure?",
                    text: "You will not be able to recover this data!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: '#d9534f',
                    confirmButtonText: 'Yes, '+move_delete+'!',
                    cancelButtonText: "No, cancel plz!",
                    closeOnConfirm: false,
                    closeOnCancel: false
                },
                function(isConfirm){
                    if (isConfirm){
                        my_form.submit();
                    } else {
                        swal("Cancelled", "Your data is safe :)", "error");
                    }
                }
            );

        }

    });
});
function showMassAction(){
    $('.massAction,.massActionWrapper').fadeIn();
}
function hideMassAction(){
    $('.massAction,.massActionWrapper').fadeOut();
}
