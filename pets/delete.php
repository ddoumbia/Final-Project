<?php
include('../includes/header.php');
include('../includes/auth.php');
check_login();

$pet_id = $_GET['id'] ?? null;

if (!$pet_id) {
    header('Location: index.php');
    exit();
}

// Fetch the pet to check ownership
$stmt = $conn->prepare("SELECT owner_id FROM Pet WHERE pet_id = ?");
$stmt->bind_param('i', $pet_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "<h2>Pet Not Found</h2>";
    include('../includes/footer.php');
    exit();
}

$pet = $result->fetch_assoc();

// Check ownership or admin
if ($_SESSION['user_id'] != $pet['owner_id'] && !$_SESSION['is_admin']) {
    header('Location: index.php');
    exit();
}

// Delete the pet
$stmt = $conn->prepare("DELETE FROM Pet WHERE pet_id = ?");
$stmt->bind_param('i', $pet_id);
if ($stmt->execute()) {
    header('Location: index.php');
    exit();
} else {
    echo "Error: " . $stmt->error;
}
include('../includes/footer.php');
?>
