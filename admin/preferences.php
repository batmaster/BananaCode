<?php
	require_once('../functions.php');
	if(!loggedin())
		header("Location: login.php");
	else if($_SESSION['username'] !== 'admin')
		header("Location: login.php");
	else
		include('header.php');
		connectdb();
?>

<li class="active"><a id="Clock"></a>
<li>
<li><a href="index.php">Admin Panel</a></li>
<li><a href="users.php">Users</a></li>
<li class="active"><a href="#about">Preferences</a></li>
<li><a href="logout.php">Logout</a></li>
</ul>
</div>
<!--/.nav-collapse -->
</div>
</div>
</div>
<!-- container -->
<div class="container">
	<div class="tabbable"> <!-- Only required for left/right tabs -->
		<ul class="nav nav-tabs">
			<li class="active"><a href="#tab1" data-toggle="tab">User Pages</a></li>
			<li><a href="#tab2" data-toggle="tab">Admin Pages</a></li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane active" id="tab1">
				<form method="post" action="preferences.php">
					<div class="well">
						<h1><small>Problems Page</small></h1>
						<div class="control-group">
						
								<input type="checkbox"> Newest on top
							<div class="controls">
								<div class="input-prepend">
									<input type="number" name="new-range"/>
								</div>
							</div>
							<br>
							
						</div>
					</div>
					<br/>
					<br/>
					<input class="btn" type="submit" name="submit" value="Change Password"/>
				</form>
			</div>
			<div class="tab-pane" id="tab2">
				<p>Howdy, I'm in Section 2.</p>
			</div>
		</div>
	</div>
</div>
<!-- /container -->

<?php
	include('footer.php');
?>