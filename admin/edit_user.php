<?php
include('../includes/header.php');
include('../includes/auth.php');
check_admin();

$user_id = $_GET['id'] ?? null;
$errors = [];

if (!$user_id) {
    header('Location: manage_users.php');
    exit();
}

// Fetch existing user data
$stmt = $conn->prepare("SELECT * FROM User WHERE user_id = ?");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "<h2>User Not Found</h2>";
    include('../includes/footer.php');
    exit();
}

$user = $result->fetch_assoc();
$username = $user['username'];
$email = $user['email'];
$is_admin = $user['is_admin'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $is_admin = isset($_POST['is_admin']) ? 1 : 0;

    // Validate inputs
    if (empty($username)) {
        $errors[] = "Username is required.";
    }
    if (empty($email)) {
        $errors[] = "Email is required.";
    }

    // Check for existing username or email
    $stmt = $conn->prepare("SELECT * FROM User WHERE (username = ? OR email = ?) AND user_id != ?");
    $stmt->bind_param('ssi', $username, $email, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $errors[] = "Username or email already exists.";
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("UPDATE User SET username = ?, email = ?, is_admin = ? WHERE user_id = ?");
        $stmt->bind_param('ssii', $username, $email, $is_admin, $user_id);
        if ($stmt->execute()) {
            header('Location: manage_users.php');
            exit();
        } else {
            $errors[] = "Error: " . $stmt->error;
        }
    }
}
?>
<h2>Edit User</h2>
<?php
if (!empty($errors)) {
    echo '<div class="alert alert-danger"><ul>';
    foreach ($errors as $error) {
        echo "<li>" . htmlspecialchars($error) . "</li>";
    }
    echo '</ul></div>';
}
?>
<form method="post" action="edit_user.php?id=<?php echo $user_id; ?>">
    <div class="form-group">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" class="form-control" value="<?php echo htmlspecialchars($username); ?>" required>
    </div>
    <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" class="form-control" value="<?php echo htmlspecialchars($email); ?>" required>
    </div>
    <div class="form-group form-check">
        <input type="checkbox" name="is_admin" id="is_admin" class="form-check-input" <?php echo $is_admin ? 'checked' : ''; ?>>
        <label for="is_admin" class="form-check-label">Is Admin</label>
    </div>
    <button type="submit" class="btn btn-primary">Update User</button>
</form>
<?php
include('../includes/footer.php');
?>
