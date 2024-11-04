<?php
include('../includes/header.php');

// Fetch all posts
$sql = "SELECT p.*, u.username FROM Post p JOIN User u ON p.author_id = u.user_id ORDER BY date_created DESC";
$result = $conn->query($sql);
?>
<h2>All Posts</h2>
<?php if(isset($_SESSION['user_id'])): ?>
    <a href="create.php" class="btn btn-primary">Create New Post</a>
<?php endif; ?>
<div class="list-group mt-3">
    <?php while ($post = $result->fetch_assoc()): ?>
        <a href="view.php?id=<?php echo $post['post_id']; ?>" class="list-group-item list-group-item-action">
            <h5 class="mb-1"><?php echo htmlspecialchars($post['title']); ?></h5>
            <small>By <?php echo htmlspecialchars($post['username']); ?> on <?php echo htmlspecialchars($post['date_created']); ?></small>
        </a>
    <?php endwhile; ?>
</div>
<?php
include('../includes/footer.php');
?>
