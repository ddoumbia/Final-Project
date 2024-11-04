<?php
include('../includes/header.php');
include('../includes/auth.php');
check_admin();

$page_id = $_GET['id'] ?? null;
$title = '';
$content = '';
$slug = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $page_id = $_POST['page_id'] ?? null;
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $slug = trim($_POST['slug']);

    // Validate inputs
    if (empty($title)) {
        $errors[] = "Title is required.";
    }
    if (empty($slug)) {
        $errors[] = "Slug is required.";
    }

    // Check for existing slug
    $stmt = $conn->prepare("SELECT * FROM Page WHERE slug = ? AND page_id != ?");
    $stmt->bind_param('si', $slug, $page_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $errors[] = "Slug already exists.";
    }

    if (empty($errors)) {
        if ($page_id) {
            // Update existing page
            $stmt = $conn->prepare("UPDATE Page SET title = ?, content = ?, slug = ? WHERE page_id = ?");
            $stmt->bind_param('sssi', $title, $content, $slug, $page_id);
        } else {
            // Insert new page
            $stmt = $conn->prepare("INSERT INTO Page (title, content, slug) VALUES (?, ?, ?)");
            $stmt->bind_param('sss', $title, $content, $slug);
        }

        if ($stmt->execute()) {
            header('Location: index.php');
            exit();
        } else {
            $errors[] = "Error: " . $stmt->error;
        }
    }
} elseif ($page_id) {
    // Fetch existing page data
    $stmt = $conn->prepare("SELECT * FROM Page WHERE page_id = ?");
    $stmt->bind_param('i', $page_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $page = $result->fetch_assoc();
    $title = $page['title'];
    $content = $page['content'];
    $slug = $page['slug'];
}
?>
<h2><?php echo $page_id ? 'Edit Page' : 'Create Page'; ?></h2>
<?php
if (!empty($errors)) {
    echo '<div class="alert alert-danger"><ul>';
    foreach ($errors as $error) {
        echo "<li>" . htmlspecialchars($error) . "</li>";
    }
    echo '</ul></div>';
}
?>
<form method="post" action="edit.php">
    <input type="hidden" name="page_id" value="<?php echo htmlspecialchars($page_id); ?>">
    <div class="form-group">
        <label for="title">Title:</label>
        <input type="text" name="title" id="title" class="form-control" value="<?php echo htmlspecialchars($title); ?>" required>
    </div>
    <div class="form-group">
        <label for="slug">Slug:</label>
        <input type="text" name="slug" id="slug" class="form-control" value="<?php echo htmlspecialchars($slug); ?>" required>
        <small class="form-text text-muted">URL-friendly name (e.g., about-us)</small>
    </div>
    <div class="form-group">
        <label for="content">Content:</label>
        <textarea name="content" id="content" class="form-control" rows="10" required><?php echo htmlspecialchars($content); ?></textarea>
    </div>
    <button type="submit" class="btn btn-primary"><?php echo $page_id ? 'Update' : 'Create'; ?></button>
</form>
<?php
include('../includes/footer.php');
?>
