function hideFilesDiv($this) {
    let $selector = $this.parent('.pro_files_section');
    $selector.fadeOut('slow');
}


function readFileURL(input) {
    if (input.files && input.files[0]) {
        var reader, element, label, file_type;
        var index = $(input).attr('index');
        console.log(index);
        var $rejectedFiles = '';
        for (var i = 0; i < input.files.length; i++) {
            file_type = input.files[i].type
            if(!file_type){
                file_type = getFileExtension(input.files[i].name)
            }
            if (checkMime(file_type) < 0) {
                $rejectedFiles += input.files[i].name + '\n'
            }

            label = $(input).siblings('label');
            label.css('border', 'none');
            label.html('');
            if (input.files[i].type == 'application/pdf') {
                reader = new FileReader();
                reader.onload = function (e) {
                    element = $('<iframe >'); //Equivalent: $(document.createElement('iframe'))
                    element.attr('src', e.target.result);
                    element.addClass('iframePdf');
                    element.appendTo(label);
                    addFiles(input, index);
                };
                reader.readAsDataURL(input.files[i]);
            } else if (input.files[i].type == 'image/jpeg' || input.files[i].type == 'image/png') {
                reader = new FileReader();
                reader.onload = function (e) {
                    element = $('<img >'); //Equivalent: $(document.createElement('img'))
                    element.attr('src', e.target.result);
                    element.appendTo(label);
                    addFiles(input, index);
                };
                reader.readAsDataURL(input.files[i]);
            } else {
                $icon = getIcon(input.files[i].name);
                element = $('<i>'); //Equivalent: $(document.createElement('i'))
                element.html("<p class='i_file_name'>" + input.files[i].name + "</p>");
                element.attr('class', 'file_icon fa-5x ' + $icon);
                element.appendTo(label);
                addFiles(input, index);
            }
            if (checkMime(input.files[i].type) < 0) {
                hideFilesDiv(label.prev())
            }
        }
        if ($rejectedFiles != '')
            swal("Files Rejected", "The Following files were rejected\n" + $rejectedFiles, "error");
    }

}

function removeFiles() {
    var $selector = $('.pro_files_section');
    $selector.each(function () {
        var $val = $(this).find('input[data-pro-name=useMeDeleteFile]').val();
        // console.log($val)
        $(this).find('input[data-pro-name=deleteMeFile]').val($val);
        $(this).find('input[name="hideFile[]"]').val(1);
        $(this).fadeOut('slow');
    });

}

function getIcon($file) {
    $type = $file.split('.')[1];
    switch ($type) {
        case "txt":
            $return = 'fa fa-sticky-note';
            break;
        case "docx":
        case "docm":
        case "dotx":
        case "dotm":
        case "docb":
        case "rtf":
        case "doc":
            $return = 'fa fa-file-word-o';
            break;
        case "xlsx":
        case "xlsm":
        case "xltx":
        case "xltm":
        case "xls":
        case "xlt":
            // console.log('hello')
            $return = 'fa fa-file-excel-o';
            break;
        case "pdf":
            $return = 'fa fa-file-pdf-o';
            break;
        case "ppsx":
        case "ppt":
        case "pptm":
        case "pptx":
            $return = 'fa fa-file-powerpoint-o';
            break;
        default:
            $return = 'fa fa-files-o';
            break;
    }
    return $return;
}

function checkMime($fileMime) {
    $accepted = [
        'application/msword',
        'application/x-zip-compressed',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.template',
        'application/vnd.ms-word.document.macroEnabled.12',
        'application/vnd.ms-word.template.macroEnabled.12',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.template',
        'application/vnd.ms-excel.sheet.macroEnabled.12',
        'application/vnd.ms-excel.sheet.macroEnabled.12',
        'application/vnd.ms-powerpoint',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'application/vnd.openxmlformats-officedocument.presentationml.template',
        'application/vnd.openxmlformats-officedocument.presentationml.slideshow',
        'application/pdf',
        'text/plain',
        'image/jpeg',
        'image/gif',
        'image/png',
    ];
    return ($.inArray($fileMime, $accepted));
}

function isUnicode(str) {
    for (var i = 0, n = str.length; i < n; i++) {
        if (str.charCodeAt(i) > 255) {
            return true;
        }
    }
    return false;
}

// $(document).ready(function () {
//     $(".pro_files_browser .row").sortable({
//         tolerance: 'pointer',
//         revert: 'invalid',
//         placeholder: '',
//         forceHelperSize: true
//     });
// })
function getFileExtension(filename) {
    return filename.slice((filename.lastIndexOf(".") - 1 >>> 0) + 2);
}
