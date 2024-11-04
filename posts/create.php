<?php
include('../includes/header.php');
include('../includes/auth.php');
check_login();

$title = '';
$content = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    // Validate inputs
    if (empty($title)) {
        $errors[] = "Title is required.";
    }
    if (empty($content)) {
        $errors[] = "Content is required.";
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO Post (title, content, author_id) VALUES (?, ?, ?)");
        $stmt->bind_param('ssi', $title, $content, $_SESSION['user_id']);
        if ($stmt->execute()) {
            header('Location: index.php');
            exit();
        } else {
            $errors[] = "Error: " . $stmt->error;
        }
    }
}
?>
<h2>Create New Post</h2>
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
        <label for="title">Title:</label>
        <input type="text" name="title" id="title" class="form-control" value="<?php echo htmlspecialchars($title); ?>" required>
    </div>
    <div class="form-group">
        <label for="content">Content:</label>
        <textarea name="content" id="content" class="form-control" rows="10" required><?php echo htmlspecialchars($content); ?></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Publish Post</button>
</form>
<?php
include('../includes/footer.php');
?>
