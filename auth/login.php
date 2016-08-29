<?php 
session_start();
//include_once("./php_lib/navbar.php");
include_once "constants.php";
?>

<!DOCTYPE html>
<html>
<head>
	<link href="/extlib/bootstrap/css/bootstrap.min.css" rel="stylesheet"/>
</head>
<body>
	<nav class="navbar navbar-default">
		<div class="navbar-header">
			<strong class="navbar-brand"><?php //echo get_navbar_title(); ?></strong>
		</div>
			<?php //get_navbar_content(); ?>
	</nav>
    <div class="container">
	<div class="panel panel-default">
		<div class="panel-heading"><h3>Login</h3></div>
		<div class="panel-body">
		    <?php if (isset($_SESSION[SESSION_ERROR])) { ?>
		    <div class="alert alert-danger">
		        <div><?= $_SESSION[SESSION_ERROR] ?></div>
		    </div>
		    <?php $_SESSION[SESSION_ERROR] = null; } ?>
			<form action="auth.php" method="POST">
				<div class="form-group">
					<label for="user_input">Username</label>
					<input id="user_input" name="user_input" type="text" class="form-control">
				</div>
				<div class="form-group">
					<label for="pass_input">Password</label>
					<input id="pass_input" name="pass_input" type="password" class="form-control">
				</div>
				<div class="form-group">
					<input type="submit" class="form-control">
				</div>
			</form>
		</div>
	</div>
    </div>
	<script src="/extlib/jquery-3.0.0.min.js"></script>
</body>
</html>