<?php

function viewer() {

return '
    <link rel="stylesheet" href="libs/css/tui/tuidoc-example-style.css" />
    <link rel="stylesheet" href="libs/css/tui/toastui-editor-viewer.min.css" />

    <script src="libs/js/tui/toastui-editor-viewer.min.js"></script>
    <script>
      const viewer = new toastui.Editor({
        el: document.querySelector(\'#viewer\'),
        initialValue: content.join(\'\n\')
      });
    </script>
';
}