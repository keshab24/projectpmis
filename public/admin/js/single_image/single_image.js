$(document).ready(function() {
    /*==========================================
     *
     *==========================================*/
    $('.drop-zone').click(function(){
        $(this).find('input[type=file]').trigger('click');
    });
    $("#image_browse").change(function(){
        readURL(this,$('#image_preview'));
    });
    $("#image_browse1").change(function(){
        readURL(this,$('#image_preview1'));
    });
    $("#image_browse2").change(function(){
        readURL(this,$('#image_preview2'));
    });
});

function readURL(input, $target) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $target.attr('src', e.target.result);
        };

        reader.readAsDataURL(input.files[0]);
    }
}
