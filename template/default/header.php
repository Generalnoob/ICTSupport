<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width,minimum-scale=1">
		<title><?php echo $lang_Home_Page; ?></title>
		<link href="<?= URL_Site.'template/'.Site_Theme.'/';?>style.css" rel="stylesheet" type="text/css">
		<script src="https://kit.fontawesome.com/ae1a122eb7.js" crossorigin="anonymous"></script>
		<script>
		//Say Hi to the returning user
			function hideLoadingDiv() {
  			setTimeout(function(){
    		document.getElementById('hiUser').classList.add('hidden');
  				},10000)
}		//Responsive Menu
		function myMenu() { 
			var x = document.getElementById("myTopnav");
  			if (x.className === "topnav") {
    		x.className += " responsive";
  				} else {
    		x.className = "topnav";
  				}
			}
		
		</script>
	</head>
	<body class="loggedin" onload="hideLoadingDiv()">
		<nav class="navtop">
			<div class="topnav"  id="myTopnav">
				<?php Add_Logo(); ?>
				
				<a href="<?=$URL_HOME?>"><i class="fas fa-home"></i><?php echo $lang_Home; ?></a>
				<a href="<?=$URL_PROFILE?>"><i class="fas fa-user-circle"></i><?php echo $lang_Profile; ?></a>
				<?php if ($_SESSION['role'] == 'Admin'): ?>
				<a href="<?=$URL_ADMIN?>" target="_blank"><i class="fas fa-user-cog"></i><?php echo $lang_Admin; ?></a>
				<?php endif; ?>
				<a href="<?=$URL_SUPPORT?>"><i class="fas fa-desktop"></i><?php echo $lang_Support; ?></a>
				<a href="<?=$URL_LOGOUT?>"><i class="fas fa-sign-out-alt"></i><?php echo $lang_Logout; ?></a>
				<a href="javascript:void(0);" class="icon" onclick="myMenu()"><i class="fa fa-bars"></i></a>
			</div>
		</nav>