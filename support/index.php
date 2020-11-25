<?php
//Call main functions
include '../main.php';
//Call Language File
languages($con);
include '../languages/'.languages($con).'.php';
//check login state
check_loggedin($con);
//Start Pagination
$limit = 10;  
if (isset($_GET["ps"])) { $ps  = $_GET["ps"]; } else { $ps=1; };
if (isset($_GET["pa"])) { $pa  = $_GET["pa"]; } else { $pa=1; };
$start_froms = ($ps-1) * $limit;
$start_froma = ($pa-1) * $limit;
//Call Tickets
$stmt = $con->prepare('SELECT id, device_id, user_id, status, problem, date, title FROM support WHERE user_id = '.$_SESSION['id'].' ORDER BY date DESC LIMIT '.$start_froms.', '.$limit.'');
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($id, $device_id, $user_id, $status, $problem, $date, $title);
?>
<?php 
//Page Name
$Page_Name = $lang_Support_Tickets;
include '../template/'.Site_Theme.'/header.php'; ?>
		<div class="content">
			<h2><?php echo $lang_Support_Areana; ?></h2>
			<div class="buttons_link"><div class="button_links">
   			<a href="add_support.php"><?php echo $lang_Creat_Support_Ticket; ?></a>
		</div>
			 <div class="button_links">
   			<a href="index.php"><?php echo $lang_Support_Tickets; ?></a>
		</div></div>
			<p class="block"><?php echo $lang_Welcome_Back; ?>, <?=$_SESSION['name']?>!</p>
			
			<div class="my_support_tickets">
			<div class="block table">
				<p><?php echo $lang_Welcome_Back; ?></p>
        <table>
            <thead>
                <tr class="head_banners">
                    <td>#</td>
                    <td><div class="response_small"><i class="fas fa-address-card" title="<?php echo $lang_Device_ID; ?>"></i></div> <div class="response_large"><?php echo $lang_Device_ID; ?></div></td>
					<td><div class="response_small"><i class="fas fa-user-cog" title="<?php echo $lang_Issue; ?>"></i> </div><div class="response_large"><?php echo $lang_Issue; ?></div></td>
                    <td><div class="response_small"><i class="fas fa-unlock" title="<?php echo $lang_Status; ?>"></i> </div><div class="response_large"><?php echo $lang_Status; ?></div></td>
					<td><div class="response_small"><i class="fas fa-calendar-week" title="<?php echo $lang_Date; ?>"></i> </div><div class="response_large"><?php echo $lang_Date; ?></div></td>
					<td><div class="response_small"><i class="fas fa-comments" title="<?php echo $lang_Response; ?>"></i></div><div class="response_large"><?php echo $lang_Response; ?></div></td>
                </tr>
            </thead>
            <tbody>
                <?php //Check if there is a ticket
				if ($stmt->num_rows == 0): ?>
                <tr>
                    <td colspan="4" style="text-align:center;"><?php echo $lang_No_Tickets; ?></td>
                </tr>
                <?php else: ?>
                <?php // list the tickets
				while ($stmt->fetch()): ?>
                <tr class="details" onclick="location.href='support_ticket.php?id=<?=$id?>'">
					<td></td>
					<td><?=$device_id?></td>
                    <td class="problem"><?php echo substr($title, 0, 50); echo strlen($title) > 50 ? '...' : ''; ?></td>
                    <td><?php if ($status == 1){echo '<div class="response_small"><i class="red fas fa-lock"></i></div><div class="response_large">'.$lang_Closed.'</div>';}else{echo '<div class="response_small"><i class="green fas fa-unlock"></i></div><div class="response_large">'.$lang_Open.'</div>';}; ?></td>
                    <td><?php echo time_elapsed_string($date, true); ?></td>
					<?php //Check for responses and display who responded	
				$response_limit = '1';
				$restmt = $con->prepare('SELECT response_read FROM support_chat WHERE ticket_id = '.$id.' ORDER BY id DESC LIMIT '.$response_limit.'');
				$restmt->execute();
				$restmt->store_result();
				$restmt->bind_result($reesponse_read);
				
				$restmt1 = $con->prepare('SELECT response_read FROM support_chat WHERE ticket_id = '.$id.' ORDER BY id DESC LIMIT '.$response_limit.'');
				$restmt1->execute();
				$restmt1->store_result();
				$restmt1->bind_result($reesponse_read1);
					
					if ($restmt1->fetch() <= 0){ ?>
						 <td><div class="response_small"><i class="response_grey fas fa-comments" title="'.$lang_No_Response.'"></i></div><div class="response_large"> <?php echo $lang_No_Response;?></div></td>
					
					<?php } else { while ($restmt->fetch()){ ?>
					<td><div class="response_small">
						<?php if ($reesponse_read == '2'){echo '<i class="response_red fas fa-comments" title="'.$lang_Admin_Responded.'"></i>';} else {if ($reesponse_read == '1'){echo '<i class="response_green fas fa-comments" title="'.$lang_User_Responded.'"></i>';} else {echo '<i class="response_grey fas fa-comments" title="No Response"></i>';}}?></div><div class="response_large"><?php if ($reesponse_read == '2'){echo $lang_Admin_Responded;} else {if ($reesponse_read == '1'){echo $lang_User_Responded;}}?></div>
					</td>
					<?php } }?>
					
				</tr>
                <?php endwhile;?>
                <?php endif;?>
            </tbody>
					</table>
				<?php $ptmt = $con->prepare('SELECT id FROM support WHERE user_id = '.$_SESSION['id'].'');
						$ptmt->execute();
						$ptmt->store_result();
						$ptmt->bind_result($id); 
				$total_records = $ptmt->num_rows;
