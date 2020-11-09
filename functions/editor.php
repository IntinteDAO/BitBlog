<?php

function init_editor() {

return '

<div id="summernote"></div>

<script>
window.onload = function() {

$(\'#summernote\').summernote({
        callbacks: {
            onImageUpload: function(files) {
                for(let i=0; i < files.length; i++) {
                    $.upload(files[i]);
                }
            }
        },
        height: 500,
    });

    $.upload = function (file) {
        let out = new FormData();
        out.append(\'file\', file, file.name);

        $.ajax({
            method: \'POST\',
            url: \'upload.php\',
            contentType: false,
            cache: false,
            processData: false,
            data: out,
            success: function (img) {
                $(\'#summernote\').summernote(\'insertImage\', img);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.error(textStatus + " " + errorThrown);
            }
        });
    };
}
</script>

';

}