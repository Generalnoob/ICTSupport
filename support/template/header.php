<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width,minimum-scale=1">
		<title><?php echo $Page_Name; ?></title>
		<link href="../style.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body class="loggedin">
		<nav class="navtop">
			<div>
				<h1><?php echo $lang_Support; ?></h1>
				<ul class="nav-top">
				<li><a href="../home.php"><i class="fas fa-home"></i><?php echo $lang_Home; ?></a></li>
				<li><a href="../profile.php"><i class="fas fa-user-circle"></i><?php echo $lang_Profile; ?></a></li>
				<li><?php if ($_SESSION['role'] == 'Admin'): ?>
				<a href="../admin/index.php" target="_blank"><i class="fas fa-user-cog"></i><?php echo $lang_Admin; ?></a>
				<?php endif; ?></li>
				<li><a href="index.php"><i class="fas fa-desktop"></i><?php echo $lang_Support; ?></a></li>
				<li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i><?php echo $lang_Logout; ?></a></li>
					</ul>
			</div>
		</nav>