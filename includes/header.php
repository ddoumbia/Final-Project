<?php
// includes/header.php
session_start();
include('db_connect.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Group 3 Application</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/assets/css/styles.css">
</head>
<body>
<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="/index.php">OurApp</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"       aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="/pets/index.php">Pets</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/posts/index.php">Posts</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/pages/index.php">Pages</a>
            </li>
            <?php if(isset($_SESSION['user_id'])): ?>
                <?php if($_SESSION['is_admin']): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/index.php">Admin</a>
                    </li>
                <?php endif; ?>
                <li class="nav-item">
                    <a class="nav-link" href="/logout.php">Logout (<?php echo $_SESSION['username']; ?>)</a>
                </li>
            <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link" href="/login.php">Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/register.php">Register</a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</nav>
<div class="container mt-4">
