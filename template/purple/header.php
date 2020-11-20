<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width,minimum-scale=1">
		<title><?php echo $lang_Home_Page; ?></title>
		<link href="<?= URL_Site;?>style.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
		<script>function hideLoadingDiv() {
  setTimeout(function(){
    document.getElementById('hiUser').classList.add('hidden');
  },10000)
}</script>
	</head>
	<body class="loggedin" onload="hideLoadingDiv()">
		<nav class="navtop">
			<div>
				<?php Add_Logo(); ?>
				<ul class="nav-top">
				<li><a href="<?=$URL_HOME?>"><i class="fas fa-home"></i><?php echo $lang_Home; ?></a></li>
				<li><a href="<?=$URL_PROFILE?>"><i class="fas fa-user-circle"></i><?php echo $lang_Profile; ?></a></li>
				<li><?php if ($_SESSION['role'] == 'Admin'): ?>
				<a href="<?=$URL_ADMIN?>" target="_blank"><i class="fas fa-user-cog"></i><?php echo $lang_Admin; ?></a>
				<?php endif; ?></li>
				<li><a href="<?=$URL_SUPPORT?>"><i class="fas fa-desktop"></i><?php echo $lang_Support; ?></a></li>
				<li><a href="<?=$URL_LOGOUT?>"><i class="fas fa-sign-out-alt"></i><?php echo $lang_Logout; ?></a></li>
					</ul>
			</div>
		</nav>