<?php
include('../includes/header.php');
include('../includes/auth.php');
check_login();

$name = '';
$species = '';
$age = '';
$errors = [];

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
        $stmt = $conn->prepare("INSERT INTO Pet (name, species, age, owner_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('ssii', $name, $species, $age, $_SESSION['user_id']);
        if ($stmt->execute()) {
            header('Location: index.php');
            exit();
        } else {
            $errors[] = "Error: " . $stmt->error;
        }
    }
}
?>
<h2>Add New Pet</h2>
<?php
if (!empty($errors)) {
    echo '<div class="alert alert-danger"><ul>';
    foreach ($errors as $error) {
        echo "<li>" . htmlspecialchars($error) . "</li>";
    }
    echo '</ul></div>';
}
?>
<form method="post" action="create.php">
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
    <button type="submit" class="btn btn-primary">Add Pet</button>
</form>
<?php
include('../includes/footer.php');
?>
