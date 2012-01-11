<?php
include('auth/lock.php');
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8" />
		<meta name="author" content="F. Baumann, H. Winter" />
		<meta name="description" content="You can always count on coffee!" />
		<link rel="stylesheet" href="../css/onload.css" type="text/css" media="screen" />
	</head>
<body>
	<h1>Welcome <?php echo $login_session; ?></h1>
	<a href="auth/logout.php">Logout</a>

	<div id="wrapper">
		<div id="welcome">
			<div class="greetings">This is all about ~coffee.</div>
			<div class="greetings">Welcome!</div>
		</div>
	</div>
</body></html>