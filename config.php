<?php
ini_set('display_errors',1);
error_reporting(E_ALL);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
// database hostname, you don't usually need to change this
define('db_host', 'localhost');
// database username
define('db_user', 'root');
// database password
define('db_pass', 'pwdpwd');
// database name
define('db_name', 'ict-support');
// database charset, change this only if utf8 is not supported by your language
define('db_charset', 'utf8');
// Email activation variables
// account activation required?
define('account_activation', 'false');
// Change "Your Company Name" and "yourdomain.com", do not remove the < and >
define('mail_from', 'Your Company Name <noreply@yourdomain.com>');
// Link to activation file, update this
define('activation_link', 'localhost/activate.php');


?>
