<?php
include 'main.php';
// query to get all accounts from the database
$limit = 2;  
if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };  
$start_from = ($page-1) * $limit;
$stmt = $con->prepare('SELECT id, username, password, email, activation_code, role FROM accounts ORDER BY id ASC LIMIT '.$start_from.', '.$limit.'');
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($id, $username, $password, $email, $activation_code, $role);
include 'header.php';
?>

<h2>Accounts</h2>

<div class="links">
    <a href="account.php">Create Account</a>
</div>

<div class="content-block">
    <div class="table">
        <table>
            <thead>
                <tr>
                    <td>#</td>
                    <td>Username</td>
                    <td class="responsive-hidden">Password</td>
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
                    <td><?=$id?></td>
                    <td><?=$username?></td>
                    <td class="responsive-hidden"><?=$password?></td>
                    <td class="responsive-hidden"><?=$email?></td>
                    <td class="responsive-hidden"><?=$activation_code?></td>
                    <td class="responsive-hidden"><?=$role?></td>
                </tr>
                <?php endwhile; ?>
				<?php endif; ?>

            </tbody>
        </table>
		<?php 
				
$dtmt = $con->prepare('SELECT id FROM accounts');
$dtmt->execute();
$dtmt->store_result();
$dtmt->bind_result($id);  
$total_records = $dtmt->num_rows;
$total_pages = ceil($total_records / $limit); 
$pagLink = '<div class="pagination">';
for ($i=1; $i<=$total_pages; $i++) 
{  
    $pagLink .= '<a href="accounts.php?page='.$i.'">'.$i.'</a> ';  
}  
echo $pagLink . '</div>'; ?>
    </div>
</div>

<?=template_admin_footer()?>
