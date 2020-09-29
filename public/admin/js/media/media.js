function hideDiv($this){
    var $selector = $this.parent('div.pro_images_section');
    var $val = $selector.find('input[data-pro-name=useMeDelete]').val();
    $selector.find('input[data-pro-name=deleteMe]').val($val);
    $selector.fadeOut('slow');
}

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader, img, $label;
        for (var i = 0; i < input.files.length; i++){

            reader = new FileReader();
            reader.onload = function (e) {
                img = $('<img >'); //Equivalent: $(document.createElement('img'))
                img.attr('src', e.target.result);
                $label = $('.pro_images_browser .pro_images_section:last-child label');
                $label.css('border','none');
                $label.html('');
                img.appendTo($label);
                addImages();
            };
            reader.readAsDataURL(input.files[i]);
        }

    }
}
function removeImages(){
    var $selector = $('.pro_images_section');
    $selector.each(function(){
        var $val = $(this).find('input[data-pro-name=useMeDelete]').val();
        $(this).find('input[data-pro-name=deleteMe]').val($val);
        $(this).fadeOut('slow');
    });

}
