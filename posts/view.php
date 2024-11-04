<?php
include('../includes/header.php');

$post_id = $_GET['id'] ?? null;

if (!$post_id) {
    echo "<h2>Post Not Found</h2>";
    include('../includes/footer.php');
    exit();
}

$stmt = $conn->prepare("SELECT p.*, u.username FROM Post p JOIN User u ON p.author_id = u.user_id WHERE p.post_id = ?");
$stmt->bind_param('i', $post_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "<h2>Post Not Found</h2>";
    include('../includes/footer.php');
    exit();
}

$post = $result->fetch_assoc();
?>
<h2><?php echo htmlspecialchars($post['title']); ?></h2>
<p><small>By <?php echo htmlspecialchars($post['username']); ?> on <?php echo htmlspecialchars($post['date_created']); ?></small></p>
<div><?php echo nl2br(htmlspecialchars($post['content'])); ?></div>
<?php
if (isset($_SESSION['user_id']) && ($_SESSION['user_id'] == $post['author_id'] || $_SESSION['is_admin'])):
?>
    <a href="edit.php?id=<?php echo $post['post_id']; ?>" class="btn btn-warning">Edit</a>
    <a href="delete.php?id=<?php echo $post['post_id']; ?>" class="btn btn-danger">Delete</a>
<?php
endif;
include('../includes/footer.php');
?>
