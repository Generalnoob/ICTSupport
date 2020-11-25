<?php
//Call main functions
include '../main.php';
check_loggedin($con);
//Call Language File
languages($con);
include '../languages/'.languages($con).'.php';
// Default input product values
$support = array(
	'device_id' => '',
	'problem' => '',
	'solution' => '',
);
$date = new DateTime();

if (isset($_GET['id'])) {
    // Get the account from the database
    $stmt = $con->prepare('SELECT id, device_id, user_id, status, problem, solution, date, title FROM support WHERE id = ?');
    $stmt->bind_param('i', $_GET['id']);
    $stmt->execute();
    $stmt->bind_result($support['id'], $support['device_id'], $support['user_id'], $support['status'], $support['problem'], $support['solution'], $support['date'], $support['title']);
    $stmt->fetch();
    $stmt->close();
    // ID param exists, edit an existing device
    $page = 'Edit';
    if (isset($_POST['submit'])) {
        // Update the device
        $stmt = $con->prepare('UPDATE support SET device_id = ?, user_id = ?, status = ?, problem = ?, solution = ?, date = ?, title = ? WHERE id = ?');
        $stmt->bind_param('sssssssi', $_POST['device_id'], $_POST['user_id'], $_POST['status'], $_POST['problem'], $_POST['solution'], $_POST['date'], $_POST['title'], $_GET['id']);
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
       $stmt = $con->prepare('INSERT IGNORE INTO support (device_id, user_id, status, problem, date, title) VALUES (?,?,?,?,?,?)');
        $stmt->bind_param('ssssss', $_POST['device_id'], $_POST['user_id'], $_POST['status'], $_POST['problem'], $_POST['date'], $_POST['title']);
        $stmt->execute();
        header('Location: index.php');
        exit;
    }
}

$Page_Name = $lang_Support_Areana;
include '../template/'.Site_Theme.'/header.php'; ?>
		<div class="content">
			<h2><?php echo $lang_Support_Areana; ?></h2>
			<div class="buttons_link"><div class="button_links">
   			<a href="add_support.php"><?php echo $lang_Creat_Support_Ticket; ?></a>
		</div>
			<?php if ($_SESSION['role'] == 'Admin' OR 'ICT Support'): ?> <div class="button_links">
   			<a href="index.php"><?php echo $lang_Support_Tickets; ?></a>
		</div><?php endif; ?></div>
			<div class="block">
			<h2><?=$page?> <?php echo $lang_Support_Ticket; ?></h2>
    <form action="" method="post" class="form responsive-width-100">
        <input type="hidden" id="user_id" name="user_id" value="<?php if (isset ($support['user_id'])){ echo $support['user_id']; } else { echo $_SESSION['id']; } ?>" required>
		<input type="hidden" id="date" name="date" value="<?php if (isset ($support['date'])){ echo $support['date']; } else { echo $date->format('Y-m-d H:i:s'); } ?>" required>
		<input type="hidden" id="status" name="status" value="2" required>
		<input type="hidden" id="response" name="response" value="3" required>
		<label for="username"><?php echo $lang_Device_ID; ?></label>
        <input type="text" id="device_id" name="device_id" placeholder="e.g. 002" value="<?=$support['device_id']?>" required>
		<label for="username"><?php echo $lang_Device_Title; ?></label>
        <input type="text" id="title" name="title" placeholder="Please be short but descriptive" value="" required>
		<label for="username"><?php echo $lang_Issue; ?></label>
        <textarea id="problem" name="problem" placeholder="<?php echo $lang_Detailed_As_Possible; ?>" required><?=$support['problem']?></textarea>
		
        <div class="submit-btns">
            <input type="submit" name="submit" value="Submit">
            <?php if ($page == 'Edit'): ?>
            <input type="submit" name="close" value="close" class="delete">
            <?php endif; ?>
        </div>
    </form>
			</div>
		</div>
<?php include '../template/'.Site_Theme.'/footer.php'; ?>