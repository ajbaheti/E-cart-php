<?php 
	include('functions.php');
	session_start();

?>

<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title>E-commerce website</title>
		<link href="style/style.css" type="text/css" rel="stylesheet" />
	</head>
	<body>
		<div id="heading">
			<h1>One Place Electronic Shop</h1>
			<h2>Welcome <?php echo "'{$_SESSION['login_user']}'"; ?></h2>
		</div>
		<div id="menubar">
			<?php echo menu_navigation(); ?>
		</div>