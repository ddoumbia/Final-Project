<?php
include('includes/header.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve and sanitize form data
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Initialize error array
    $errors = [];

    // Validate inputs
    if (empty($username)) {
        $errors[] = "Username is required.";
    }
    if (empty($email)) {
        $errors[] = "Email is required.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email is invalid.";
    }
    if (empty($password)) {
        $errors[] = "Password is required.";
    }
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    // Check for existing username or email
    $stmt = $conn->prepare("SELECT * FROM User WHERE username = ? OR email = ?");
    $stmt->bind_param('ss', $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $errors[] = "Username or email already exists.";
    }

    // If no errors, proceed to register
    if (empty($errors)) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO User (username, email, password_hash) VALUES (?, ?, ?)");
        $stmt->bind_param('sss', $username, $email, $password_hash);
        if ($stmt->execute()) {
            // Registration successful
            header('Location: login.php');
            exit();
        } else {
            $errors[] = "Error: " . $stmt->error;
        }
    }
}
?>
<h2>Register</h2>
<?php
if (!empty($errors)) {
    echo '<div class="alert alert-danger"><ul>';
    foreach ($errors as $error) {
        echo "<li>" . htmlspecialchars($error) . "</li>";
    }
    echo '</ul></div>';
}
?>
<form method="post" action="register.php">
    <div class="form-group">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" class="form-control" value="<?php echo htmlspecialchars($username ?? ''); ?>" required>
    </div>
    <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" class="form-control" value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
    </div>
    <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="confirm_password">Confirm Password:</label>
        <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Register</button>
</form>
<?php
include('includes/footer.php');
?>
