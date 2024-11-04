<?php
include('../includes/header.php');

// Fetch all pages
$stmt = $conn->prepare("SELECT * FROM Page");
$stmt->execute();
$result = $stmt->get_result();
?>
<h2>Pages</h2>
<?php if(isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
    <a href="edit.php" class="btn btn-primary">Create New Page</a>
<?php endif; ?>
<ul class="list-group mt-3">
    <?php while ($page = $result->fetch_assoc()): ?>
        <li class="list-group-item">
            <a href="view.php?slug=<?php echo urlencode($page['slug']); ?>"><?php echo htmlspecialchars($page['title']); ?></a>
            <?php if(isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
                <a href="edit.php?id=<?php echo $page['page_id']; ?>" class="btn btn-sm btn-secondary float-right">Edit</a>
            <?php endif; ?>
        </li>
    <?php endwhile; ?>
</ul>
<?php
include('../includes/footer.php');
?>
