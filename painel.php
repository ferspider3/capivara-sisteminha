<?php
require 'includes/conexao.php';
require 'includes/functions.php';
redirect_if_not_logged_in();
redirect_if_not_admin();

// Buscar reservas ativas
$active_reservations = $pdo->query("SELECT r.name as room_name, u.name as user_name, res.start_time, res.end_time 
FROM reservations res 
JOIN rooms r ON res.room_id = r.id 
JOIN users u ON res.user_id = u.id 
WHERE res.end_time > NOW() 
ORDER BY res.start_time ASC")->fetchAll();

// Buscar reservas finalizadas
$finished_reservations = $pdo->query("SELECT r.name as room_name, u.name as user_name, res.start_time, res.end_time 
FROM reservations res 
JOIN rooms r ON res.room_id = r.id 
JOIN users u ON res.user_id = u.id 
WHERE res.end_time <= NOW() 
ORDER BY res.start_time DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-br" data-bs-theme="dark">
<head>
    <title>Painel de Reservas</title>
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
        <h2>Painel de Reservas</h2>
        <ul class="nav nav-tabs mt-4" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="active-tab" data-bs-toggle="tab" data-bs-target="#active" type="button" role="tab" aria-controls="active" aria-selected="true">Reservas Ativas</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="finished-tab" data-bs-toggle="tab" data-bs-target="#finished" type="button" role="tab" aria-controls="finished" aria-selected="false">Reservas Finalizadas</button>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="active" role="tabpanel" aria-labelledby="active-tab">
                <!-- Conteúdo para Reservas Ativas -->
                <div class="mt-3">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Sala</th>
                                <th scope="col">Usuário</th>
                                <th scope="col">Data Início</th>
                                <th scope="col">Data Fim</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($active_reservations as $reservation): ?>
                                <tr>
                                    <td><?= htmlspecialchars($reservation['room_name']) ?></td>
                                    <td><?= htmlspecialchars($reservation['user_name']) ?></td>
                                    <td><?= htmlspecialchars($reservation['start_time']) ?></td>
                                    <td><?= htmlspecialchars($reservation['end_time']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="tab-pane fade" id="finished" role="tabpanel" aria-labelledby="finished-tab">
                <!-- Conteúdo para Reservas Finalizadas -->
                <div class="mt-3">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Sala</th>
                                <th scope="col">Usuário</th>
                                <th scope="col">Data Início</th>
                                <th scope="col">Data Fim</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($finished_reservations as $reservation): ?>
                                <tr>
                                    <td><?= htmlspecialchars($reservation['room_name']) ?></td>
                                    <td><?= htmlspecialchars($reservation['user_name']) ?></td>
                                    <td><?= htmlspecialchars($reservation['start_time']) ?></td>
                                    <td><?= htmlspecialchars($reservation['end_time']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
