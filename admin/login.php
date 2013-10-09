<?php
	require_once('../functions.php');
	if(loggedin() and $_SESSION['username'] == 'admin')
		header("Location: index.php");
	else if(isset($_POST['password'])) {
		if(trim($_POST['password']) == "")
			header("Location: login.php?derror=1"); // empty entry
		else {
			// code to login the user and start a session
			connectdb();
			$query = "SELECT salt,hash FROM users WHERE username='admin'";
			$result = mysql_query($query);
			$fields = mysql_fetch_array($result);
			$currhash = crypt($_POST['password'], $fields['salt']);
			if($currhash == $fields['hash']) {
				$_SESSION['username'] = "admin";
				header("Location: index.php");
			} else
				header("Location: login.php?error=1");
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<title>Codejudge Admin Panel Login</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">

<!-- Le styles -->
<link href="../css/bootstrap.css" rel="stylesheet">
<style>
body {
	padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
}
.footer {
	text-align: center;
	font-size: 11px;
}
</style>
<link href="../css/bootstrap-responsive.css" rel="stylesheet">

<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

<!-- Le fav and touch icons -->
<link rel="shortcut icon" href="http://twitter.github.com/bootstrap/assets/ico/favicon.ico">
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="http://twitter.github.com/bootstrap/assets/ico/apple-touch-icon-144-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="http://twitter.github.com/bootstrap/assets/ico/apple-touch-icon-114-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="http://twitter.github.com/bootstrap/assets/ico/apple-touch-icon-72-precomposed.png">
<link rel="apple-touch-icon-precomposed" href="http://twitter.github.com/bootstrap/assets/ico/apple-touch-icon-57-precomposed.png">
</head>

<body>
<div class="navbar navbar-fixed-top">
	<div class="navbar-inner">
		<div class="container"> <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </a> <a class="brand" href="#">Codejudge</a> </div>
	</div>
</div>


<div class="container">
	<?php
        if(isset($_GET['logout']))
          echo("<div class=\"alert alert-info\"><a class=\"close\" data-dismiss=\"alert\" href=\"#\">×</a>\nYou have logged out successfully!\n</div>");
        else if(isset($_GET['error']))
          echo("<div class=\"alert alert-error\"><a class=\"close\" data-dismiss=\"alert\" href=\"#\">×</a>\nIncorrect Password!\n</div>");
        else if(isset($_GET['derror']))
          echo("<div class=\"alert alert-error\"><a class=\"close\" data-dismiss=\"alert\" href=\"#\">×</a>\nPlease enter all the details asked before you can continue!\n</div>");
      ?>
	<div class="well well-large" align="center">
		<h1><small>Login</small></h1>
		<p>Please login to use the admin panel.</p>
		<br/>
		<form method="post" action="login.php">
			<div class="control-group">
				<label class="control-label" for="inputIcon">Password</label>
				<div class="controls">
					<div class="input-prepend">
						<span class="add-on">
							<i class="icon-envelope"></i>
						</span>
						<input type="password" name="password"/>
					</div>
				</div>
			</div>
			<br/>
			<br/>
			<input class="btn" type="submit" name="submit" value="Login"/>
		</form>
	</div>
</div>
<!-- /container -->

<?php
	include('footer.php');
?>
