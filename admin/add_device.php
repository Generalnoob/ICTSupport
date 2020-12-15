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
$devices_type = array('Tower PC', 'Mini PC', 'Laptop', 'Tablet', 'Phone');
$devices_os = array('Windows 10', 'Windows 8', 'Windows 7', 'Windows vista', 'Windows XP', 'Linux', 'Suse', 'Redhat', 'Fedora', 'Centos', 'Ubuntu', 'Chrome OS', 'Mac OS', 'Apple iOS', 'Android');
if (isset($_GET['id'])) {
    // Get the account from the database
    $stmt = $pdo->prepare('SELECT * FROM devices WHERE id = ?');
    $stmt->execute([ $_GET['id'] ]);
	$device = $stmt->fetch(PDO::FETCH_ASSOC);
    // ID param exists, edit an existing device
    $page = 'Edit';
    if (isset($_POST['submit'])) {
        // Update the device
        $stmt = $pdo->prepare('UPDATE devices SET device_type = ?, department = ?, device_id = ?, motherboard = ?, ram = ?, processor = ?, gpu = ?, sound_card = ?, wifi = ?, bluetooth = ?, simcard = ?, make = ?, model = ?, camera = ?, os = ? WHERE id = ?');
        $stmt->execute([ $_POST['device_type'], $_POST['department'], $_POST['device_id'], $_POST['motherboard'], $_POST['ram'], $_POST['processor'], $_POST['gpu'], $_POST['sound_card'], $_POST['wifi'], $_POST['bluetooth'], $_POST['simcard'], $_POST['make'], $_POST['model'], $_POST['camera'], $_POST['device_os'], $_GET['id'] ]);
        header('Location: devices.php');
		exit;
		 
    }
    if (isset($_POST['delete'])) {
        // Delete the account
        $stmt = $pdo->prepare('DELETE FROM devices WHERE id = ?');
        $stmt->execute([ $_GET['id'] ]);
        header('Location: devices.php');
        exit;
    }
} else {
    // Create a new account
    $page = 'Create';
    if (isset($_POST['submit'])) {
		
       $stmt = $pdo->prepare('INSERT IGNORE INTO devices (device_type, department, device_id, motherboard, ram, processor, gpu, sound_card, wifi, bluetooth, simcard, make, model, camera, os) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
        $stmt->execute([ $_POST['device_type'], $_POST['department'], $_POST['device_id'], $_POST['motherboard'], $_POST['ram'], $_POST['processor'], $_POST['gpu'], $_POST['sound_card'], $_POST['wifi'], $_POST['bluetooth'], $_POST['simcard'], $_POST['make'], $_POST['model'], $_POST['camera'], $_POST['device_os'] ]);
        header('Location: devices.php');
        exit;
    }
}

include 'header.php';
?>
<script src="jquery-3.4.1.min.js" type="text/javascript"></script>
<h2><?=$page?> Device</h2>

<div class="content-block">
    <form action="" method="post" class="form responsive-width-100">
        <label for="device_id">Device ID</label>
		 <input type="text" id="device_id" name="device_id" placeholder="Device ID" value="<?=$device['device_id']?>" required>
		<!-- Response -->
   <div id="uname_response" ></div>
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
<script type="text/javascript">
$(document).ready(function(){

   $("#device_id").keyup(function(){

     var device_id = $(this).val().trim();

     if(device_id != ''){

        $.ajax({
           url: 'ajaxfile.php',
           type: 'post',
           data: {device_id:device_id},
           success: function(response){

              // Show response
              $("#uname_response").html(response);

           }
        });
     }else{
        $("#uname_response").html("");
     }

  });

});
</script>

<?=template_admin_footer()?>
