<?php
//Call main functions
include '../main.php';
//Call Language File
languages($pdo);
include '../languages/'.languages($pdo).'.php';
//check login state
check_loggedin($pdo);
if ($_SESSION['role'] == 'ICT Support' OR 'Admin' OR 'Member'){
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
			</div>
			<p class="block_support"><?php echo $lang_Your_Support_Tickets; ?>, <?=$_SESSION['name']?>!</p>
			
			<div class="my_support_tickets">
			<div class="table">
       <div class="container">
			<?php //Check if there is a ticket
				if (!$usertickets): ?>
                   <div style="text-align:center;"><?php echo $lang_No_Tickets; ?></div>
                <?php else: ?>
                <?php // list the tickets
				foreach ($user_tickets as $user_ticket): ?>
		   		<?php	//Check if ticket is closed
						if ($user_ticket['status'] == 1) { ?>
                <div class="details grey" onclick="location.href='support_ticket.php?id=<?=$user_ticket['id']?>'">
					<?php } else { ?>
				<div class="details" onclick="location.href='support_ticket.php?id=<?=$user_ticket['id']?>'">	
					<?php } ?>
					<div class="device_type">
				<?php 	// get device type and display icon
						$dtype = $pdo->prepare('SELECT device_type FROM devices WHERE device_id = '.$user_ticket['device_id'].'');
						$dtype->execute();
						$dtypes = $dtype->fetchAll(PDO::FETCH_ASSOC);
						
						foreach ($dtypes as $type){
						if ($type['device_type'] == ''){echo '<i class="fas fa-question"></i>';}
						if ($type['device_type'] == 'Tower PC'){echo '<i class="fas fa-desktop"></i>';}
						if ($type['device_type'] == 'Mini PC'){echo '<i class="fas fa-desktop"></i>';}
						if ($type['device_type'] == 'Laptop'){echo '<i class="fas fa-laptop"></i>';}
						if ($type['device_type'] == 'Tablet'){echo '<i class="fas fa-tablet-alt"></i>';}
						if ($type['device_type'] == 'Phone'){echo '<i class="fas fa-mobile-alt"></i>';}} ?>
					</div>
					<div class="ticket_details">
                    <div class="title"><?php echo substr($user_ticket['title'], 0, 20); echo strlen($user_ticket['title']) > 20 ? '...' : ''; ?></div>
					<div class="problem"><?php echo substr($user_ticket['problem'], 0, 50); echo strlen($user_ticket['problem']) > 50 ? '...' : ''; ?></div>
                    
					<?php //Check for responses and display who responded
					$response_limit = '1';
					$restmt = $pdo->prepare('SELECT response_read, date FROM support_chat WHERE ticket_id = '.$user_ticket['id'].' ORDER BY id DESC LIMIT '.$response_limit.'');
					$restmt->execute();
					$restmts = $restmt->fetchAll(PDO::FETCH_ASSOC);	
					$astmt = $pdo->prepare('SELECT response_read, date FROM support_chat WHERE ticket_id = '.$user_ticket['id'].' ORDER BY id DESC LIMIT '.$response_limit.'');
						$astmt->execute();
					if ($astmt->rowCount() <= 0){ ?>
						 <div class="response_user"><i class="response_grey fas fa-comments" title="'.$lang_No_Response.'"></i> <?php echo $lang_No_Response;?><div class="activity">Last Activity: None</div></div>
					<?php } else { 
						foreach ($restmts as $response){ ?>
					
					<div class="response_user">
						<?php if ($response['response_read'] == '2'){echo '<i class="response_red fas fa-comments" title="'.$lang_Admin_Responded.'"></i> ';} else {if ($response['response_read'] == '1'){echo '<i class="response_green fas fa-comments" title="'.$lang_User_Responded.'"></i> ';} else {echo '<i class="response_grey fas fa-comments" title="No Response"></i> ';}} if ($response['response_read'] == '2'){echo $lang_Admin_Responded;} else {if ($response['response_read'] == '1'){echo $lang_User_Responded;}}?><div class="activity">
							Last Activity: <?php $date1 = date_create($response['date']); echo date_format($date1,"H:i d/m/Y"); ?>
						</div></div>
					<?php } }?>
						<div class="response_all">
						<div class="response"><?php if ($user_ticket['status'] == 1){echo '<div class="response_small"><i class="fas fa-circle red"></i></div><div class="response_large">'.$lang_Closed.'</div>';}else{echo '<div class="response_small"><i class="fas fa-circle green"></i></div><div class="response_large">'.$lang_Open.'</div>';}; ?></div>
                    <div class="response"><i class="fas fa-calendar-week" title="<?php echo $lang_Date; ?>"></i> <?php $date2 = date_create($user_ticket['date']); echo date_format($date2,"H:i d/m/Y"); ?></div>
						<div class="device_id"><i class="fas fa-address-card" title="<?php echo $lang_Device_ID; ?>"></i> <?=$user_ticket['device_id']?></div></div>
					
					</div></div>
                <?php endforeach;?>
                <?php endif;?>
		   </div>
				<?php 	
		   				$ptmt = $pdo->prepare('SELECT id FROM support WHERE user_id = '.$_SESSION['id'].'');
						$ptmt->execute();
		   				$total_records = $ptmt->rowCount();
		   
$total_pages = ceil($total_records / $limit); 
$pagLinks = '<div class="pagination"> '.$lang_Pages.': ';
for ($i=1; $i<=$total_pages; $i++) 
{  
    $pagLinks .= '<a href="index.php?ps='.$i.'">'.$i.'</a> ';  
}  
echo $pagLinks . '</div>'; ?>
				</div></div>
		</div>
				</div>
			</div>
	<?php include '../template/'.Site_Theme.'/footer.php'; } ?>

