<?php
include('../includes/header.php');
include('../includes/auth.php');
check_admin();

// Fetch all users
$stmt = $conn->prepare("SELECT * FROM User");
$stmt->execute();
$result = $stmt->get_result();
?>
<h2>Manage Users</h2>
<table class="table table-striped mt-3">
    <thead>
        <tr>
            <th>User ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Is Admin</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($user = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($user['user_id']); ?></td>
            <td><?php echo htmlspecialchars($user['username']); ?></td>
            <td><?php echo htmlspecialchars($user['email']); ?></td>
            <td><?php echo $user['is_admin'] ? 'Yes' : 'No'; ?></td>
            <td>
                <a href="edit_user.php?id=<?php echo $user['user_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                <?php if ($user['user_id'] != $_SESSION['user_id']): ?>
                <a href="delete_user.php?id=<?php echo $user['user_id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                <?php endif; ?>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>
<?php
include('../includes/footer.php');
?>
