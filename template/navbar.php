<nav class="navbar navbar-expand-lg navbar-light bg-light">
<a class="navbar-brand" href="index.php"><?php echo $website_title; ?></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav ml-auto">

<?php
	if(isset($_SESSION['login'])) {
		echo '<li class="nav-item"><a class="nav-link" href="add_article.php">Add article</a></li>';
		echo '<form method="POST" action="logout.php"><li class="nav-item"><input type="hidden" name="logout" value="1"><button class="btn btn-link nav-link" type="submit">Logout</button></li></form>';
	} else {
		echo '<li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>';
		echo '<li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>';
	}
?>

    </ul>
  </div>
</nav>



<div class="container">
  <div class="row">
