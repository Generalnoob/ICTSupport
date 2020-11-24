<?php
include 'main.php';
// Read the activation email template HTML file
$config_file = file_get_contents('../config.php');
preg_match_all('/define\(\'(.*?)\', \'(.*?)\'/', $config_file, $matches);
if (!empty($_POST)) {
    // Update the configuration file with the new keys and values
    foreach ($_POST as $k => $v) {
        $config_file = preg_replace('/define\(\'' . $k . '\'\, \'(.*?)\'/s', 'define(\'' . $k . '\', \'' . $v . '\'', $config_file);
    }
    file_put_contents('../config.php', $config_file);
    header('Location: settings.php');
    exit;
}
include 'header.php';
?>

<h2>Settings</h2>

<div class="content-block">
	** NOTE: Please make sure config.php is writable **
    <form action="" method="post" class="form responsive-width-100">
		<label>Site Name - Please name your site</label>
        <input type="text" name="Site_Name" value="<?=htmlspecialchars(Site_Name, ENT_QUOTES)?>" placeholder="<?=str_replace('_', ' ', Site_Name)?>">
		<label>Database Host - this is normaly localhost</label>
       	<input type="text" name="db_host" value="<?=htmlspecialchars(db_host, ENT_QUOTES)?>" placeholder="<?=str_replace('_', ' ', db_host)?>">
		<label>Database User - this is normaly found in your web hosting panel</label>
		<input type="text" name="db_user" value="<?=htmlspecialchars(db_user, ENT_QUOTES)?>" placeholder="<?=str_replace('_', ' ', db_user)?>">
		<label>Database Password - this is normaly found in your web hosting panel</label>
		<input type="text" name="db_pass" value="<?=htmlspecialchars(db_pass, ENT_QUOTES)?>" placeholder="<?=str_replace('_', ' ', db_pass)?>">
		<label>Database Name - this is normaly found in your web hosting panel</label>
		<input type="text" name="db_name" value="<?=htmlspecialchars(db_name, ENT_QUOTES)?>" placeholder="<?=str_replace('_', ' ', db_name)?>">
		<label>Database Charset - only change this is your language is not supported</label>
		<input type="text" name="db_charset" value="<?=htmlspecialchars(db_charset, ENT_QUOTES)?>" placeholder="<?=str_replace('_', ' ', db_charset)?>">
		<label>Require Account Activation - Set this to true is you wish for your users to activate their account, set /leave it on false to leave this feature disabled.</label>
		<input type="text" name="account_activation" value="<?=htmlspecialchars(account_activation, ENT_QUOTES)?>" placeholder="<?=str_replace('_', ' ', account_activation)?>">
		<label>Email From - Set this for the emails sent from ICT Support.</label>
		<input type="text" name="mail_from" value="<?=htmlspecialchars(mail_from, ENT_QUOTES)?>" placeholder="<?=str_replace('_', ' ', mail_from)?>">
		<label>Activation Link - This is the link to the activation file, it should be domain.com/activate.php unless you have the script in a subfolder.</label>
		<input type="text" name="activation_link" value="<?=htmlspecialchars(activation_link, ENT_QUOTES)?>" placeholder="<?=str_replace('_', ' ', activation_link)?>">
		<label>Site URL - this is the complete url for the script make sure to put http:// at the front, if using in a internal network make sure to us the IP address and not localhost e.g http://192.168.0.1/.</label>
		<input type="text" name="URL_Site" value="<?=htmlspecialchars(URL_Site, ENT_QUOTES)?>" placeholder="<?=str_replace('_', ' ', URL_Site)?>">
        <input type="submit" value="Save">
    </form>
</div>

<?=template_admin_footer()?>
