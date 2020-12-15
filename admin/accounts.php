<?php
include 'main.php';
// query to get all accounts from the database
$limit = 10;  
if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };  
$start_from = ($page-1) * $limit;
$stmt = $pdo->prepare('SELECT id, username, password, email, activation_code, role FROM accounts ORDER BY id ASC LIMIT '.$start_from.', '.$limit.'');
$stmt->execute();
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
                    <td class="responsive-hidden">Email</td>
                    <td class="responsive-hidden">Activation Code</td>
                    <td class="responsive-hidden">Role</td>
                </tr>
            </thead>
            <tbody>
                <?php if ($stmt->rowCount() == 0): ?>
                <tr>
                    <td colspan="8" style="text-align:center;">There are no accounts</td>
                </tr>
                <?php else: ?>
                <?php while ($account = $stmt->fetch(PDO::FETCH_ASSOC)){ ?>
                <tr class="details" onclick="location.href='account.php?id=<?=$account['id']?>'">
                    <td><?=$account['id']?></td>
                    <td><?=$account['username']?></td>
                    <td class="responsive-hidden"><?=$account['email']?></td>
                    <td class="responsive-hidden"><?=$account['activation_code']?></td>
                    <td class="responsive-hidden"><?=$account['role']?></td>
                </tr>
                <?php } ?>
				<?php endif; ?>

            </tbody>
        </table>
		<?php 
				
$dtmt = $pdo->prepare('SELECT id FROM accounts');
$dtmt->execute();  
$total_records = $dtmt->rowCount();
$total_pages = ceil($total_records / $limit); 
$pagLink = '<div class="pagination">Page: ';
for ($i=1; $i<=$total_pages; $i++) 
{  
    $pagLink .= '<a href="accounts.php?page='.$i.'">'.$i.'</a> ';  
}  
echo $pagLink . '</div>'; ?>
    </div>
</div>

<?=template_admin_footer()?>
