<?php
include '../main.php';
check_loggedin($con);
// Default input product values
$support = array(
	'device_id' => '',
	'problem' => '',
	'solution' => '',
);
$date = new DateTime();

if (isset($_GET['id'])) {
    // Get the account from the database
    $stmt = $con->prepare('SELECT id, device_id, user_id, status, problem, solution, date FROM support WHERE id = ?');
    $stmt->bind_param('i', $_GET['id']);
    $stmt->execute();
    $stmt->bind_result($support['id'], $support['device_id'], $support['user_id'], $support['status'], $support['problem'], $support['solution'], $support['date']);
    $stmt->fetch();
    $stmt->close();
    // ID param exists, edit an existing device
    $page = 'Edit';
    if (isset($_POST['submit'])) {
        // Update the device
        $stmt = $con->prepare('UPDATE support SET device_id = ?, user_id = ?, status = ?, problem = ?, solution = ?, date = ? WHERE id = ?');
        $stmt->bind_param('ssssssi', $_POST['device_id'], $_POST['user_id'], $_POST['status'], $_POST['problem'], $_POST['solution'], $_POST['date'], $_GET['id']);
        $stmt->execute();
        header('Location: index.php');
		exit;
		 
    }
    if (isset($_POST['close'])) {
        // Close the Ticket
		$closed = '1';
        $stmt = $con->prepare('UPDATE support SET status = ? WHERE id = ?');
        $stmt->bind_param('si', $closed, $_GET['id']);
        $stmt->execute();
        header('Location: index.php');
        exit;
    }
} else {
    // Create a new account
    $page = 'Create';
    if (isset($_POST['submit'])) {
       $stmt = $con->prepare('INSERT IGNORE INTO support (device_id, user_id, status, problem, date) VALUES (?,?,?,?,?)');
        $stmt->bind_param('sssss', $_POST['device_id'], $_POST['user_id'], $_POST['status'], $_POST['problem'], $_POST['date']);
        $stmt->execute();
        header('Location: index.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width,minimum-scale=1">
		<title>Create Support Ticket</title>
		<link href="../style.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body class="loggedin">
		<nav class="navtop">
			<div>
				<h1>Create Support Ticket</h1>
				<ul class="nav-top">
				<li><a href="../home.php"><i class="fas fa-home"></i>Home</a></li>
				<li><a href="../profile.php"><i class="fas fa-user-circle"></i>Profile</a></li>
				<li><?php if ($_SESSION['role'] == 'Admin'): ?>
				<a href="../admin/index.php" target="_blank"><i class="fas fa-user-cog"></i>Admin</a>
				<?php endif; ?></li>
				<li><a href="index.php"><i class="fas fa-desktop"></i>Support</a></li>
				<li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a></li>
					</ul>
			</div>
		</nav>
		<div class="content">
			<h2>Support Areana</h2>
			<div class="buttons_link"><div class="button_links">
   			<a href="add_support.php">Create Support Ticket</a>
		</div>
			<?php if ($_SESSION['role'] == 'Admin' OR 'ICT Support'): ?> <div class="button_links">
   			<a href="index.php">Support Tickets</a>
		</div><?php endif; ?></div>
			<div class="block">
			<h2><?=$page?> Support Ticket</h2>
    <form action="" method="post" class="form responsive-width-100">
        <input type="hidden" id="user_id" name="user_id" value="<?php if (isset ($support['user_id'])){ echo $support['user_id']; } else { echo $_SESSION['id']; } ?>" required>
		<input type="hidden" id="date" name="date" value="<?php if (isset ($support['date'])){ echo $support['date']; } else { echo $date->format('d-m-Y H:i:s'); } ?>" required>
		<input type="hidden" id="status" name="status" value="2" required>
		<input type="hidden" id="response" name="response" value="3" required>
		<label for="username">Device ID</label>
        <input type="text" id="device_id" name="device_id" placeholder="e.g. 002" value="<?=$support['device_id']?>" required>
		<label for="username">problem</label>
        <textarea id="problem" name="problem" placeholder="Please be as detailed as possible when telling us what is wrong with your device" required><?=$support['problem']?></textarea>
		
        <div class="submit-btns">
            <input type="submit" name="submit" value="Submit">
            <?php if ($page == 'Edit'): ?>
            <input type="submit" name="close" value="close" class="delete">
            <?php endif; ?>
        </div>
    </form>
			</div>
		</div>
	</body>
</html>
