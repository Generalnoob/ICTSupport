<?php
include 'main.php';
check_loggedin($con);
languages($con);
include 'languages/'.languages($con).'.php';
include 'template/'.Site_Theme.'/header.php'; 
?>
		<div class="content">
			<h3>Home Page</h3>
			<div class="block">
				<div class="homecenter"><div class="homepage"><a href="home.php"><i class="fas fa-home"></i></br>Home</a></div>
						<div class="homepage"><a href="profile.php"><i class="fas fa-user-circle"></i></br>Profile</a></div>
						<?php if ($_SESSION['role'] == 'Admin'): ?><div class="homepage"><a href="admin/index.php" target="_blank"><i class="fas fa-user-cog"></i></br></i>Admin</a></div><?php endif; ?>
						<div class="homepage"><a href="support/index.php"><i class="fas fa-desktop"></i></br>Support</a></div>
						<div class="homepage"><a href="logout.php"><i class="fas fa-sign-out-alt"></i></br>Logout</a></div>
			</div></div>
		</div>
<div class="hiUser" id="hiUser" name="hiUser"><?php echo $lang_Welcome_Back; ?>, <?=$_SESSION['name']?>!</div>
<?php include 'template/'.Site_Theme.'/footer.php'; ?>
