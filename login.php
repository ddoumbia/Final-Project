<?php
include('includes/header.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Initialize error array
    $errors = [];

    // Validate inputs
    if (empty($email)) {
        $errors[] = "Email is required.";
    }
    if (empty($password)) {
        $errors[] = "Password is required.";
    }

    // If no errors, proceed to login
    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT * FROM User WHERE email = ?");
        $stmt->bind_param('s', $email);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                // Verify password
                if (password_verify($password, $user['password_hash'])) {
                    // Password is correct, set session variables
                    session_regenerate_id(true);
                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['is_admin'] = $user['is_admin'];
                    header('Location: index.php');
                    exit();
                } else {
                    $errors[] = "Invalid email or password.";
                }
            } else {
                $errors[] = "No account found with that email.";
            }
        } else {
            $errors[] = "Error: " . $stmt->error;
        }
    }
}
?>
<h2>Login</h2>
<?php
if (!empty($errors)) {
    echo '<div class="alert alert-danger"><ul>';
    foreach ($errors as $error) {
        echo "<li>" . htmlspecialchars($error) . "</li>";
    }
    echo '</ul></div>';
}
?>
<form method="post" action="login.php">
    <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" class="form-control" value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
    </div>
    <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Login</button>
</form>
<?php
include('includes/footer.php');
?>
