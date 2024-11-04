<?php
include('includes/header.php');
?>

<!-- Jumbotron for a prominent welcome message -->
<div class="jumbotron text-center">
    <h1 class="display-4">Welcome to Group 3 Application</h1>
    <p class="lead">Explore our features and enjoy your stay!</p>
    <hr class="my-4">
    <p>Get started by navigating through the sections below.</p>
</div>

<!-- Features Section -->
<div class="container">
    <div class="row text-center">
        <!-- Pets Card -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Pets</h5>
                    <p class="card-text">View and manage your pets.</p>
                    <a href="/pets/index.php" class="btn btn-primary">View Pets</a>
                </div>
            </div>
        </div>
        <!-- Posts Card -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Posts</h5>
                    <p class="card-text">Read and share posts.</p>
                    <a href="/posts/index.php" class="btn btn-secondary">View Posts</a>
                </div>
            </div>
        </div>
        <!-- Pages Card -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Pages</h5>
                    <p class="card-text">Explore static pages.</p>
                    <a href="/pages/index.php" class="btn btn-info">View Pages</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Authentication Buttons -->
<div class="text-center mt-4">
    <?php if(!isset($_SESSION['user_id'])): ?>
        <a href="/register.php" class="btn btn-success mr-2">Register</a>
        <a href="/login.php" class="btn btn-outline-primary">Login</a>
    <?php else: ?>
        <a href="/logout.php" class="btn btn-danger">Logout</a>
    <?php endif; ?>
</div>

<?php
include('includes/footer.php');
?>
