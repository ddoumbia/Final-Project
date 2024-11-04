<?php
include('../includes/header.php');
include('../includes/auth.php');
check_login();

$post_id = $_GET['id'] ?? null;

if (!$post_id) {
    header('Location: index.php');
    exit();
}

// Fetch the post to check ownership
$stmt = $conn->prepare("SELECT author_id FROM Post WHERE post_id = ?");
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

// Delete the post
$stmt = $conn->prepare("DELETE FROM Post WHERE post_id = ?");
$stmt->bind_param('i', $post_id);
if ($stmt->execute()) {
    header('Location: index.php');
    exit();
} else {
    echo "Error: " . $stmt->error;
}
include('../includes/footer.php');
?>