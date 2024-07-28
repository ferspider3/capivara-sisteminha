<?php
require 'conexao.php';
require 'functions.php';
redirect_if_not_logged_in();
redirect_if_not_admin();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $access_level = $_POST['access_level'];

    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, password = ?, access_level = ? WHERE id = ?");
        $stmt->execute([$name, $email, $password, $access_level, $id]);
    } else {
        $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, access_level = ? WHERE id = ?");
        $stmt->execute([$name, $email, $access_level, $id]);
    }

    header('Location: ../admin.php');
    exit();
}
?>
