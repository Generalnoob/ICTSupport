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
?>

<?=template_admin_header('Email Template')?>

<h2>Settings</h2>

<div class="content-block">
    <form action="" method="post" class="form responsive-width-100">
        <?php for($i = 0; $i < count($matches[1]); $i++): ?>
        <label><?=str_replace('_', ' ', $matches[1][$i])?></label>
        <input type="text" name="<?=$matches[1][$i]?>" value="<?=htmlspecialchars($matches[2][$i], ENT_QUOTES)?>" placeholder="<?=str_replace('_', ' ', $matches[1][$i])?>">
        <?php endfor; ?>
        <input type="submit" value="Save">
    </form>
</div>

<?=template_admin_footer()?>
