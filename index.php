<?php
session_start();
require 'includes/functions.php';
require 'includes/messages.php';
redirect_if_not_logged_in();
?>
<!DOCTYPE html>
<html lang="pt-br" data-bs-theme="dark">
<head>
    <title>Página Inicial</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" type="image/x-icon" href="images/favicon16.png">
</head>
<body>
<?php include 'includes/toasts.php'; ?>
<div class="d-flex">
    <?php include 'includes/menu.php'; ?>
    <div id="content" class="container-fluid p-3">
        <h2>Bem-vindo, <?= htmlspecialchars($_SESSION['name']) ?>!</h2>
        <p>Aqui você pode gerenciar suas reservas e visualizar informações importantes.</p>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
