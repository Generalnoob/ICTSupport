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
