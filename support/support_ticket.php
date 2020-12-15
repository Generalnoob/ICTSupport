<?php
include '../main.php';
check_loggedin($pdo);
//Call Language File
languages($pdo);
include '../languages/'.languages($pdo).'.php';
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
    $stmt = $pdo->prepare('SELECT * FROM support WHERE id = ?');
    $stmt->execute([ $_GET['id'] ]);
    $support = $stmt->fetch(PDO::FETCH_ASSOC);
	
	// Get the account from the database
	$atmt = $pdo->prepare('SELECT username FROM accounts WHERE id = ?');
    $atmt->execute([ $support['user_id'] ]);
    $account = $atmt->fetch(PDO::FETCH_ASSOC);
       // Update the device
        if (isset($_POST['submit'])) {
        $stmt = $pdo->prepare('INSERT IGNORE INTO support_chat (ticket_id, response, user_id, date, response_read) VALUES (?,?,?,?,?)');
        $stmt->execute([ $_POST['ticket_id'], $_POST['response'], $_POST['user_id'], $_POST['date'], $_POST['response_read'] ]);
        header('Location: support_ticket.php?id='.$_POST['ticket_id']);
        exit;
		 
    }
    if (isset($_POST['close'])) {
        // Close the Ticket
		$closed = '1';
        $stmt = $pdo->prepare('UPDATE support SET status = ? WHERE id = ?');
        $stmt->execute([ $closed, $_GET['id'] ]);
        header('Location: index.php');
        exit;
    }
}
$Page_Name = $lang_Support_Ticket;
include '../template/'.Site_Theme.'/header.php'; ?>
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
				<?php echo '<br>'.$lang_Device_ID_Edit.': '.$support['device_id'].' '.$lang_Support_Ticket_By.' '.$account['username'].'</br>'; echo ''.$lang_Issue.':</br > '.$support['problem'].'</br>';
				// Get the system specs from the database
    $dtmt = $pdo->prepare('SELECT * FROM devices WHERE device_id = '.$support['device_id'].'');
    $dtmt->execute();
    $device = $dtmt->fetch(PDO::FETCH_ASSOC);
				if ($device['wifi'] == '1'){$wifi = 'Yes';} else {$wifi = 'No';}
				if ($device['bluetooth'] == '1'){$bluetooth = 'Yes';} else {$bluetooth = 'No';}
				if ($device['camera'] == '1'){$camera = 'Yes';} else {$camera = 'No';}
				if ($device['simcard'] == '1'){$simcard = 'Yes';} else {$simcard = 'No';}
				echo '<div class="specs"><b>Device Type:</b> '.$device['device_type'].' <b>Department:</b> '.$device['department'].' <b>Mobo:</b> '.$device['motherboard'].' <b>Ram:</b> '.$device['ram'].' <b>processor:</b> '.$device['processor'].' <b>GPU:</b> '.$device['gpu'].' <b>Sound Card:</b> '.$device['sound_card'].' <b>WiFi:</b> '.$wifi.' <b>Operating System:</b> '.$device['os'].' <b>Bluetooth:</b> '.$bluetooth.' <b>Simcard:</b> '.$simcard.' <b>Make:</b> '.$device['make'].' <b>Model:</b> '.$device['model'].' <b>Camera:</b> '.$camera.'</div>';
				
				?>
	
		<?php // Get the account from the database
    			$ctmt = $pdo->prepare('SELECT * FROM support_chat WHERE ticket_id = '.$_GET['id'].'');
				$ctmt->execute();
    			$chat = $ctmt->rowCount();
				
		?>
                <?php if ($chat == 0): ?>
                  <div class="no_reply">There are no responses</div>
                <?php else: 
			  
                 while ($chats = $ctmt->fetch(PDO::FETCH_ASSOC)){ 

			   	$uctmt = $pdo->prepare('SELECT username FROM accounts WHERE id = '.$chats['user_id'].'');
				$uctmt->execute();
				$uctmts = $uctmt->fetch(PDO::FETCH_ASSOC);
    			 ?>
					<div class="reply">
						<div class="reply_inner">
					<div class="username"><?php echo $lang_Username; ?> <?=$uctmts['username']?></div>
					<div class="user_response"><?=$chats['response']?></div>
							</div>
					</div>
                <?php } ?>
                <?php endif; ?>
				
				<form action="" method="post" class="form responsive-width-100">
        <input type="hidden" id="user_id" name="user_id" value="<?=$_SESSION['id']?>" required>
		<input type="hidden" id="ticket_id" name="ticket_id" value="<?php echo $_GET['id']; ?>" required>
		<input type="hidden" id="date" name="date" value="<?php echo date("Y-m-d H:i:s"); ?>" required>
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
<?php include '../template/'.Site_Theme.'/footer.php'; ?>
