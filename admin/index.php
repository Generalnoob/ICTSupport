<?php
include 'main.php';
include 'header.php';
if ($otmt->rowCount() == '0'){$otmt = '0';}else{$otmt = $otmt->rowCount();}
if ($wtmt->rowCount() == '0'){$wtmt = '0';}else{$wtmt = $wtmt->rowCount();}
?>

<h2>Home</h2>
<div class="maincont">
	<div class="content-block">
	<div class="stats_all"><div class="stats_details"><i class="fas fa-tags"></i> <span class="details_stats">Open Tickets<br /><?php echo $statsall->rowCount();?></span></div></div>
	<div class="stats_low"><div class="stats_details"><i class="fas fa-tags"></i> <span class="details_stats">Low Priority<br /><?php echo $statslow->rowCount();?></span></div></div>
	<div class="stats_med"><div class="stats_details"><i class="fas fa-tags"></i> <span class="details_stats">Medium Priority<br /><?php echo $statsmed->rowCount();?></span></div></div>
	<div class="stats_high"><div class="stats_details"><i class="fas fa-tags"></i> <span class="details_stats">High Priority<br /><?php echo $statshigh->rowCount();?></span></div></div>
	</div>
<div class="content-block-short">
	<script type="text/javascript">
      google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Task', 'This Weeks Tickets'],
          ['Open Tickets',     <?php echo $otmt;?>],
          ['Closed Tickets',   <?php echo $wtmt;?>],
        ]);

        var options = {
          title: 'This Weeks Tickets',
          pieSliceText:'value',
          pieHole: 0.4,
		  sliceVisibilityThreshold:0
        };
		  
        var chart = new google.visualization.PieChart(document.getElementById('donutchart1'));
        chart.draw(data, options,);
      }
    </script>
<div id="donutchart1" style="width: 500px; height: 500px;"></div>
	
	<div class="donut_sub"><div class="donut_stats">Open Tickets<br /><?php echo $otmt;?></div><div class="donut_stats">Closed Tickets<br /><?php echo $wtmt;?></div></div>
	</div><div class="content-block-short">
	<script type="text/javascript">
      google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Task', 'This Months Tickets'],
          ['Open Tickets',     <?php echo $o1tmt->rowCount();?>],
          ['Closed Tickets',   <?php echo $ctmt->rowCount();?>],
        ]);

        var options = {
          title: 'This Months Tickets',
          pieSliceText:'value',
          pieHole: 0.4,
		  sliceVisibilityThreshold:0
        };
		 if(data.getNumberOfRows() == 0){
    $("#donutchart2").append("Sorry, not info available")
}else{
    var chart = new google.visualization.PieChart(document.getElementById('donutchart2'));
    chart.draw(data, options);        
} 
        
      }
    </script>
<div id="donutchart2" style="width: 500px; height: 500px;"></div>
	<div class="donut_sub"><div class="donut_stats">Open Tickets<br /><?php echo $o1tmt->rowCount();?></div><div class="donut_stats">Closed Tickets<br /><?php echo $ctmt->rowCount();?></div></div>
</div><div class="content-block-short">
	<div id="chart_div" style="width: 500px; height: 500px;"></div>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
    google.load("visualization", "1", {packages:["corechart"]});
    google.setOnLoadCallback(drawChart);
    function drawChart() {
        var data = google.visualization.arrayToDataTable([
        ['Month',	'Tickets'],
        <?php while ($ticket = $ytmt->fetch(PDO::FETCH_ASSOC)){
	$daterounded = strtotime( $ticket['date'] );
	$datea = date( 'M', $daterounded );
	$dateb = date( 'm', $daterounded );
	// Get number of tickets from year
	$dates = $pdo->prepare('SELECT id FROM support WHERE MONTH(date)='.$dateb.'');
	$dates->execute();
    echo "['".$datea."',".$dates->rowCount()."],";
} ?>
    ]);

        var options = {
            title: 'Tickets this year',
            curveType: 'function',
            legend: { position: 'bottom' }
        };

        var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
        chart.draw(data, options);
    }
</script>
</div>

<div class="content-block-short">
    <div class="table_users">
		<div class="title">Latest Accounts</div>
		<div class="sub_title">Total Users: <?php echo $astmt1->rowCount(); ?></div>
        <table  cellspacing="0" style="width:500px;">
            <thead>
                <tr>
                    <td>#</td>
                    <td>Username</td>
                    <td>Email</td>
                    <td>Role</td>
                </tr>
            </thead>
            <tbody>
                <?php if ($stmt->rowCount() == 0): ?>
                <tr>
                    <td colspan="8" style="text-align:center;">There are no accounts</td>
                </tr>
                <?php else: ?>
                <?php while ($account = $astmt->fetch(PDO::FETCH_ASSOC)): ?>
                <tr class="details" onclick="location.href='account.php?id=<?=$account['id']?>'">
                    <td></td>
                    <td><?=$account['username']?></td>                 
                    <td><?=$account['email']?></td>
                    <td><?=$account['role']?></td>
                </tr>
                <?php endwhile; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
	</div><div class="content-block-short">
	<div class="table_devices">
		<div class="title">Latest Devices</div>
		<div class="sub_title">Total Devices: <?php  echo $dtmt1->rowCount(); ?></div>
        <table  cellspacing="0" style="width:500px;">
            <thead>
                <tr>
                    <td>#</td>
                    <td>Device ID</td>
                    <td>Device Type</td>
                    <td>Department</td>
                    <td>Make</td>
					<td>Model</td>
                </tr>
            </thead>
            <tbody>
                <?php if ($dtmt1->rowCount() == 0): ?>
                <tr>
                    <td colspan="8" style="text-align:center;">There are no support Tickets</td>
                </tr>
                <?php else: ?>
                <?php while ($device = $dtmt->fetch(PDO::FETCH_ASSOC)): ?>
                <tr class="details" onclick="location.href='add_device.php?id=<?=$id?>'">
                    <td></td>
                    <td><?=$device['device_id']?></td>                 
                    <td><?=$device['device_type']?></td>
                    <td><?=$device['department']?></td>
                    <td><?=$device['make']?></td>
					<td><?=$device['model']?></td>
                </tr>
                <?php endwhile; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
            </tbody>
        </table>
</div></div>
</div>

<?=template_admin_footer()?>
