<?php
// Include the root "main.php" file and check if user is logged-in...
include_once '../config.php';
include_once '../main.php';
include '../languages/'.languages($con).'.php';
check_loggedin($con, '../index.php');
$stmt = $con->prepare('SELECT password, email, role, username FROM accounts WHERE id = ?');
// Get the account info using the logged-in session ID
$stmt->bind_param('i', $_SESSION['id']);
$stmt->execute();
$stmt->bind_result($password, $email, $role, $username);
$stmt->fetch();
$stmt->close();
// Check if the user is an admin...
if ($role != 'Admin') {
    exit('You do not have permission to access this page!');
}

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
// monthly tickets closed
$ctmt = $con->prepare('SELECT id, device_id, user_id, status, problem, date FROM support WHERE status = 1 AND date >= DATE_SUB(now(),INTERVAL 1 WEEK) ');
$ctmt->execute();
$ctmt->store_result();
$ctmt->bind_result($id, $device_id, $user_id, $status, $problem, $date);
// Weekly Tickets open
$otmt = $con->prepare('SELECT id, device_id, user_id, status, problem, date FROM support WHERE status = 2 AND date >= DATE_SUB(now(),INTERVAL 1 WEEK) ');
$otmt->execute();
$otmt->store_result();
$otmt->bind_result($id, $device_id, $user_id, $status, $problem, $date);
// monthly tickets closed
$c1tmt = $con->prepare('SELECT id, device_id, user_id, status, problem, date FROM support WHERE status = 1 AND date >= DATE_SUB(now(),INTERVAL 1 MONTH) ');
$c1tmt->execute();
$c1tmt->store_result();
$c1tmt->bind_result($id, $device_id, $user_id, $status, $problem, $date);
// monthly tickets open
$o1tmt = $con->prepare('SELECT id, device_id, user_id, status, problem, date FROM support WHERE status = 2 AND date >= DATE_SUB(now(),INTERVAL 1 MONTH) ');
$o1tmt->execute();
$o1tmt->store_result();
$o1tmt->bind_result($id, $device_id, $user_id, $status, $problem, $date);

// yearly tickets
$ytmt = $con->prepare('SELECT id, date FROM support WHERE date >= DATE_SUB(now(),INTERVAL 1 YEAR) GROUP BY MONTH(date)');
$ytmt->execute();
$ytmt->store_result();
$ytmt->bind_result($id, $date);

// Template admin footer
function template_admin_footer() {
echo <<<EOT
<div class="responsive-width-100 footer">Created By <a href="https://davidlomas.eu" title="David Lomas" target="_blank">David Lomas</a> | <a href="https://davidlomas.eu/ict-support-script-free/" title="ICT Support" target="_blank">ICTSupport</a> GNU GPL</div>	
        </main>
        <script>
        document.querySelector(".responsive-toggle").onclick = function(event) {
            event.preventDefault();
            var aside_display = document.querySelector("aside").style.display;
            document.querySelector("aside").style.display = aside_display == "flex" ? "none" : "flex";
        };
        </script>
    </body>
</html>
EOT;
}
?>
