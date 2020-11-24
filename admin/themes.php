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
    header('Location: themes.php');
    exit;
}
include 'header.php';
?>

<h2>Themes</h2>

<div class="content-block">
    <div class="table">
		<div class="info">Please selete a template to use, to make your own simply copy the "default" folder and rename it then edit it to your liking!</div>
		<form action="" method="post" class="form responsive-width-100">
			<label>Theme - Below are the templates currently avaliable.</label>
			<select id="Site_Theme" name="Site_Theme" style="margin-bottom: 30px;">
				<?php
	if (Site_Theme == ''){echo Site_Theme;} else {echo '<option value="'.Site_Theme.'">'.Site_Theme.'</option>';}
	// Return an array with the list of sub directories of $dir
	$scan = scandir('../template');
foreach($scan as $file) {
   if (is_dir("../template/$file")) {
	    if ($file != '.' && $file != '..'){
      echo '<option value="'.$file.'">'.$file.'</option>';
   }}
}
		?>
        </select>
		<label>Add Logo - If you want to use a logo next to the site title please put true otherwise leave it on falses.</label>
		<input type="text" name="add_logo" value="<?=htmlspecialchars(add_logo, ENT_QUOTES)?>" placeholder="<?=str_replace('_', ' ', add_logo)?>">
		<label>Logo Location and Name - must be the global address with http:// in the begining.</label>
		<input type="text" name="logo" value="<?=htmlspecialchars(logo, ENT_QUOTES)?>" placeholder="<?=str_replace('_', ' ', logo)?>">
        <input type="submit" value="Save">
			</form>
    </div>
</div>

<?=template_admin_footer()?>
