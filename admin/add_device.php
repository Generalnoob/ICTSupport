<?php
include 'main.php';
// Default input product values
$device = array(
	'id' => '',
	'device_type' => '',
	'department' => '',
	'device_id' => '',
	'motherboard' => '',
	'ram' => '',
	'processor' => '',
	'gpu' => '',
	'sound_card' => '',
	'wifi' => '',
	'bluetooth' => '',
	'simcard' => '',
	'make' => '',
	'model' => '',
	'camera' => '',
	'device_os' => ''
);
$devices_type = array('Tower PC', 'Mini PC', 'Tablet', 'Phone');
$devices_os = array('Windows 10', 'Windows 8', 'Windows 7', 'Windows vista', 'Windows XP', 'Linux', 'Chrome OS', 'Ubuntu', 'Mac OS', 'Debian', 'Apple iOS', 'Android');
if (isset($_GET['id'])) {
    // Get the account from the database
    $stmt = $con->prepare('SELECT id, device_type, department, device_id, motherboard, ram, processor, gpu, sound_card, wifi, bluetooth, simcard, make, model, camera, os FROM devices WHERE id = ?');
    $stmt->bind_param('i', $_GET['id']);
    $stmt->execute();
    $stmt->bind_result($device['id'], $device['device_type'], $device['department'], $device['device_id'], $device['motherboard'], $device['ram'], $device['processor'], $device['gpu'], $device['sound_card'], $device['wifi'], $device['bluetooth'], $device['simcard'], $device['make'], $device['model'], $device['camera'], $device['device_os']);
    $stmt->fetch();
    $stmt->close();
    // ID param exists, edit an existing device
    $page = 'Edit';
    if (isset($_POST['submit'])) {
        // Update the device
        $stmt = $con->prepare('UPDATE devices SET device_type = ?, department = ?, device_id = ?, motherboard = ?, ram = ?, processor = ?, gpu = ?, sound_card = ?, wifi = ?, bluetooth = ?, simcard = ?, make = ?, model = ?, camera = ?, os = ? WHERE id = ?');
        $stmt->bind_param('sssssssssssssssi', $_POST['device_type'], $_POST['department'], $_POST['device_id'], $_POST['motherboard'], $_POST['ram'], $_POST['processor'], $_POST['gpu'], $_POST['sound_card'], $_POST['wifi'], $_POST['bluetooth'], $_POST['simcard'], $_POST['make'], $_POST['model'], $_POST['camera'], $_POST['device_os'], $_GET['id']);
        $stmt->execute();
        header('Location: devices.php');
		exit;
		 
    }
    if (isset($_POST['delete'])) {
        // Delete the account
        $stmt = $con->prepare('DELETE FROM devices WHERE id = ?');
        $stmt->bind_param('i', $_GET['id']);
        $stmt->execute();
        header('Location: devices.php');
        exit;
    }
} else {
    // Create a new account
    $page = 'Create';
    if (isset($_POST['submit'])) {
       $stmt = $con->prepare('INSERT IGNORE INTO devices (device_type, department, device_id, motherboard, ram, processor, gpu, sound_card, wifi, bluetooth, simcard, make, model, camera, os) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
        $stmt->bind_param('sssssssssssssss', $_POST['device_type'], $_POST['department'], $_POST['device_id'], $_POST['motherboard'], $_POST['ram'], $_POST['processor'], $_POST['gpu'], $_POST['sound_card'], $_POST['wifi'], $_POST['bluetooth'], $_POST['simcard'], $_POST['make'], $_POST['model'], $_POST['camera'], $_POST['device_os']);
        $stmt->execute();
        header('Location: devices.php');
        exit;
    }
}
?>

<?=template_admin_header($page . ' Device')?>

<h2><?=$page?> Device</h2>

<div class="content-block">
    <form action="" method="post" class="form responsive-width-100">
        <label for="username">Device ID</label>
        <input type="text" id="device_id" name="device_id" placeholder="Device ID" value="<?=$device['device_id']?>" required>
		<label for="username">Device Type</label>
        <select id="device_type" name="device_type" style="margin-bottom: 30px;">
			<?php if (isset ($device['device_type'])) {echo '<option value="'.$device['device_type'].'">'.$device['device_type'].'</option>';} ?>
            <?php foreach ($devices_type as $device_type): ?>
            <option value="<?=$device_type?>"<?=$device_type==$device['device_type']?' selected':''?>><?=$device_type?></option>
            <?php endforeach; ?>
        </select>
		<label for="username">Device Operating System</label>
        <select id="device_os" name="device_os" style="margin-bottom: 30px;">
			<?php if (isset ($device['device_os'])) {echo '<option value="'.$device['device_os'].'">'.$device['device_os'].'</option>';} ?>
			<?php foreach ($devices_os as $device_os): ?>
            <option value="<?=$device_os?>"<?=$device_os==$device['device_os']?' selected':''?>><?=$device_os?></option>
            <?php endforeach; ?>
        </select>
		<label for="username">Department</label>
        <input type="text" id="department" name="department" placeholder="Department" value="<?=$device['department']?>" required>
		<label for="username">Motherboard</label>
        <input type="text" id="motherboard" name="motherboard" placeholder="Motherboard" value="<?=$device['motherboard']?>" required>
		<label for="username">RAM</label>
        <input type="text" id="ram" name="ram" placeholder="RAM" value="<?=$device['ram']?>" required>
		<label for="username">Processor</label>
        <input type="text" id="processor" name="processor" placeholder="Processor" value="<?=$device['processor']?>" required>
		<label for="username">GPU</label>
        <input type="text" id="gpu" name="gpu" placeholder="GPU" value="<?=$device['gpu']?>" required>
		<label for="username">Sound Card</label>
        <input type="text" id="sound_card" name="sound_card" placeholder="Sound Card" value="<?=$device['sound_card']?>" required>
		<label for="username">WiFi</label>
		<select id="wifi" name="wifi" style="margin-bottom: 30px;">
        	<?php if ($device['wifi'] == 1) {echo '<option value="1">Yes</option>';} ?>
			<?php if ($device['wifi'] == 0) {echo '<option value="0">No</option>';} ?>
			<option value="1">Yes</option>
			<option value="0">No</option>
        </select>
		<label for="username">Bluetooth</label>
		<select id="bluetooth" name="bluetooth" style="margin-bottom: 30px;">
			<?php if ($device['bluetooth'] == 1) {echo '<option value="1">Yes</option>';} ?>
			<?php if ($device['bluetooth'] == 0) {echo '<option value="0">No</option>';} ?>
            <option value="1">Yes</option>
			<option value="0">No</option>
        </select>
		<label for="Camera">Camera</label>
		<select id="camera" name="camera" style="margin-bottom: 30px;">
			<?php if ($device['camera'] == 1) {echo '<option value="1">Yes</option>';} ?>
			<?php if ($device['camera'] == 0) {echo '<option value="0">No</option>';} ?>
            <option value="1">Yes</option>
			<option value="0">No</option>
        </select>
		<label for="Simcard">Simcard</label>
		<select id="simcard" name="simcard" style="margin-bottom: 30px;">
			<?php if ($device['simcard'] == 1) {echo '<option value="1">Yes</option>';} ?>
			<?php if ($device['simcard'] == 0) {echo '<option value="0">No</option>';} ?>
            <option value="1">Yes</option>
			<option value="0">No</option>
        </select>
		<label for="username">Make</label>
        <input type="text" id="make" name="make" placeholder="Make" value="<?=$device['make']?>" required>
		<label for="username">Model</label>
        <input type="text" id="model" name="model" placeholder="Model" value="<?=$device['model']?>" required>
		
		
        <div class="submit-btns">
            <input type="submit" name="submit" value="Submit">
            <?php if ($page == 'Edit'): ?>
            <input type="submit" name="delete" value="Delete" class="delete">
            <?php endif; ?>
        </div>
    </form>
</div>

<?=template_admin_footer()?>
