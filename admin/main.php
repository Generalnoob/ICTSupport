<?php
// Include the root "main.php" file and check if user is logged-in...
include_once '../config.php';
include_once '../main.php';
include '../languages/'.languages($pdo).'.php';
check_loggedin($pdo, '../index.php');
$stmt = $pdo->prepare('SELECT * FROM accounts WHERE id = ?');
$stmt->execute([ $_SESSION['id'] ]);
$account = $stmt->fetch(PDO::FETCH_ASSOC);
// Check if the user is an admin...
if ($account['role'] != 'Admin') {
    exit('You do not have permission to access this page!');
}

// query to get all accounts from the database
$astmt = $pdo->prepare('SELECT * FROM accounts ORDER BY id DESC LIMIT 5');
$astmt->execute();
$astmt1 = $pdo->prepare('SELECT * FROM accounts ORDER BY id DESC');
$astmt1->execute();
// query to get all devices from the database
$dtmt = $pdo->prepare('SELECT * FROM devices ORDER BY id DESC LIMIT 5');
$dtmt->execute();
$devices = $dtmt->fetch(PDO::FETCH_ASSOC);
$dtmt1 = $pdo->prepare('SELECT * FROM devices ORDER BY id DESC LIMIT 5');
$dtmt1->execute();
// query to get all support tickets from the database
$xtmt = $pdo->prepare('SELECT * FROM support WHERE status = 2 ORDER BY date DESC');
$xtmt->execute();
$tickets = $xtmt->fetch(PDO::FETCH_ASSOC);
// Weekly tickets closed
$wtmt = $pdo->prepare('SELECT * FROM support WHERE status = 1 AND date >= DATE_SUB(now(),INTERVAL 1 WEEK) ');
$wtmt->execute();
$w1_tickets = $wtmt->fetch(PDO::FETCH_ASSOC);
// Weekly Tickets open
$otmt = $pdo->prepare('SELECT * FROM support WHERE status = 2 AND date >= DATE_SUB(now(),INTERVAL 1 WEEK) ');
$otmt->execute();
$w2_tickets = $otmt->fetch(PDO::FETCH_ASSOC);
// monthly tickets closed
$ctmt = $pdo->prepare('SELECT * FROM support WHERE status = 1 AND date >= DATE_SUB(now(),INTERVAL 1 MONTH) ');
$ctmt->execute();
$m1_tickets = $ctmt->fetch(PDO::FETCH_ASSOC);
// monthly tickets open
$o1tmt = $pdo->prepare('SELECT * FROM support WHERE status = 2 AND date >= DATE_SUB(now(),INTERVAL 1 MONTH) ');
$o1tmt->execute();
$m2_tickets = $o1tmt->fetch(PDO::FETCH_ASSOC);
// yearly tickets
$ytmt = $pdo->prepare('SELECT id, date FROM support WHERE date >= DATE_SUB(now(),INTERVAL 1 YEAR) GROUP BY MONTH(date)');
$ytmt->execute();

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
