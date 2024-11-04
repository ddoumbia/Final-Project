<?php
// includes/auth.php

function check_login() {
    if(!isset($_SESSION['user_id'])) {
        header('Location: /login.php');
        exit();
    }
}

function check_admin() {
    if(!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
        header('Location: /index.php');
        exit();
    }
}
?>
