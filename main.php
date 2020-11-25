<?php
// The main file contains the database connection, session initializing, and functions, other PHP files will depend on this file.
// Include the configuration file
include_once 'config.php';
// We need to use sessions, so you should always start sessions using the below function
session_start();
// Connect to the MySQL database using MySQLi
ini_set('display_errors',1);
error_reporting(E_ALL);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$con = mysqli_connect(db_host, db_user, db_pass, db_name);
if (mysqli_connect_errno()) {
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}
// Update the charset
mysqli_set_charset($con, db_charset);
// The below function will check if the user is logged-in and also check the remember me cookie
function check_loggedin($con, $redirect_file = URL_Site.'index.php') {
	// Check for remember me cookie variable and loggedin session variable
    if (isset($_COOKIE['rememberme']) && !empty($_COOKIE['rememberme']) && !isset($_SESSION['loggedin'])) {
    	// If the remember me cookie matches one in the database then we can update the session variables.
    	$stmt = $con->prepare('SELECT id, username, role FROM accounts WHERE rememberme = ?');
		$stmt->bind_param('s', $_COOKIE['rememberme']);
		$stmt->execute();
		$stmt->store_result();
		if ($stmt->num_rows > 0) {
			// Found a match, update the session variables and keep the user logged-in
			$stmt->bind_result($id, $username, $role);
			$stmt->fetch();
            $stmt->close();
			session_regenerate_id();
			$_SESSION['loggedin'] = TRUE;
			$_SESSION['name'] = $username;
			$_SESSION['id'] = $id;
			$_SESSION['role'] = $role;
		} else {
			// If the user is not remembered redirect to the login page.
			header('Location: ' . $redirect_file);
			exit;
		}
    } else if (!isset($_SESSION['loggedin'])) {
    	// If the user is not logged in redirect to the login page.
    	header('Location: ' . $redirect_file);
    	exit;
    }
}
// Send activation email function
function send_activation_email($email, $code) {
	$subject = 'Account Activation Required';
	$headers = 'From: ' . mail_from . "\r\n" . 'Reply-To: ' . mail_from . "\r\n" . 'Return-Path: ' . mail_from . "\r\n" . 'X-Mailer: PHP/' . phpversion() . "\r\n" . 'MIME-Version: 1.0' . "\r\n" . 'Content-Type: text/html; charset=UTF-8' . "\r\n";
	$activate_link = activation_link . '?email=' . $email . '&code=' . $code;
	$email_template = str_replace('%link%', $activate_link, file_get_contents('activation-email-template.html'));
	mail($email, $subject, $email_template, $headers);
}

function languages($con) {
	// Check for language
    
    	$lang = $con->prepare('SELECT slug FROM languages WHERE active = 1');
		$lang->execute();
		$lang->store_result();
		if ($lang->num_rows > 0) {
			// Found a match
			$lang->bind_result($slug);
			$lang->fetch();
            $lang->close();
			session_regenerate_id();
			$_SESSION['slug'] = $slug;
			return $slug;
		}
}

function loginAttempts($con, $update = TRUE) {
	$ip = $_SERVER['REMOTE_ADDR'];
	$now = date('Y-m-d H:i:s');
	if ($update) {
		$stmt = $con->prepare('INSERT INTO login_attempts (ip_address, `date`) VALUES (?,?) ON DUPLICATE KEY UPDATE attempts_left = attempts_left - 1, `date` = VALUES(`date`)');
		$stmt->bind_param('ss', $ip, $now);
		$stmt->execute();
		$stmt->close();
	}
	$stmt = $con->prepare('SELECT * FROM login_attempts WHERE ip_address = ?');
	$stmt->bind_param('s', $ip);
	$stmt->execute();
	$result = $stmt->get_result();
	$login_attempts = $result->fetch_array(MYSQLI_ASSOC);
	$stmt->close();
	if ($login_attempts) {
		// The user can try to login after 1 day... change the "+1 day" if you want increase/decrease this date.
		$expire = date('Y-m-d H:i:s', strtotime('+1 day', strtotime($login_attempts['date'])));
		if ($now > $expire) {
			$stmt = $con->prepare('DELETE FROM login_attempts WHERE ip_address = ?');
			$stmt->bind_param('s', $ip);
			$stmt->execute();
			$stmt->close();
			$login_attempts = array();
		}
	}
	return $login_attempts;
}
// ICT Support Version
$version = '0.0.05v';

// Template URLS
$URL_HOME = URL_Site.'home.php';
$URL_PROFILE = URL_Site.'profile.php';
$URL_ADMIN = URL_Site.'admin/index.php';
$URL_SUPPORT = URL_Site.'support/index.php';
$URL_LOGOUT = URL_Site.'logout.php';

// Template logo
function Add_Logo() {
if (add_logo == 'true'){ ?>
	<img src="<?php echo logo; ?>" alt="<?php echo Site_Name; ?>" height="55px"/> <h1><?php echo Site_Name; ?></h1>
<?php } else { ?>
	<h1><?php echo Site_Name; ?></h1>
<?php }
}
// 
function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'y',
        'm' => 'm',
        'w' => 'w',
        'd' => 'd',
        'h' => 'h',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . '' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . '' : 'just now';
}
?>
