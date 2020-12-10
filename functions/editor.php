<?php

function init_editor() {

return '

    <link rel="stylesheet" href="libs/css/tui/tuidoc-example-style.css" />
    <link rel="stylesheet" href="libs/css/codemirror/codemirror.min.css"/>
    <link rel="stylesheet" href="libs/css/tui/toastui-editor.min.css" />

      <div id="editor"></div>

    <script src="libs/js/tui/md-default.js"></script>

    <script src="libs/js/tui/toastui-editor-all.min.js"></script>
    <script>
	window.onload = function() {

      editor = new toastui.Editor({
        el: document.querySelector(\'#editor\'),
        initialValue: content,
	previewStyle: \'vertical\',
	height: \'auto\',
        initialEditType: \'wysiwyg\',
	hooks: {
	addImageBlobHook: function(file, callback) {
		out = new FormData();
		out.append(\'file\', file, file.name);

		$.ajax({
			method: \'POST\',
			url: \'upload.php\',
			contentType: false,
			cache: false,
			processData: false,
			data: out,

			success: function (img) {
				callback(img);
			},

			error: function (jqXHR, textStatus, errorThrown) {
				alert(textStatus + " " + errorThrown);
			}
		});

	return false;
	}}
      }); }
    </script>
  </body>
</html>




';

}