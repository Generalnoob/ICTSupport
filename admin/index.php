<?php
include 'main.php';
// query to get all accounts from the database
$stmt = $con->prepare('SELECT id, username, password, email, activation_code, role FROM accounts ORDER BY id DESC LIMIT 5');
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($id, $username, $password, $email, $activation_code, $role);
// query to get all devices from the database
$dtmt = $con->prepare('SELECT id, device_type, department, device_id, make, model FROM devices ORDER BY id DESC LIMIT 5');
$dtmt->execute();
$dtmt->store_result();
$dtmt->bind_result($id, $device_type, $department, $device_id, $make, $model);
// query to get all support tickets from the database
$xtmt = $con->prepare('SELECT id, device_id, user_id, status, problem, date FROM support WHERE status = 2 ORDER BY date DESC');
$xtmt->execute();
$xtmt->store_result();
$xtmt->bind_result($id, $device_id, $user_id, $status, $problem, $date);

$ctmt = $con->prepare('SELECT id, device_id, user_id, status, problem, date FROM support WHERE status = 1 AND date >= DATE_SUB(now(),INTERVAL 1 WEEK) ');
$ctmt->execute();
$ctmt->store_result();
$ctmt->bind_result($id, $device_id, $user_id, $status, $problem, $date);

$otmt = $con->prepare('SELECT id, device_id, user_id, status, problem, date FROM support WHERE status = 2 AND date >= DATE_SUB(now(),INTERVAL 1 WEEK) ');
$otmt->execute();
$otmt->store_result();
$otmt->bind_result($id, $device_id, $user_id, $status, $problem, $date);

$c1tmt = $con->prepare('SELECT id, device_id, user_id, status, problem, date FROM support WHERE status = 1 AND date >= DATE_SUB(now(),INTERVAL 1 MONTH) ');
$c1tmt->execute();
$c1tmt->store_result();
$c1tmt->bind_result($id, $device_id, $user_id, $status, $problem, $date);

$o1tmt = $con->prepare('SELECT id, device_id, user_id, status, problem, date FROM support WHERE status = 2 AND date >= DATE_SUB(now(),INTERVAL 1 MONTH) ');
$o1tmt->execute();
$o1tmt->store_result();
$o1tmt->bind_result($id, $device_id, $user_id, $status, $problem, $date);
include 'header.php';
?>

<h2>Home</h2>

<div class="content-block">
	<script type="text/javascript">
      google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Task', 'Weekly Tickets'],
          ['Open Tickets',     <?php echo $otmt->num_rows;?>],
          ['Closed Tickets',      <?php echo $ctmt->num_rows;?>],
        ]);

        var options = {
          title: 'Weekly Tickets',
          pieHole: 0.4,
        };

        var chart = new google.visualization.PieChart(document.getElementById('donutchart1'));
        chart.draw(data, options);
      }
    </script>
<div id="donutchart1" style="width: 500px; height: 500px;float:left;display:block;"></div>
	
	<script type="text/javascript">
      google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Task', 'Weekly Tickets'],
          ['Open Tickets',     <?php echo $o1tmt->num_rows;?>],
          ['Closed Tickets',      <?php echo $c1tmt->num_rows;?>],
        ]);

        var options = {
          title: 'Monthly Tickets',
          pieHole: 0.4,
        };

        var chart = new google.visualization.PieChart(document.getElementById('donutchart2'));
        chart.draw(data, options);
      }
    </script>
<div id="donutchart2" style="width: 500px; height: 500px;float:left;display:block;"></div>

</div>

<div class="content-block">
    <div class="table_users">
		<div class="title">Latest Accounts</div>
		<div class="sub_title">Total Users: <?php echo $stmt->num_rows; ?></div>
        <table  cellspacing="0">
            <thead>
                <tr>
                    <td>#</td>
                    <td>Username</td>
                    <td class="responsive-hidden">Email</td>
                    <td class="responsive-hidden">Activation Code</td>
                    <td class="responsive-hidden">Role</td>
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
                    <td class="responsive-hidden"><?=$email?></td>
                    <td class="responsive-hidden"><?=$activation_code?></td>
                    <td class="responsive-hidden"><?=$role?></td>
                </tr>
                <?php endwhile; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
	<div class="table_devices">
		<div class="title">Latest Devices</div>
		<div class="sub_title">Total Devices: <?php  echo $dtmt->num_rows; ?></div>
        <table  cellspacing="0">
            <thead>
                <tr>
                    <td>#</td>
                    <td>Device ID</td>
                    <td class="responsive-hidden">Device Type</td>
                    <td class="responsive-hidden">Department</td>
                    <td class="responsive-hidden">Make</td>
					<td class="responsive-hidden">Model</td>
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
                    <td class="responsive-hidden"><?=$device_type?></td>
                    <td class="responsive-hidden"><?=$department?></td>
                    <td class="responsive-hidden"><?=$make?></td>
					<td class="responsive-hidden"><?=$model?></td>
                </tr>
                <?php endwhile; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
            </tbody>
        </table>
    </div>
</div>

<?=template_admin_footer()?>
