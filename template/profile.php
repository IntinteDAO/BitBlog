<?php

function show_profile_configuration() {

global $tip_enable;

echo 
'

<div class="col-12 card">
  <div class="card-header">
    <ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
      <li class="nav-item">
        <a class="nav-link active" id="general-tab" data-toggle="tab" href="#general" role="tab" aria-controls="general" aria-selected="true">General</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" id="ignore-tab" data-toggle="tab" href="#ignore" role="tab" aria-controls="ignore" aria-selected="false">Ignore list</a>
      </li>
<!--      <li class="nav-item">
        <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Contact</a>
      </li>-->
    </ul>
  </div>
  <div class="card-body">
    <div class="tab-content" id="myTabContent">

      <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
<form method="POST">
    NSFW Configuration:
   <select class="form-control" name="nsfw">:';
      if($_SESSION['nsfw']==0) { echo '<option selected value=0>Always display</option>'; } else { echo '<option value=0>Always display</option>'; }
      if($_SESSION['nsfw']==1) { echo '<option selected value=1>Always hide</option>'; } else { echo '<option value=1>Always hide</option>'; }
      if($_SESSION['nsfw']==2) { echo '<option selected value=2>Don\'t show</option>'; } else { echo '<option value=2>Don\'t show</option>'; }

echo '</select><br>';
    if($tip_enable == 1) {
    echo 'Tip button and reCaptcha settings (increases privacy, but you can\'t tip anymore):
        <select class="form-control" name="tip">';
          if($_SESSION['tip']==0) { echo '<option selected value=0>Enable Tip and reCaptcha</option>'; } else { echo '<option value=0>Enable Tip and reCaptcha</option>'; }
          if($_SESSION['tip']==1) { echo '<option selected value=1>Disable Tip and reCaptcha</option>'; } else { echo '<option value=1>Disable Tip and reCaptcha</option>'; }
echo	'</select>';
	}
	echo '<button class="btn btn-primary" type="submit">Update</button>';
echo '	</div>
      <div class="tab-pane fade" id="ignore" role="tabpanel" aria-labelledby="ignore-tab">
';

    $ignore_list = scandir('indexes/mute/'.$_SESSION['login']);
    echo '<table class="table table-striped table-bordered table-hover table-sm">';
    echo '<thead><td>Nick:</td><td>Delete:</td></thead>';
	for($i=2; $i<=count($ignore_list)-1; $i++) {
		echo '<tr id="'.$ignore_list[$i].'"><td>'.$ignore_list[$i].'</td><td><a href="#" onclick="$.post(\'mute.php\', { username: \''.$ignore_list[$i].'\' }); document.getElementById(\''.$ignore_list[$i].'\').outerHTML=\'\'; ">Remove from list</a></td></tr>';
	}
    echo '</table>';

echo '
    </div>
      <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">Treść karty #contact</div>

</form>
    </div>
  </div>
</div>

';

}