$total_pages = ceil($total_records / $limit); 
$pagLinks = '<div class="pagination"> '.$lang_Pages.': ';
for ($i=1; $i<=$total_pages; $i++) 
{  
    $pagLinks .= '<a href="index.php?ps='.$i.'">'.$i.'</a> ';  
}  
echo $pagLinks . '</div>'; ?>
			</div></div>
			<?php if ($_SESSION['role'] == 'Member'): ?> 
			<?php else:?>
			<div class="block">
			<p><?php echo $lang_Support_Tickets; ?>
		<?php 	$astatus = '2';
				$astmt = $con->prepare('SELECT id, device_id, user_id, status, problem, date, title FROM support WHERE status = '.$astatus.' ORDER BY date ASC LIMIT '.$start_froma.', '.$limit.'');
				$astmt->execute();
				$astmt->store_result();
				$astmt->bind_result($aid, $adevice_id, $auser_id, $astatus, $aproblem, $adate, $atitle);?>
				<table>
            <thead>
                <tr class="head_banners">
                    <td>#</td>
					<td><div class="response_small"><i class="fas fa-address-card" title="<?php echo $lang_Device_ID; ?>"></i></div> <div class="response_large"><?php echo $lang_Device_ID; ?></div></td>
					<td><div class="response_small"><i class="fas fa-user-cog" title="<?php echo $lang_Issue; ?>"></i> </div><div class="response_large"><?php echo $lang_Issue; ?></div></td>
                    <td><div class="response_small"><i class="fas fa-unlock" title="<?php echo $lang_Status; ?>"></i> </div><div class="response_large"><?php echo $lang_Status; ?></div></td>
					<td><div class="response_small"><i class="fas fa-calendar-week" title="<?php echo $lang_Date; ?>"></i> </div><div class="response_large"><?php echo $lang_Date; ?></div></td>
					<td><div class="response_small"><i class="fas fa-comments" title="<?php echo $lang_Response; ?>"></i></div><div class="response_large"><?php echo $lang_Response; ?></div></td>
                </tr>
            </thead>
            <tbody>
                <?php if ($astmt->num_rows == 0): ?>
                <tr>
                    <td colspan="4" style="text-align:center;"><?php echo $lang_No_Tickets; ?></td>
                </tr>
                <?php else: ?>
				<?php while ($astmt->fetch()): ?>
                <tr class="details" onclick="location.href='support_ticket.php?id=<?=$aid?>'">
					<td></td>
					<td><?=$adevice_id?></td>
                    <td class="problem"><?php echo substr($atitle, 0, 50); echo strlen($atitle) > 50 ? '...' : ''; ?></td>
                    <td><?php if ($astatus == 1){echo '<div class="response_small"><i class="red fas fa-lock"></i></div><div class="response_large">'.$lang_Closed.'</div>';}else{echo '<div class="response_small"><i class="green fas fa-unlock"></i></div><div class="response_large">'.$lang_Open.'</div>';}; ?></td>
                    <td><?php echo time_elapsed_string($adate, true); ?></td>
					<?php 	
				$response_limit = '1';
				$rstmt = $con->prepare('SELECT response_read FROM support_chat WHERE ticket_id = '.$aid.' ORDER BY id DESC LIMIT '.$response_limit.'');
				$rstmt->execute();
				$rstmt->store_result();
				$rstmt->bind_result($response_read);
				$restmt2 = $con->prepare('SELECT response_read FROM support_chat WHERE ticket_id = '.$aid.' ORDER BY id DESC LIMIT '.$response_limit.'');
				$restmt2->execute();
				$restmt2->store_result();
				$restmt2->bind_result($reesponse_read2);
					
					if ($restmt2->fetch() <= 0){ ?>
						 <td><div class="response_small"><i class="response_grey fas fa-comments" title="'.$lang_No_Response.'"></i></div><div class="response_large"> <?php echo $lang_No_Response;?></div></td>
					
					<?php } else { while ($rstmt->fetch()){ ?>
					<td><div class="response_small">
						<?php if ($reesponse_read == '2'){echo '<i class="response_red fas fa-comments" title="'.$lang_Admin_Responded.'"></i>';} else {if ($reesponse_read == '1'){echo '<i class="response_green fas fa-comments" title="'.$lang_User_Responded.'"></i>';} else {echo '<i class="response_grey fas fa-comments" title="No Response"></i>';}}?></div><div class="response_large"><?php if ($reesponse_read == '2'){echo $lang_Admin_Responded;} else {if ($reesponse_read == '1'){echo $lang_User_Responded;}}?></div>
					</td>
					<?php } }?>
				</tr>
                <?php endwhile; ?>
					
				<?php endif; 
				echo '</tbody></table>';
				$atmt = $con->prepare('SELECT id FROM support');
				$atmt->execute();
				$atmt->store_result();
				$atmt->bind_result($id);
				$total_recordss = $atmt->num_rows;
				$total_pages = ceil($total_records / $limit); 
				$pagLink = '<div class="pagination"> '.$lang_Pages.': ';
				for ($i=1; $i<=$total_pages; $i++) 
				{  
    			$pagLink .= '<a href="index.php?pa='.$i.'">'.$i.'</a> ';  
				}  
				echo $pagLink . '</div>'; ?>
				</p>
			</div><?php endif; ?>
		</div>
	<?php include '../template/'.Site_Theme.'/footer.php'; ?>
