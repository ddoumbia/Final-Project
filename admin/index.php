<?php
include('../includes/header.php');
include('../includes/auth.php');
check_admin();
?>
<h2>Admin Dashboard</h2>
<ul class="list-group mt-3">
    <li class="list-group-item"><a href="manage_users.php">Manage Users</a></li>
    <li class="list-group-item"><a href="../pets/index.php">Manage Pets</a></li>
    <li class="list-group-item"><a href="../posts/index.php">Manage Posts</a></li>
    <li class="list-group-item"><a href="../pages/index.php">Manage Pages</a></li>
</ul>
<?php
include('../includes/footer.php');
?>
