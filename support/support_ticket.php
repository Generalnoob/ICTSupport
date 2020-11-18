<?php
include '../main.php';
check_loggedin($con);
//Call Language File
languages($con);
include '../languages/'.languages($con).'.php';
// Default input product values
$support = array(
	'device_id' => '',
	'problem' => '',
	'solution' => '',
);
$date = new DateTime();
$page = 'Update';
if (isset($_GET['id'])) {
    // Get the device from the database
    $stmt = $con->prepare('SELECT id, device_id, user_id, status, problem, date FROM support WHERE id = ?');
    $stmt->bind_param('i', $_GET['id']);
    $stmt->execute();
    $stmt->bind_result($support['id'], $support['device_id'], $support['user_id'], $support['status'], $support['problem'], $support['date']);
    $stmt->fetch();
    $stmt->close();
	// Get the account from the database
    $atmt = $con->prepare('SELECT username FROM accounts WHERE id = ?');
    $atmt->bind_param('i', $support['user_id']);
    $atmt->execute();
    $atmt->bind_result($account['username']);
    $atmt->fetch();
    $atmt->close();
       // Update the device
        if (isset($_POST['submit'])) {
        $stmt = $con->prepare('INSERT IGNORE INTO support_chat (ticket_id, response, user_id, date, response_read) VALUES (?,?,?,?,?)');
        $stmt->bind_param('sssss', $_POST['ticket_id'], $_POST['response'], $_POST['user_id'], $_POST['date'], $_POST['response_read']);
        $stmt->execute();
        header('Location: support_ticket.php?id='.$_POST['ticket_id']);
        exit;
		 
    }
    if (isset($_POST['close'])) {
        // Close the Ticket
		$closed = '1';
        $stmt = $con->prepare('UPDATE support SET status = ? WHERE id = ?');
        $stmt->bind_param('si', $closed, $_GET['id']);
        $stmt->execute();
        header('Location: index.php');
        exit;
    }
}
?>
	<?php 
$Page_Name = $lang_Support_Ticket;
include '../template/header.php'; ?>
	<div class="content">
			<h2><?php echo $lang_Support_Areana; ?></h2>
			<div class="buttons_link"><div class="button_links">
   			<a href="add_support.php"><?php echo $lang_Creat_Support_Ticket; ?></a>
		</div>
			<div class="button_links">
   			<a href="index.php"><?php echo $lang_Support_Tickets; ?></a>
		</div></div>
			<div class="block">
			<h2><?php echo $lang_Update_Support_Ticket; ?></h2>
				<?php echo '<br>'.$lang_Device_ID.': '.$support['device_id'].' '.$lang_Support_Ticket_By.' '.$account['username'].'</br>'; echo ''.$lang_Issue.':</br > '.$support['problem'].'</br>';
				// Get the system specs from the database
    $dtmt = $con->prepare('SELECT id, device_type, department, device_id, motherboard, ram, processor, gpu, sound_card, wifi, bluetooth, simcard, make, model, camera, os FROM devices WHERE device_id = ?');
    $dtmt->bind_param('i', $support['device_id']);
    $dtmt->execute();
    $dtmt->bind_result($device['id'], $device['device_type'], $device['department'], $device['device_id'], $device['motherboard'], $device['ram'], $device['processor'], $device['gpu'], $device['sound_card'], $device['wifi'], $device['bluetooth'], $device['simcard'], $device['make'], $device['model'], $device['camera'], $device['os']);
    $dtmt->fetch();
    $dtmt->close();
				if ($device['wifi'] == '1'){$wifi = 'Yes';} else {$wifi = 'No';}
				if ($device['bluetooth'] == '1'){$bluetooth = 'Yes';} else {$bluetooth = 'No';}
				if ($device['camera'] == '1'){$camera = 'Yes';} else {$camera = 'No';}
				if ($device['simcard'] == '1'){$simcard = 'Yes';} else {$simcard = 'No';}
				echo '<div class="specs"><b>Device Type:</b> '.$device['device_type'].' <b>Department:</b> '.$device['department'].' <b>Mobo:</b> '.$device['motherboard'].' <b>Ram:</b> '.$device['ram'].' <b>processor:</b> '.$device['processor'].' <b>GPU:</b> '.$device['gpu'].' <b>Sound Card:</b> '.$device['sound_card'].' <b>WiFi:</b> '.$wifi.' <b>Operating System:</b> '.$device['os'].' <b>Bluetooth:</b> '.$bluetooth.' <b>Simcard:</b> '.$simcard.' <b>Make:</b> '.$device['make'].' <b>Model:</b> '.$device['model'].' <b>Camera:</b> '.$camera.'</div>';
				
				?>
	
		<?php // Get the account from the database
    			$ctmt = $con->prepare('SELECT id, ticket_id, response, user_id, date FROM support_chat WHERE ticket_id = '.$_GET['id'].'');
				$ctmt->execute();
				$ctmt->store_result();
				$ctmt->bind_result($id, $ticket_id, $response, $user_id, $date);	
		?>
		<table>
           <tbody>
                <?php if ($ctmt->num_rows == 0): ?>
                <tr>
                    <td colspan="4" style="text-align:center;">There are no responses</td>
                </tr>
                <?php else: ?>
                <?php while ($ctmt->fetch()): 
			   	$uctmt = $con->prepare('SELECT username FROM accounts WHERE id = ?');
    			$uctmt->bind_param('i', $user_id);
    			$uctmt->execute();
    			$uctmt->bind_result($username['username']);
    			$uctmt->fetch();
    			$uctmt->close(); ?>
					<td>
					<div class="username"><?php echo $lang_Username; ?> <?=$username['username']?></div>
					<div class="response"><?=$response?></div>
					</td>
				</tr>
                <?php endwhile; ?>
                <?php endif; ?>
            </tbody>
		</table>
				
				<form action="" method="post" class="form responsive-width-100">
        <input type="hidden" id="user_id" name="user_id" value="<?=$_SESSION['id']?>" required>
		<input type="hidden" id="ticket_id" name="ticket_id" value="<?php echo $_GET['id']; ?>" required>
		<input type="hidden" id="date" name="date" value="<?php if (isset ($support['date'])){ echo $support['date']; } else { echo $date->format('d-m-Y H:i:s'); } ?>" required>
		<input type="hidden" id="status" name="status" value="2" required>
					<?php if ($_SESSION['role'] == 'Admin' OR 'ICT Support'): ?>
		<input type="hidden" id="response_read" name="response_read" value="2" required>
					<?php else: ?>
		<input type="hidden" id="response_read" name="response_read" value="1" required>
					<?php endif; ?>
		<label for="username"><?php echo $lang_Responed; ?></label>
        <textarea id="response" name="response" placeholder="" required></textarea>
		<p></p>
        <div class="submit-btns">
            <input type="submit" name="submit" value="Submit">
            <?php if ($page == 'Update'): ?>
            <input type="submit" name="close" value="close" class="delete">
            <?php endif; ?>
        </div>
    </form>
			</div>
		</div>
<div class="footer">Created By David Lomas | ICTSupport <?=$version?> GNU GPL</div>
	</body>
</html>
