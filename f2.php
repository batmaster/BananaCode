<?php
	require_once('functions.php');
	if(loggedin())
		header("Location: index.php");
	else if(isset($_POST['action'])) {
		$username = mysql_real_escape_string($_POST['username']);
		if($_POST['action']=='login') {
			if(trim($username) == "" or trim($_POST['password']) == "")
				header("Location: login.php?derror=1"); // empty entry
			else {
				// code to login the user and start a session
				connectdb();
				$query = "SELECT salt,hash FROM users WHERE username='".$username."'";
				$result = mysql_query($query);
				$fields = mysql_fetch_array($result);
				$currhash = crypt($_POST['password'], $fields['salt']);
				if($currhash == $fields['hash']) {
					$_SESSION['username'] = $username;
					header("Location: index.php");
				} else
					header("Location: login.php?error=1");
			}
		} else if($_POST['action']=='register') {
			// register the user
			$email = mysql_real_escape_string($_POST['email']);
			if(trim($username) == "" or trim($_POST['password']) == "" or trim($email) == "")
				header("Location: login.php?derror=1"); // empty entry
			else {
				// create the entry in the users table
				connectdb();
				$query = "SELECT salt,hash FROM users WHERE username='".$username."'";
				$result = mysql_query($query);
				if(mysql_num_rows($result)!=0)
					header("Location: login.php?exists=1");
				else {
					$salt = randomAlphaNum(5);
					$hash = crypt($_POST['password'], $salt);
					$sql="INSERT INTO `users` ( `username` , `salt` , `hash` , `email` ) VALUES ('".$username."', '$salt', '$hash', '".$email."')";
					mysql_query($sql);
					header("Location: login.php?registered=1");
				}
			}
		}
	}
?><head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<title><?php echo(getName()); ?>Login</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">

<!-- Le styles -->
<link href="css/bootstrap.css" rel="stylesheet">
<style>
body {
	padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
}
.footer {
	text-align: center;
	font-size: 11px;
}
</style>
<link href="css/bootstrap-responsive.css" rel="stylesheet">

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



<link href="css/bootstrap-responsive.css" rel="stylesheet">


<div class="container">
<form method="post" action="login.php" class="bs-docs-example form-horizontal">
	<input type="hidden" name="action" value="login"/>
		<div class="control-group">
						<label class="control-label" for="inputIcon">Username</label>
						<div class="controls">
							<div class="input-prepend"> <span class="add-on"><i class="icon-envelope"></i></span>
								<input type="text" name="username">
								</input>
							</div>
						</div>
						<br/>
						<label class="control-label" for="inputIcon">Password</label>
						<div class="controls">
							<div class="input-prepend"> <span class="add-on"><i class="icon-envelope"></i></span>
								<input type="password" name="password"/>
							</div>
						</div>
					</div>
					<br/>
					<br/>
					<input class="btn btn-info btn-block" type="submit" name="submit" value="Login"/>
				</form>

</div>
