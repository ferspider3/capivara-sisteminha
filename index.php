<?php
require 'includes/functions.php';
redirect_if_not_logged_in();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Página Inicial</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="d-flex">
    <?php include 'includes/menu.php'; ?>
    <div id="content" class="container-fluid p-3">
        <h2>Bem-vindo, <?= $_SESSION['name'] ?>!</h2>
        <p>Aqui você pode gerenciar suas reservas e visualizar informações importantes.</p>
        <!-- Conteúdo adicional pode ser adicionado aqui -->
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
