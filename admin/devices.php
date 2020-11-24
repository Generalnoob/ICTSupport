<?php
include 'main.php';
// query to get all accounts from the database
$stmt = $con->prepare('SELECT id, device_type, department, device_id, motherboard, ram, processor, gpu, sound_card, wifi, bluetooth, simcard, make, model, camera, os FROM devices');
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($id, $device_type, $department, $device_id, $motherboard, $ram, $processor, $gpu, $sound_card, $wifi, $bluetooth, $simcard, $make, $model, $camera, $device_os);
include 'header.php';
?>

<h2>Devices</h2>

<div class="links">
    <a href="add_device.php">Create Device</a>
</div>

<div class="content-block">
    <div class="table">
        <table>
            <thead>
                <tr>
                    <td>#</td>
                    <td>Device Type</td>
					<td>Device OS</td>
                    <td>Device ID</td>
                    <td>Motherboard</td>
                    <td>Ram</td>
					<td>Processor</td>
                    <td>GPU</td>
                    <td>Sound Card</td>
                    <td>WiFi</td>
					<td>Bluetooth</td>
					<td>Camera</td>
                    <td>Simcard</td>
                    <td>Make</td>
                    <td>Model</td>
                    <td>Department</td>
                </tr>
            </thead>
            <tbody>
                <?php if ($stmt->num_rows == 0): ?>
                <tr>
                    <td colspan="8" style="text-align:center;">There are no accounts</td>
                </tr>
                <?php else: ?>
                <?php while ($stmt->fetch()): ?>
                <tr class="details" onclick="location.href='add_device.php?id=<?=$id?>'">
					<td></td>
                    <td><?=$device_type?></td>
					<td><?=$device_os?></td>
                    <td><?=$device_id?></td>
                    <td><?=$motherboard?></td>
                    <td><?=$ram?></td>
                    <td><?=$processor?></td>
                    <td><?=$gpu?></td>
					<td><?=$sound_card?></td>
					<td><?php if ($wifi == 1){echo 'Yes';} else { echo 'No';}?></td>
                    <td><?php if ($bluetooth == 1){echo 'Yes';} else { echo 'No';}?></td>
					<td><?php if ($camera == 1){echo 'Yes';} else { echo 'No';}?></td>
					<td><?php if ($simcard == 1){echo 'Yes';} else { echo 'No';}?></td>
					<td><?=$make?></td>
                    <td><?=$model?></td>
                    <td><?=$department?></td>
                </tr>
                <?php endwhile; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?=template_admin_footer()?>
