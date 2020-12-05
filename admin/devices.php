<?php
include 'main.php';
$limit = 20;  
if (isset($_GET["ps"])) { $ps  = $_GET["ps"]; } else { $ps=1; };
$start_froms = ($ps-1) * $limit;
// query to get all Devices from the database
$stmt = $con->prepare('SELECT id, device_type, department, device_id, motherboard, ram, processor, gpu, sound_card, wifi, bluetooth, simcard, make, model, camera, os FROM devices LIMIT '.$start_froms.', '.$limit.'');
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($id, $device_type, $department, $device_id, $motherboard, $ram, $processor, $gpu, $sound_card, $wifi, $bluetooth, $simcard, $make, $model, $camera, $device_os);
include 'header.php';
?>

<h2>Devices</h2>

<div class="links">
    <a href="add_device.php">Create Device</a>
</div>

<div class="content-block-device">
         <?php if ($stmt->num_rows == 0): ?>
               
                    <div style="text-align:center;">There are no accounts</div>
                
                <?php else: ?>
                <?php while ($stmt->fetch()): ?>
	<div class="content-block-short">
               <div class="device-tab">
				<div class="device-type"><a href="add_device.php?id=<?=$id?>"><?php 
	if ($device_type == 'Tower PC'){echo '<i class="fas fa-desktop"></i>';}
	if ($device_type == 'Mini PC'){echo '<i class="fas fa-desktop"></i>';}
	if ($device_type == 'Laptop'){echo '<i class="fas fa-laptop"></i>';}
	if ($device_type == 'Tablet'){echo '<i class="fas fa-tablet-alt"></i>';}
	if ($device_type == 'Phone'){echo '<i class="fas fa-mobile-alt" align="center"></i>';}
					?></div>
					<div><?php 
	if ($device_os == 'Windows 10'){echo '<i class="fab fa-windows"></i>';}
	if ($device_os == 'Windows 8'){echo '<i class="fab fa-windows"></i>';}
	if ($device_os == 'Windows 7'){echo '<i class="fab fa-windows"></i>';}
	if ($device_os == 'Windows Vista'){echo '<i class="fab fa-windows"></i>';}
	if ($device_os == 'Windows XP'){echo '<i class="fab fa-windows"></i>';}
	if ($device_os == 'Linux'){echo '<i class="fab fa-linux"></i>';}
	if ($device_os == 'Suse'){echo '<i class="fas fa-suse"></i>';}
	if ($device_os == 'Redhat'){echo '<i class="fas fa-redhat"></i>';}
	if ($device_os == 'Fedora'){echo '<i class="fas fa-fedora"></i>';}
	if ($device_os == 'Centos'){echo '<i class="fas fa-centos"></i>';}
	if ($device_os == 'Ubuntu'){echo '<i class="fas fa-ubuntu"></i>';}
	if ($device_os == 'Chrome'){echo '<i class="fas fa-chrome"></i>';}
	if ($device_os == 'Mac OS'){echo '<i class="fas fa-apple"></i>';}
	if ($device_os == 'Apple iOS'){echo '<i class="fas fa-apple"></i>';}
	if ($device_os == 'Android'){echo '<i class="fa fa-android"></i>';}
						?>
						<span> <?=$device_os?></span></div>
					<div><i class="fas fa-id-card"></i> <?=$device_id?></div>
                    <div class="device-extra"><?php if ($wifi == 1){echo '<i class="fas fa-wifi"></i>';} else { echo '<i class="grey fas fa-wifi"></i>';}?></div>
                    <div class="device-extra"><?php if ($bluetooth == 1){echo '<i class="fab fa-bluetooth"></i>';} else { echo '<i class="grey fab fa-bluetooth"></i>';}?></div>
					<div class="device-extra"><?php if ($camera == 1){echo '<i class="fas fa-camera"></i>';} else { echo '<i class="grey fas fa-camera"></i>';}?></div>
					<div class="device-extra"><?php if ($simcard == 1){echo '<i class="fas fa-sim-card"></i>';} else { echo '<i class="grey fas fa-sim-card"></i>';}?></div>
					<div class="responsive-hidden"><i class="fas fa-ellipsis-v"></i> <?=$make?></div>
                    <div class="responsive-hidden"><i class="fas fa-ellipsis-v"></i> <?=$model?></div>
                    <div><i class="fas fa-building"></i> <?=$department?></div>
				
					</a></div> </div>

		
                <?php endwhile; ?>
                <?php endif; ?>
</div>
<div class="content-block">
         <?php $ptmt = $con->prepare('SELECT id FROM devices');
						$ptmt->execute();
						$ptmt->store_result();
						$ptmt->bind_result($id); 
				$total_records = $ptmt->num_rows;
$total_pages = ceil($total_records / $limit); 
$pagLinks = '<div class="pagination"> '.$lang_Pages.': ';
for ($i=1; $i<=$total_pages; $i++) 
{  
    $pagLinks .= '<a href="devices.php?ps='.$i.'">'.$i.'</a> ';  
}  
echo $pagLinks . '</div>'; ?>
			</div>
    
<?=template_admin_footer()?>
