<?php
include('../includes/header.php');

$pet_id = $_GET['id'] ?? null;

if (!$pet_id) {
    echo "<h2>Pet Not Found</h2>";
    include('../includes/footer.php');
    exit();
}

$stmt = $conn->prepare("SELECT p.*, u.username FROM Pet p JOIN User u ON p.owner_id = u.user_id WHERE p.pet_id = ?");
$stmt->bind_param('i', $pet_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "<h2>Pet Not Found</h2>";
    include('../includes/footer.php');
    exit();
}

$pet = $result->fetch_assoc();
?>
<h2><?php echo htmlspecialchars($pet['name']); ?></h2>
<p><strong>Species:</strong> <?php echo htmlspecialchars($pet['species']); ?></p>
<p><strong>Age:</strong> <?php echo htmlspecialchars($pet['age']); ?></p>
<p><strong>Owner:</strong> <?php echo htmlspecialchars($pet['username']); ?></p>
<?php
if (isset($_SESSION['user_id']) && ($_SESSION['user_id'] == $pet['owner_id'] || $_SESSION['is_admin'])):
?>
    <a href="edit.php?id=<?php echo $pet['pet_id']; ?>" class="btn btn-warning">Edit</a>
    <a href="delete.php?id=<?php echo $pet['pet_id']; ?>" class="btn btn-danger">Delete</a>
<?php
endif;
include('../includes/footer.php');
?>
