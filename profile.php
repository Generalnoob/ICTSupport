<?php
include 'main.php';
check_loggedin($con);
languages($con);
include 'languages/'.languages($con).'.php';
// output message (errors, etc)
$msg = '';
// We don't have the password or email info stored in sessions so instead we can get the results from the database.
$stmt = $con->prepare('SELECT password, email, activation_code, role FROM accounts WHERE id = ?');
// In this case we can use the account ID to get the account info.
$stmt->bind_param('i', $_SESSION['id']);
$stmt->execute();
$stmt->bind_result($password, $email, $activation_code, $role);
$stmt->fetch();
$stmt->close();
// Handle edit profile post data
if (isset($_POST['username'], $_POST['password'], $_POST['cpassword'], $_POST['email'])) {
	// Make sure the submitted registration values are not empty.
	if (empty($_POST['username']) || empty($_POST['email'])) {
		$msg = $lang_Input_Is_Empty;
	} else if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
		$msg = $lang_Valid_Email;
	} else if (!preg_match('/^[a-zA-Z0-9]+$/', $_POST['username'])) {
	    $msg = $lang_Valid_Username;
	} else if (!empty($_POST['password']) && (strlen($_POST['password']) > 20 || strlen($_POST['password']) < 5)) {
		$msg = $lang_Password_Must;
	} else if ($_POST['cpassword'] != $_POST['password']) {
		$msg = $lang_Password_Match;
	}
	if (empty($msg)) {
		// Check if new username or email already exists in database
		$stmt = $con->prepare('SELECT * FROM accounts WHERE (username = ? OR email = ?) AND username != ? AND email != ?');
		$stmt->bind_param('ssss', $_POST['username'], $_POST['email'], $_SESSION['name'], $email);
		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows > 0) {
			$msg = 'Account already exists with that username and/or email!';
		} else {
			// no errors occured, update the account...
			$stmt->close();
			$uniqid = account_activation == 'true' && $email != $_POST['email'] ? uniqid() : $activation_code;
			$stmt = $con->prepare('UPDATE accounts SET username = ?, password = ?, email = ?, activation_code = ? WHERE id = ?');
			// We do not want to expose passwords in our database, so hash the password and use password_verify when a user logs in.
			$password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : $password;
			$stmt->bind_param('ssssi', $_POST['username'], $password, $_POST['email'], $uniqid, $_SESSION['id']);
			$stmt->execute();
			$stmt->close();
			// Update the session variables
			$_SESSION['name'] = $_POST['username'];
			if (account_activation == 'true' && $email != $_POST['email']) {
				// Account activation required, send the user the activation email with the "send_activation_email" function from the "main.php" file
				send_activation_email($_POST['email'], $uniqid);
				// Log the user out
				unset($_SESSION['loggedin']);
				$msg = 'You have changed your email address, you need to re-activate your account!';
			} else {
				// profile updated redirect the user back to the profile page and not the edit profile page
				header('Location: profile.php');
				exit;
			}
		}
	}
}
include 'template/'.Site_Theme.'/header.php'; 
?>
		<?php if (!isset($_GET['action'])): ?>
		<div class="content profile">
			<h2><?php echo $lang_Profile_Page; ?></h2>
			<div class="block">
				<p><?php echo $lang_Account_Details; ?></p>
				<table>
					<tr>
						<td><?php echo $lang_Username; ?></td>
						<td><?=$_SESSION['name']?></td>
					</tr>
					<tr>
						<td><?php echo $lang_Email; ?></td>
						<td><?=$email?></td>
					</tr>
					<tr>
						<td><?php echo $lang_Role; ?></td>
						<td><?=$role?></td>
					</tr>
				</table>
				<a class="profile-btn" href="profile.php?action=edit"><?php echo $lang_Edit_Details; ?></a>
			</div>
		</div>
		<?php elseif ($_GET['action'] == 'edit'): ?>
		<div class="content profile">
			<h2>Edit Profile Page</h2>
			<div class="block">
				<form action="profile.php?action=edit" method="post">
					<label for="username"><?php echo $lang_Username; ?></label>
					<input type="text" value="<?=$_SESSION['name']?>" name="username" id="username" placeholder="Username">
					<label for="password"><?php echo $lang_Password; ?></label>
					<input type="password" name="password" id="password" placeholder="Password">
					<label for="cpassword"><?php echo $lang_Confirm_Password; ?></label>
					<input type="password" name="cpassword" id="cpassword" placeholder="Confirm Password">
					<label for="email"><?php echo $lang_Email; ?></label>
					<input type="email" value="<?=$email?>" name="email" id="email" placeholder="Email">
					<br>
					<input class="profile-btn" type="submit" value="Save">
					<p><?=$msg?></p>
				</form>
			</div>
		</div>
		<?php endif; ?>
<?php include 'template/'.Site_Theme.'/footer.php'; ?>
