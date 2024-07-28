<?php
require 'includes/conexao.php';
require 'includes/functions.php';
redirect_if_not_logged_in();
redirect_if_not_admin();

// Buscar todos os usuários
$users = $pdo->query("SELECT * FROM users ORDER BY name ASC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-br" data-bs-theme="dark">
<head>
    <title>Administração de Usuários</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" type="image/x-icon" href="images/favicon16.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .container-main {
            margin-left: 280px; /* Ajuste conforme a largura do menu lateral */
        }
        .card {
            margin-bottom: 1rem;
        }
        .nav-tabs {
            margin-top: 2rem;
        }
    </style>
</head>
<body>
    <?php include 'includes/menu.php'; ?>
    <div class="container-main p-3">
        <h2>Administração de Usuários</h2>
        <div class="mt-4">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th scope="col">Nome</th>
                        <th scope="col">E-mail</th>
                        <th scope="col">Nível de Acesso</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= htmlspecialchars($user['name']) ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td><?= htmlspecialchars($user['access_level']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
