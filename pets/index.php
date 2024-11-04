<?php
include('../includes/header.php');

// Fetch all pets
$sql = "SELECT p.*, u.username FROM Pet p JOIN User u ON p.owner_id = u.user_id";
$result = $conn->query($sql);
?>
<h2>All Pets</h2>
<?php if(isset($_SESSION['user_id'])): ?>
    <a href="create.php" class="btn btn-primary">Add New Pet</a>
<?php endif; ?>
<table class="table table-striped mt-3">
    <thead>
        <tr>
            <th>Name</th>
            <th>Species</th>
            <th>Age</th>
            <th>Owner</th>
            <?php if (isset($_SESSION['user_id'])): ?>
            <th>Actions</th>
            <?php endif; ?>
        </tr>
    </thead>
    <tbody>
        <?php while ($pet = $result->fetch_assoc()): ?>
        <tr>
            <td><a href="view.php?id=<?php echo $pet['pet_id']; ?>"><?php echo htmlspecialchars($pet['name']); ?></a></td>
            <td><?php echo htmlspecialchars($pet['species']); ?></td>
            <td><?php echo htmlspecialchars($pet['age']); ?></td>
            <td><?php echo htmlspecialchars($pet['username']); ?></td>
            <?php if (isset($_SESSION['user_id']) && ($_SESSION['user_id'] == $pet['owner_id'] || $_SESSION['is_admin'])): ?>
            <td>
                <a href="edit.php?id=<?php echo $pet['pet_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                <a href="delete.php?id=<?php echo $pet['pet_id']; ?>" class="btn btn-danger btn-sm">Delete</a>
            </td>
            <?php endif; ?>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>
<?php
include('../includes/footer.php');
?>
