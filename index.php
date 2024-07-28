<?php
session_start();
require 'includes/conexao.php';  // Inclua a conexão com o banco de dados
require 'includes/functions.php';
require 'includes/messages.php';
redirect_if_not_logged_in();

// Consultas ao banco de dados
try {
    // Reservas Ativas (ainda não iniciadas)
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM reservations WHERE start_time >= NOW() AND DATE(start_time) = CURDATE()");
    $stmt->execute();
    $reservas_ativas_hoje = $stmt->fetchColumn();

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM reservations WHERE start_time >= NOW() AND YEARWEEK(start_time, 1) = YEARWEEK(CURDATE(), 1)");
    $stmt->execute();
    $reservas_ativas_semana = $stmt->fetchColumn();

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM reservations WHERE start_time >= NOW() AND MONTH(start_time) = MONTH(CURDATE()) AND YEAR(start_time) = YEAR(CURDATE())");
    $stmt->execute();
    $reservas_ativas_mes = $stmt->fetchColumn();

    // Reservas Finalizadas (Considerando end_time)
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM reservations WHERE end_time < NOW() AND DATE(end_time) = CURDATE()");
    $stmt->execute();
    $reservas_finalizadas_hoje = $stmt->fetchColumn();

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM reservations WHERE end_time < NOW() AND YEARWEEK(end_time, 1) = YEARWEEK(CURDATE(), 1)");
    $stmt->execute();
    $reservas_finalizadas_semana = $stmt->fetchColumn();

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM reservations WHERE end_time < NOW() AND MONTH(end_time) = MONTH(CURDATE()) AND YEAR(end_time) = YEAR(CURDATE())");
    $stmt->execute();
    $reservas_finalizadas_mes = $stmt->fetchColumn();

    // Salas Totais
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM rooms");
    $stmt->execute();
    $salas_totais = $stmt->fetchColumn();
} catch (PDOException $e) {
    add_message("Erro ao buscar informações: " . $e->getMessage(), 'danger');
}
?>
<!DOCTYPE html>
<html lang="pt-br" data-bs-theme="dark">
<head>
    <title>Página Inicial</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" type="image/x-icon" href="images/favicon16.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
<?php include 'includes/toasts.php'; ?>
<div class="d-flex">
    <?php include 'includes/menu.php'; ?>
    <div id="content" class="container-fluid p-3">
        <div class="d-flex align-items-center">
            <img src="images/favicon32.png" class="flex-shrink-0" alt="capivara">
            <div class="flex-grow-1 ms-3">
                <h5 class="mt-0">Bem-vindo, <?= htmlspecialchars($_SESSION['name']) ?>!</h5>
                <p>Aqui você pode gerenciar suas reservas e visualizar informações importantes.</p>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="card mb-3" style="width: 18rem;">
                        <div class="card-body">
                            <h5 class="card-title">Reservas Ativas</h5>
                            <i class="fa-solid fa-users-between-lines"></i>
                            <p class="card-text">Hoje <span class="badge text-bg-secondary"><?= $reservas_ativas_hoje ?></span></p>
                            <p class="card-text">Essa semana <span class="badge text-bg-secondary"><?= $reservas_ativas_semana ?></span></p>
                            <p class="card-text">Esse mês <span class="badge text-bg-secondary"><?= $reservas_ativas_mes ?></span></p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card mb-3" style="width: 18rem;">
                        <div class="card-body">
                            <h5 class="card-title">Reservas Finalizadas</h5>
                            <i class="fa-solid fa-user-clock"></i>
                            <p class="card-text">Hoje <span class="badge text-bg-secondary"><?= $reservas_finalizadas_hoje ?></span></p>
                            <p class="card-text">Essa semana <span class="badge text-bg-secondary"><?= $reservas_finalizadas_semana ?></span></p>
                            <p class="card-text">Esse mês <span class="badge text-bg-secondary"><?= $reservas_finalizadas_mes ?></span></p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card mb-3" style="width: 18rem;">
                        <div class="card-body">
                            <h5 class="card-title">Salas Totais</h5>
                            <i class="fa-solid fa-universal-access"></i>
                            <p class="card-text">Total <span class="badge text-bg-secondary"><?= $salas_totais ?></span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
