<?php

if(isset($_POST['logout'])) {
	include('config.php');
	session_destroy();
	echo '<meta http-equiv="refresh" content="0; url=index.php" />';
}

?>