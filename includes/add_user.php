<?php
require 'conexao.php';
require 'functions.php';
redirect_if_not_logged_in();
redirect_if_not_admin();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $access_level = $_POST['access_level'];

    $stmt = $pdo->prepare("INSERT INTO users (name, email, password, access_level) VALUES (?, ?, ?, ?)");
    $stmt->execute([$name, $email, $password, $access_level]);

    header('Location: ../admin.php');
    exit();
}
?>
