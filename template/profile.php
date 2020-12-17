<?php

function show_profile_configuration() {

echo 
'
<div class="col-12">
<div class="card">
  <div class="card-header">
    <ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
      <li class="nav-item">
        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#general" role="tab" aria-controls="general" aria-selected="true">General</a>
      </li>
    </ul>
  </div>
  <div class="card-body">
    <div class="tab-content" id="myTabContent">
	<form method="POST">
      <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">

<div class="form-group">
	NSFW Configuration:
    <select class="form-control" name="nsfw">';

      if($_SESSION['nsfw']==0) { echo '<option selected value=0>Always display</option>'; } else { echo '<option value=0>Always display</option>'; }
      if($_SESSION['nsfw']==1) { echo '<option selected value=1>Always hide</option>'; } else { echo '<option value=1>Always hide</option>'; }
      if($_SESSION['nsfw']==2) { echo '<option selected value=2>Don\'t show</option>'; } else { echo '<option value=2>Don\'t show</option>'; }

echo '</select><br>';

	echo 'Tip button and reCaptcha settings (increases privacy, but you can\'t tip anymore):
        <select class="form-control" name="tip">';
	      if($_SESSION['tip']==0) { echo '<option selected value=0>Enable Tip and reCaptcha</option>'; } else { echo '<option value=0>Enable Tip and reCaptcha</option>'; }
	      if($_SESSION['tip']==1) { echo '<option selected value=1>Disable Tip and reCaptcha</option>'; } else { echo '<option value=1>Disable Tip and reCaptcha</option>'; }
echo	'</select>';
echo '</div>

    </div>
<button class="btn btn-primary" type="submit">Update</button>
</form>
    </div>
  </div>
</div>
</div>
';

}