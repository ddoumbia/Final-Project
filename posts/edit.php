<?php
include('../includes/header.php');
include('../includes/auth.php');
check_login();

$post_id = $_GET['id'] ?? null;
$errors = [];

if (!$post_id) {
    header('Location: index.php');
    exit();
}

// Fetch existing post data
$stmt = $conn->prepare("SELECT * FROM Post WHERE post_id = ?");
$stmt->bind_param('i', $post_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "<h2>Post Not Found</h2>";
    include('../includes/footer.php');
    exit();
}

$post = $result->fetch_assoc();

// Check ownership or admin
if ($_SESSION['user_id'] != $post['author_id'] && !$_SESSION['is_admin']) {
    header('Location: index.php');
    exit();
}

$title = $post['title'];
$content = $post['content'];

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
        $stmt = $conn->prepare("UPDATE Post SET title = ?, content = ? WHERE post_id = ?");
        $stmt->bind_param('ssi', $title, $content, $post_id);
        if ($stmt->execute()) {
            header('Location: index.php');
            exit();
        } else {
            $errors[] = "Error: " . $stmt->error;
        }
    }
}
?>
<h2>Edit Post</h2>
<?php
if (!empty($errors)) {
    echo '<div class="alert alert-danger"><ul>';
    foreach ($errors as $error) {
        echo "<li>" . htmlspecialchars($error) . "</li>";
    }
    echo '</ul></div>';
}
?>
<form method="post" action="edit.php?id=<?php echo $post_id; ?>">
    <div class="form-group">
        <label for="title">Title:</label>
        <input type="text" name="title" id="title" class="form-control" value="<?php echo htmlspecialchars($title); ?>" required>
    </div>
    <div class="form-group">
        <label for="content">Content:</label>
        <textarea name="content" id="content" class="form-control" rows="10" required><?php echo htmlspecialchars($content); ?></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Update Post</button>
</form>
<?php
include('../includes/footer.php');
?>
