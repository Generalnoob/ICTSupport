<?php
include 'main.php';
$limit = 20;  
if (isset($_GET["ps"])) { $ps  = $_GET["ps"]; } else { $ps=1; };
$start_froms = ($ps-1) * $limit;
// query to get all Devices from the database
$stmt = $pdo->prepare('SELECT * FROM devices LIMIT '.$start_froms.', '.$limit.'');
$stmt->execute();
include 'header.php';
?>

<h2>Devices</h2>

<div class="links">
    <a href="add_device.php">Create Device</a>
</div>

<div class="content-block-device">
         <?php if ($stmt->rowCount() == 0): ?>
               
                    <div style="text-align:center;">There are no devices</div>
                
                <?php else: ?>
                <?php while ($device = $stmt->fetch(PDO::FETCH_ASSOC)){ ?>
	<div class="content-block-short">
               <div class="device-tab">
				<div class="device-type"><a href="add_device.php?id=<?=$device['id']?>"><?php 
	if ($device['device_type'] == 'Tower PC'){echo '<i class="fas fa-desktop"></i>';}
	if ($device['device_type'] == 'Mini PC'){echo '<i class="fas fa-desktop"></i>';}
	if ($device['device_type'] == 'Laptop'){echo '<i class="fas fa-laptop"></i>';}
	if ($device['device_type'] == 'Tablet'){echo '<i class="fas fa-tablet-alt"></i>';}
	if ($device['device_type'] == 'Phone'){echo '<i class="fas fa-mobile-alt" align="center"></i>';}
					?></div>
					<div><?php 
	if ($device['os'] == 'Windows 10'){echo '<i class="fab fa-windows"></i>';}
	if ($device['os'] == 'Windows 8'){echo '<i class="fab fa-windows"></i>';}
	if ($device['os'] == 'Windows 7'){echo '<i class="fab fa-windows"></i>';}
	if ($device['os'] == 'Windows Vista'){echo '<i class="fab fa-windows"></i>';}
	if ($device['os'] == 'Windows XP'){echo '<i class="fab fa-windows"></i>';}
	if ($device['os'] == 'Linux'){echo '<i class="fab fa-linux"></i>';}
	if ($device['os'] == 'Suse'){echo '<i class="fas fa-suse"></i>';}
	if ($device['os'] == 'Redhat'){echo '<i class="fas fa-redhat"></i>';}
	if ($device['os'] == 'Fedora'){echo '<i class="fas fa-fedora"></i>';}
	if ($device['os'] == 'Centos'){echo '<i class="fas fa-centos"></i>';}
	if ($device['os'] == 'Ubuntu'){echo '<i class="fas fa-ubuntu"></i>';}
	if ($device['os'] == 'Chrome'){echo '<i class="fas fa-chrome"></i>';}
	if ($device['os'] == 'Mac OS'){echo '<i class="fas fa-apple"></i>';}
	if ($device['os'] == 'Apple iOS'){echo '<i class="fas fa-apple"></i>';}
	if ($device['os'] == 'Android'){echo '<i class="fa fa-android"></i>';}
						?>
						<span> <?=$device['os']?></span></div>
					<div><i class="fas fa-id-card"></i> <?=$device['device_id']?></div>
                    <div class="device-extra"><?php if ($device['wifi'] == 1){echo '<i class="fas fa-wifi"></i>';} else { echo '<i class="grey fas fa-wifi"></i>';}?></div>
                    <div class="device-extra"><?php if ($device['bluetooth'] == 1){echo '<i class="fab fa-bluetooth"></i>';} else { echo '<i class="grey fab fa-bluetooth"></i>';}?></div>
					<div class="device-extra"><?php if ($device['camera'] == 1){echo '<i class="fas fa-camera"></i>';} else { echo '<i class="grey fas fa-camera"></i>';}?></div>
					<div class="device-extra"><?php if ($device['simcard'] == 1){echo '<i class="fas fa-sim-card"></i>';} else { echo '<i class="grey fas fa-sim-card"></i>';}?></div>
					<div class="responsive-hidden"><i class="fas fa-ellipsis-v"></i> <?=$device['make']?></div>
                    <div class="responsive-hidden"><i class="fas fa-ellipsis-v"></i> <?=$device['model']?></div>
                    <div><i class="fas fa-building"></i> <?=$device['department']?></div>
				
					</a></div> </div>

		
                <?php } ?>
                <?php endif; ?>
</div>
<div class="content-block">
         <?php $atmt = $pdo->prepare('SELECT id FROM devices');
				$atmt->execute();
		   		$total_records = $atmt->rowCount();
$total_pages = ceil($total_records / $limit); 
$pagLinks = '<div class="pagination"> '.$lang_Pages.': ';
for ($i=1; $i<=$total_pages; $i++) 
{  
    $pagLinks .= '<a href="devices.php?ps='.$i.'">'.$i.'</a> ';  
}  
echo $pagLinks . '</div>'; ?>
			</div>
    
<?=template_admin_footer()?>
