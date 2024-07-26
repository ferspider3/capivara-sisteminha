<?php
session_start();

function is_logged_in() {
    return isset($_SESSION['user_id']);
}

function is_admin() {
    return is_logged_in() && $_SESSION['access_level'] === 'admin';
}

function redirect_if_not_logged_in() {
    if (!is_logged_in()) {
        header('Location: login.php');
        exit();
    }
}

function redirect_if_not_admin() {
    if (!is_admin()) {
        header('Location: index.php');
        exit();
    }
}
?>
