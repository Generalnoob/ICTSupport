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
$stmt = $con->prepare('SELECT id, device_id, user_id, status, problem, date FROM support WHERE user_id = '.$_SESSION['id'].' ORDER BY date DESC LIMIT '.$start_froms.', '.$limit.'');
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($id, $device_id, $user_id, $status, $problem, $date);
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width,minimum-scale=1">
		<title><?php echo $lang_Support_Page; ?></title>
		<link href="../style.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body class="loggedin">
		<nav class="navtop">
			<div>
				<h1><?php echo $lang_Support; ?></h1>
				<ul class="nav-top">
				<li><a href="../home.php"><i class="fas fa-home"></i><?php echo $lang_Home; ?></a></li>
				<li><a href="../profile.php"><i class="fas fa-user-circle"></i><?php echo $lang_Profile; ?></a></li>
				<li><?php if ($_SESSION['role'] == 'Admin'): ?>
				<a href="../admin/index.php" target="_blank"><i class="fas fa-user-cog"></i><?php echo $lang_Admin; ?></a>
				<?php endif; ?></li>
				<li><a href="index.php"><i class="fas fa-desktop"></i><?php echo $lang_Support; ?></a></li>
				<li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i><?php echo $lang_Logout; ?></a></li>
					</ul>
			</div>
		</nav>
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
                <tr>
                    <td>#</td>
                    <td><i class="fas fa-address-card"></i> <?php echo $lang_Device_ID; ?></td>
					<td><i class="fas fa-user-cog"></i> <?php echo $lang_Issue; ?></td>
                    <td><i class="fas fa-battery-half"></i> <?php echo $lang_Status; ?></td>
					<td><i class="fas fa-calendar-week"></i> <?php echo $lang_Date; ?></td>
					<td><i class="fas fa-comments"></i> <?php echo $lang_Response; ?></td>
                </tr>
            </thead>
            <tbody>
                <?php if ($stmt->num_rows == 0): ?>
                <tr>
                    <td colspan="4" style="text-align:center;"><?php echo $lang_No_Tickets; ?></td>
                </tr>
                <?php else: ?>
                <?php while ($stmt->fetch()): ?>
                <tr class="details" onclick="location.href='support_ticket.php?id=<?=$id?>'">
					<td></td>
					<td><?=$device_id?></td>
                    <td class="problem"><?php echo substr($problem,0,70); ?>... </td>
                    <td><?php if ($status == 1){echo $lang_Closed;}else{echo $lang_Open;}; ?></td>
                    <td><?=$date?></td>
					<?php 	
				$response_limit = '1';
				$restmt = $con->prepare('SELECT response_read FROM support_chat WHERE ticket_id = '.$id.' ORDER BY id DESC LIMIT '.$response_limit.'');
				$restmt->execute();
				$restmt->store_result();
				$restmt->bind_result($reesponse_read);?>
					<?php while ($restmt->fetch()): ?>
					<td><?php if ($reesponse_read == '2'){echo $lang_Admin_Responded;} else {if ($reesponse_read == '1'){echo $lang_You_Responded;} else {echo 'No Response';}}?></td>
					<?php endwhile; ?>
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
$pagLinks = '<div class="pagination"> Pages: ';
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
				$astmt = $con->prepare('SELECT id, device_id, user_id, status, problem, date FROM support WHERE status = '.$astatus.' ORDER BY date ASC LIMIT '.$start_froma.', '.$limit.'');
				$astmt->execute();
				$astmt->store_result();
				$astmt->bind_result($aid, $adevice_id, $auser_id, $astatus, $aproblem, $adate);?>
				<table>
            <thead>
                <tr>
                    <td>#</td>
                    <td><i class="fas fa-address-card"></i> <?php echo $lang_Device_ID; ?></td>
					<td><i class="fas fa-user-cog"></i> <?php echo $lang_Issue; ?></td>
                    <td><i class="fas fa-battery-half"></i> <?php echo $lang_Status; ?></td>
					<td><i class="fas fa-calendar-week"></i> <?php echo $lang_Date; ?></td>
					<td><i class="fas fa-comments"></i> <?php echo $lang_Response; ?></td>
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
                    <td class="problem"><?php echo substr($aproblem,0,70); ?>... </td>
                    <td><?php if ($astatus == 1){echo $lang_Closed;}else{echo $lang_Open;}; ?></td>
                    <td><?=$adate?></td>
					<?php 	
				$response_limit = '1';
				$rstmt = $con->prepare('SELECT response_read FROM support_chat WHERE ticket_id = '.$aid.' ORDER BY id DESC LIMIT '.$response_limit.'');
				$rstmt->execute();
				$rstmt->store_result();
				$rstmt->bind_result($response_read);?>
					<?php while ($rstmt->fetch()): ?>
					<td><?php if ($response_read == '2'){echo 'Admin Response';} else {if ($response_read == '1'){echo 'User Response';} else {echo 'No Response';}}?></td>
					<?php endwhile; ?>
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
				$pagLink = '<div class="pagination"> Pages: ';
				for ($i=1; $i<=$total_pages; $i++) 
				{  
    			$pagLink .= '<a href="index.php?pa='.$i.'">'.$i.'</a> ';  
				}  
				echo $pagLink . '</div>'; ?>
				</p>
			</div><?php endif; ?>
		</div>
		<div class="footer">Created By David Lomas</div>
	</body>
</html>
