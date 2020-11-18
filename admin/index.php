<?php
include 'main.php';
// query to get all accounts from the database
$stmt = $con->prepare('SELECT id, username, password, email, activation_code, role FROM accounts ORDER BY id DESC LIMIT 5');
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($id, $username, $password, $email, $activation_code, $role);
// query to get all devices from the database
$dtmt = $con->prepare('SELECT id, device_type, department, device_id, make, model FROM devices ORDER BY id DESC LIMIT 5');
$dtmt->execute();
$dtmt->store_result();
$dtmt->bind_result($id, $device_type, $department, $device_id, $make, $model);
// query to get all support tickets from the database
$xtmt = $con->prepare('SELECT id, device_id, user_id, status, problem, date FROM support WHERE status = 2 ORDER BY date DESC LIMIT 5');
$xtmt->execute();
$xtmt->store_result();
$xtmt->bind_result($id, $device_id, $user_id, $status, $problem, $date);
?>

<?=template_admin_header('Admin Home')?>

<h2>Home</h2>

<div class="content-block">
    <div class="table_users">
		<div class="title">Latest Accounts</div>
		<div class="sub_title">Total Users: <?php  echo $stmt->num_rows; ?></div>
        <table  cellspacing="0">
            <thead>
                <tr>
                    <td>#</td>
                    <td>Username</td>
                    <td class="responsive-hidden">Email</td>
                    <td class="responsive-hidden">Activation Code</td>
                    <td class="responsive-hidden">Role</td>
                </tr>
            </thead>
            <tbody>
                <?php if ($stmt->num_rows == 0): ?>
                <tr>
                    <td colspan="8" style="text-align:center;">There are no accounts</td>
                </tr>
                <?php else: ?>
                <?php while ($stmt->fetch()): ?>
                <tr class="details" onclick="location.href='account.php?id=<?=$id?>'">
                    <td></td>
                    <td><?=$username?></td>                 
                    <td class="responsive-hidden"><?=$email?></td>
                    <td class="responsive-hidden"><?=$activation_code?></td>
                    <td class="responsive-hidden"><?=$role?></td>
                </tr>
                <?php endwhile; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
	<div class="table_devices">
		<div class="title">Latest Devices</div>
		<div class="sub_title">Total Devices: <?php  echo $dtmt->num_rows; ?></div>
        <table  cellspacing="0">
            <thead>
                <tr>
                    <td>#</td>
                    <td>Device ID</td>
                    <td class="responsive-hidden">Device Type</td>
                    <td class="responsive-hidden">Department</td>
                    <td class="responsive-hidden">Make</td>
					<td class="responsive-hidden">Model</td>
                </tr>
            </thead>
            <tbody>
                <?php if ($stmt->num_rows == 0): ?>
                <tr>
                    <td colspan="8" style="text-align:center;">There are no support Tickets</td>
                </tr>
                <?php else: ?>
                <?php while ($dtmt->fetch()): ?>
                <tr class="details" onclick="location.href='add_device.php?id=<?=$id?>'">
                    <td></td>
                    <td><?=$device_id?></td>                 
                    <td class="responsive-hidden"><?=$device_type?></td>
                    <td class="responsive-hidden"><?=$department?></td>
                    <td class="responsive-hidden"><?=$make?></td>
					<td class="responsive-hidden"><?=$model?></td>
                </tr>
                <?php endwhile; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
	<div class="table_devices">
		<div class="title">Latest Support Tickets</div>
		<div class="sub_title">Total Open Tickets: <?php  echo $xtmt->num_rows; ?></div>
        <table  cellspacing="0">
            <thead>
                <tr>
                    <td>#</td>
                    <td>Device ID</td>
                    <td class="responsive-hidden">Problem</td>
                    <td class="responsive-hidden">Status</td>
                    <td class="responsive-hidden">Date</td>
				</tr>
            </thead>
            <tbody>
                <?php if ($xtmt->num_rows == 0): ?>
                <tr>
                    <td colspan="8" style="text-align:center;">There are no devices</td>
                </tr>
                <?php else: ?>
                <?php while ($xtmt->fetch()): ?>
                <tr class="details">
                    <td></td>
                    <td><?=$device_id?></td>                 
                    <td class="responsive-hidden"><?php echo substr($problem,0,40); ?>... </td>
                    <td class="responsive-hidden"><?=$status?></td>
                    <td class="responsive-hidden"><?=$date?></td>
                </tr>
                <?php endwhile; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?=template_admin_footer()?>
