<?php
include 'main.php';
include 'header.php';
?>

<h2>Home</h2>
<div class="maincont">
<div class="content-block-short">
	<script type="text/javascript">
      google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Task', 'This Weeks Tickets'],
          ['Open Tickets',     <?php echo $otmt->num_rows;?>],
          ['Closed Tickets',      <?php echo $ctmt->num_rows;?>],
        ]);

        var options = {
          title: 'This Weeks Tickets',
          pieHole: 0.4,
        };

        var chart = new google.visualization.PieChart(document.getElementById('donutchart1'));
        chart.draw(data, options);
      }
    </script>
<div id="donutchart1" style="width: 500px; height: 500px;float:left;display:block;"></div>
</div><div class="content-block-short">
	<script type="text/javascript">
      google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Task', 'This Months Tickets'],
          ['Open Tickets',     <?php echo $o1tmt->num_rows;?>],
          ['Closed Tickets',      <?php echo $c1tmt->num_rows;?>],
        ]);

        var options = {
          title: 'This Months Tickets',
          pieHole: 0.4,
        };

        var chart = new google.visualization.PieChart(document.getElementById('donutchart2'));
        chart.draw(data, options);
      }
    </script>
<div id="donutchart2" style="width: 500px; height: 500px;float:left;display:block;"></div>
</div><div class="content-block-short">
	<div id="chart_div" style="width: 500px; height: 500px;"></div>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
    google.load("visualization", "1", {packages:["corechart"]});
    google.setOnLoadCallback(drawChart);
    function drawChart() {
        var data = google.visualization.arrayToDataTable([
        ['Month',	'Tickets'],
        <?php while ($ytmt->fetch()){
	$daterounded = strtotime( $date );
	$datea = date( 'M', $daterounded );
	$dateb = date( 'm', $daterounded );
	// Get number of tickets from year
$dates = $con->prepare('SELECT id FROM support WHERE MONTH(date)='.$dateb.'');
$dates->execute();
$dates->store_result();
$dates->bind_result($id);
    echo "['".$datea."',".$dates->num_rows."],";
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
		<div class="sub_title">Total Users: <?php echo $stmt->num_rows; ?></div>
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
                <?php if ($stmt->num_rows == 0): ?>
                <tr>
                    <td colspan="8" style="text-align:center;">There are no accounts</td>
                </tr>
                <?php else: ?>
                <?php while ($stmt->fetch()): ?>
                <tr class="details" onclick="location.href='account.php?id=<?=$id?>'">
                    <td></td>
                    <td><?=$username?></td>                 
                    <td><?=$email?></td>
                    <td><?=$role?></td>
                </tr>
                <?php endwhile; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
	</div><div class="content-block-short">
	<div class="table_devices">
		<div class="title">Latest Devices</div>
		<div class="sub_title">Total Devices: <?php  echo $dtmt->num_rows; ?></div>
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
                <?php if ($stmt->num_rows == 0): ?>
                <tr>
                    <td colspan="8" style="text-align:center;">There are no support Tickets</td>
                </tr>
                <?php else: ?>
                <?php while ($dtmt->fetch()): ?>
                <tr class="details" onclick="location.href='add_device.php?id=<?=$id?>'">
                    <td></td>
                    <td><?=$device_id?></td>                 
                    <td><?=$device_type?></td>
                    <td><?=$department?></td>
                    <td><?=$make?></td>
					<td><?=$model?></td>
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
