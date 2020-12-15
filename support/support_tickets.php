<?php
//Call main functions
include '../main.php';
//Call Language File
languages($pdo);
include '../languages/'.languages($pdo).'.php';
//check login state
check_loggedin($pdo);
//Start Pagination
$limit = 9;  
if (isset($_GET["ps"])) { $ps  = $_GET["ps"]; } else { $ps=1; };
if (isset($_GET["pa"])) { $pa  = $_GET["pa"]; } else { $pa=1; };
$start_froms = ($ps-1) * $limit;
$start_froma = ($pa-1) * $limit;
//Call Tickets
	$usertickets = $pdo->prepare('SELECT * FROM support WHERE user_id = '.$_SESSION['id'].' ORDER BY date DESC LIMIT '.$start_froms.', '.$limit.'');
	$usertickets->execute();
	$user_tickets = $usertickets->fetchAll(PDO::FETCH_ASSOC);
?>
<?php 
//Page Name
$Page_Name = $lang_Support_Tickets;
include '../template/'.Site_Theme.'/header.php'; ?>
		<div class="content">
			<br />
			<div class="buttons_link"><div class="button_links">
   			<a href="add_support.php"><?php echo $lang_Creat_Support_Ticket; ?></a>
		</div>
			 <div class="button_links">
   			<a href="index.php"><?php echo $lang_Support_Tickets; ?></a>
		</div>
				<?php if ($_SESSION['role'] == 'ICT Support' OR 'Admin'){ ?>
			<div class="button_links">
   			<a href="support_tickets.php"><?php echo $lang_All_Support_Tickets; ?></a>
			</div><?php } ?>
				
		   </div><?php if ($_SESSION['role'] == 'ICT Support' OR 'Admin'){ ?>
			<div class="my_support_tickets">
				<div class="block_support"><?php echo $lang_Support_Tickets; ?></div>
		<?php 	$astatus = '2';
				$astmt = $pdo->prepare('SELECT id, device_id, user_id, status, problem, date, title FROM support WHERE status = '.$astatus.' ORDER BY date ASC LIMIT '.$start_froma.', '.$limit.'');
				$astmt->execute();
				$astmts = $astmt->fetchAll(PDO::FETCH_ASSOC); ?>
				<div class="table">
       <div class="container">
                <?php if (!$astmts): ?>
                <div style="text-align:center;"><?php echo $lang_No_Tickets; ?></div>
                <?php else: ?>
				<?php foreach ($astmts as $ticket){ ?>
                <div class="details" onclick="location.href='support_ticket.php?id=<?=$ticket['id']?>'">
					<div class="device_type">
				<?php 	$device_types = $pdo->prepare('SELECT device_type FROM devices WHERE device_id = '.$ticket['device_id'].'');
						$device_types->execute();
						$device_type = $device_types->fetchAll(PDO::FETCH_ASSOC);
	
						foreach ($device_type as $device){
						if ($device['device_type'] == ''){echo '<i class="fas fa-question"></i>';}
						if ($device['device_type'] == 'Tower PC'){echo '<i class="fas fa-desktop"></i>';}
						if ($device['device_type'] == 'Mini PC'){echo '<i class="fas fa-desktop"></i>';}
						if ($device['device_type'] == 'Laptop'){echo '<i class="fas fa-laptop"></i>';}
						if ($device['device_type'] == 'Tablet'){echo '<i class="fas fa-tablet-alt"></i>';}
						if ($device['device_type'] == 'Phone'){echo '<i class="fas fa-mobile-alt"></i>';}} ?>
					</div>
					<div class="device_id"><i class="fas fa-address-card" title="<?php echo $lang_Device_ID; ?>"></i> <?=$ticket['device_id']?></div>
                    <div class="title"><?php echo substr($ticket['title'], 0, 20); echo strlen($ticket['title']) > 20 ? '...' : ''; ?></div>
					<div class="problem"><?php echo substr($ticket['problem'], 0, 50); echo strlen($ticket['problem']) > 50 ? '...' : ''; ?></div>
                    
				<?php 	
						$response_limit = '1';
						$rstmt = $pdo->prepare('SELECT response_read, date FROM support_chat WHERE ticket_id = '.$ticket['id'].' ORDER BY id DESC LIMIT '.$response_limit.'');
						$rstmt->execute();
					
						$rstmt1 = $pdo->prepare('SELECT response_read, date FROM support_chat WHERE ticket_id = '.$ticket['id'].' ORDER BY id DESC LIMIT '.$response_limit.'');
						$rstmt1->execute();
						$rstmts1 = $rstmt1->fetchAll(PDO::FETCH_ASSOC); 
					
					if ($rstmt->rowCount() <= 0){ ?>
						 <div class="response_user"><div class="response_small"><i class="response_grey fas fa-comments" title="'.$lang_No_Response.'"></i></div><div class="response_large"> <?php echo $lang_No_Response;?></div></div>
					
					<?php } else { foreach ($rstmts1 as $response_read){ ?>
					<div class="response_user">
						<?php if ($response_read['response_read'] == '2'){echo '<i class="response_red fas fa-comments" title="'.$lang_Admin_Responded.'"></i> ';} else {if ($response_read['response_read'] == '1'){echo '<i class="response_green fas fa-comments" title="'.$lang_User_Responded.'"></i> ';} else {echo '<i class="response_grey fas fa-comments" title="No Response"></i> ';}}?><?php if ($response_read['response_read'] == '2'){echo $lang_Admin_Responded;} else {if ($response_read['response_read'] == '1'){echo $lang_User_Responded;}}?><br>
							Last Activity: <?php $date3 = date_create($response_read['date']); echo date_format($date3,"H:i d/m/Y"); ?>
						</div>
					<?php } }?>
					<div class="response"><?php if ($ticket['status'] == 1){echo '<div class="response_small"><i class="red fas fa-lock"></i></div><div class="response_large">'.$lang_Closed.'</div>';} else {echo '<div class="response_small"><i class="fas fa-unlock"></i></div><div class="response_large">'.$lang_Open.'</div>';}; ?></div>
					<div class="response"><i class="fas fa-calendar-week" title="<?php echo $lang_Date; ?>"></i> <?php echo time_elapsed_string($ticket['date'], true); ?></div>
		   </div>
                <?php } ?>
					
				<?php endif; 
				echo '</div>';
		   		$atmt = $pdo->prepare('SELECT id FROM support');
				$atmt->execute();
		   		$total_recordss = $atmt->rowCount();
				$total_pages = ceil($total_recordss / $limit); 
				$pagLink = '<div class="pagination"> '.$lang_Pages.': ';
				for ($i=1; $i<=$total_pages; $i++) 
				{  
    			$pagLink .= '<a href="index.php?pa='.$i.'">'.$i.'</a> ';  
				}  
				echo $pagLink . '</div>'; ?>
				</p>
			</div><?php }else{ echo 'No access'; } ?>
		</div>
				</div>
			</div>
	<?php include '../template/'.Site_Theme.'/footer.php'; ?>
