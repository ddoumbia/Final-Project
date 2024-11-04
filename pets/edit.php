<?php
include('../includes/header.php');
include('../includes/auth.php');
check_login();

$pet_id = $_GET['id'] ?? null;
$errors = [];

if (!$pet_id) {
    header('Location: index.php');
    exit();
}

// Fetch existing pet data
$stmt = $conn->prepare("SELECT * FROM Pet WHERE pet_id = ?");
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

$name = $pet['name'];
$species = $pet['species'];
$age = $pet['age'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $species = trim($_POST['species']);
    $age = trim($_POST['age']);

    // Validate inputs
    if (empty($name)) {
        $errors[] = "Name is required.";
    }
    if (empty($species)) {
        $errors[] = "Species is required.";
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("UPDATE Pet SET name = ?, species = ?, age = ? WHERE pet_id = ?");
        $stmt->bind_param('ssii', $name, $species, $age, $pet_id);
        if ($stmt->execute()) {
            header('Location: index.php');
            exit();
        } else {
            $errors[] = "Error: " . $stmt->error;
        }
    }
}
?>
<h2>Edit Pet</h2>
<?php
if (!empty($errors)) {
    echo '<div class="alert alert-danger"><ul>';
    foreach ($errors as $error) {
        echo "<li>" . htmlspecialchars($error) . "</li>";
    }
    echo '</ul></div>';
}
?>
<form method="post" action="edit.php?id=<?php echo $pet_id; ?>">
    <div class="form-group">
        <label for="name">Pet Name:</label>
        <input type="text" name="name" id="name" class="form-control" value="<?php echo htmlspecialchars($name); ?>" required>
    </div>
    <div class="form-group">
        <label for="species">Species:</label>
        <input type="text" name="species" id="species" class="form-control" value="<?php echo htmlspecialchars($species); ?>" required>
    </div>
    <div class="form-group">
        <label for="age">Age:</label>
        <input type="number" name="age" id="age" class="form-control" value="<?php echo htmlspecialchars($age); ?>">
    </div>
    <button type="submit" class="btn btn-primary">Update Pet</button>
</form>
<?php
include('../includes/footer.php');
?>
