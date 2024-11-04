<?php
include('../includes/header.php');

$slug = $_GET['slug'] ?? '';

$stmt = $conn->prepare("SELECT * FROM Page WHERE slug = ?");
$stmt->bind_param('s', $slug);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "<h2>Page Not Found</h2>";
} else {
    $page = $result->fetch_assoc();
    ?>
    <h2><?php echo htmlspecialchars($page['title']); ?></h2>
    <div><?php echo nl2br(htmlspecialchars($page['content'])); ?></div>
    <?php
}
include('../includes/footer.php');
?>
