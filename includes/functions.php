<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!function_exists('redirect_if_not_logged_in')) {
    function redirect_if_not_logged_in() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: login.php');
            exit();
        }
    }
}

if (!function_exists('contains_sql_commands')) {
    function contains_sql_commands($value) {
        $sql_commands = ['SELECT', 'INSERT', 'UPDATE', 'DELETE', 'DROP', 'TRUNCATE', 'ALTER', 'CREATE', 'RENAME'];
        foreach ($sql_commands as $command) {
            if (stripos($value, $command) !== false) {
                return true;
            }
        }
        return false;
    }
}

if (!function_exists('is_logged_in')) {
    function is_logged_in() {
        return isset($_SESSION['user_id']);
    }
}

if (!function_exists('is_admin')) {
    function is_admin() {
        return isset($_SESSION['access_level']) && $_SESSION['access_level'] === 'admin';
    }
}

if (!function_exists('redirect_if_not_admin')) {
    function redirect_if_not_admin() {
        if (!is_admin()) {
            header('Location: index.php');
            exit();
        }
    }
}
?>
