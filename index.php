<?php
session_start();
require 'includes/functions.php';
require 'includes/messages.php';
require 'includes/conexao.php';
redirect_if_not_logged_in();

// Função para buscar dados de reservas
function get_reservas($pdo, $period) {
    $sql = "SELECT COUNT(*) FROM reservations WHERE $period";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchColumn();
}

// Função para contar a quantidade de salas cadastradas
function get_salas_totais($pdo) {
    $sql = "SELECT COUNT(*) FROM rooms";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchColumn();
}

// Função para buscar as últimas 5 reservas ativas
function get_last_active_reservations($pdo) {
    $sql = "SELECT r.*, ro.name AS room_name FROM reservations r JOIN rooms ro ON r.room_id = ro.id WHERE end_time >= NOW() ORDER BY start_time DESC LIMIT 5";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Função para buscar as últimas 5 reservas finalizadas
function get_last_finished_reservations($pdo) {
    $sql = "SELECT r.*, ro.name AS room_name FROM reservations r JOIN rooms ro ON r.room_id = ro.id WHERE end_time < NOW() ORDER BY end_time DESC LIMIT 5";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Reservas ativas
$reservas_ativas_hoje = get_reservas($pdo, "DATE(start_time) = CURDATE() AND end_time >= NOW()");
$reservas_ativas_semana = get_reservas($pdo, "YEARWEEK(start_time, 1) = YEARWEEK(CURDATE(), 1) AND end_time >= NOW()");
$reservas_ativas_mes = get_reservas($pdo, "MONTH(start_time) = MONTH(CURDATE()) AND YEAR(start_time) = YEAR(CURDATE()) AND end_time >= NOW()");

// Reservas finalizadas
$reservas_finalizadas_hoje = get_reservas($pdo, "DATE(end_time) = CURDATE() AND end_time < NOW()");
$reservas_finalizadas_semana = get_reservas($pdo, "YEARWEEK(end_time, 1) = YEARWEEK(CURDATE(), 1) AND end_time < NOW()");
$reservas_finalizadas_mes = get_reservas($pdo, "MONTH(end_time) = MONTH(CURDATE()) AND YEAR(end_time) = YEAR(CURDATE()) AND end_time < NOW()");

// Salas totais
$salas_totais = get_salas_totais($pdo);

// Busca as últimas 5 reservas ativas e finalizadas
$active_reservations = get_last_active_reservations($pdo);
$finished_reservations = get_last_finished_reservations($pdo);

// Função para formatar data e hora
function format_date_br($datetime) {
    $date = new DateTime($datetime);
    return $date->format('d/m/Y H:i:s');
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
        <div class="d-flex align-items-center mb-4">
            <img src="images/favicon32.png" class="flex-shrink-0" alt="capivara">
            <div class="flex-grow-1 ms-3">
                <h5 class="mt-0">Bem-vindo, <?= htmlspecialchars($_SESSION['name']) ?>!</h5>
                <p>Aqui você pode gerenciar suas reservas e visualizar informações importantes.</p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-3">
                <div class="card text-bg-info h-100">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-3">
                                <i class="fa-solid fa-users-between-lines fa-3x"></i>
                            </div>
                            <div class="col-9 text-end">
                                <h5 class="card-title"><?= $reservas_ativas_hoje ?></h5>
                                <p class="card-text">Reservas Ativas</p>
                                <h5 class="card-title"><?= $reservas_ativas_semana ?></h5>
                                <p class="card-text">Essa Semana</p>
                                <h5 class="card-title"><?= $reservas_ativas_mes ?></h5>
                                <p class="card-text">Este Mês</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body d-flex align-items-end">
                        <a href="reserve_room.php" class="btn btn-info w-100">Detalhes <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-3">
                <div class="card text-bg-info h-100">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-3">
                                <i class="fa-solid fa-user-clock fa-3x"></i>
                            </div>
                            <div class="col-9 text-end">
                                <h5 class="card-title"><?= $reservas_finalizadas_hoje ?></h5>
                                <p class="card-text">Reservas Finalizadas</p>
                                <h5 class="card-title"><?= $reservas_finalizadas_semana ?></h5>
                                <p class="card-text">Essa Semana</p>
                                <h5 class="card-title"><?= $reservas_finalizadas_mes ?></h5>
                                <p class="card-text">Este Mês</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body d-flex align-items-end">
                        <a href="reserve_room.php" class="btn btn-info w-100">Detalhes <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-3">
                <div class="card text-bg-info h-100">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-3">
                                <i class="fa-solid fa-universal-access fa-3x"></i>
                            </div>
                            <div class="col-9 text-end">
                                <h5 class="card-title"><?= $salas_totais ?></h5>
                                <p class="card-text">Salas Totais</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body d-flex align-items-end">
                        <a href="reserve_room.php" class="btn btn-info w-100">Detalhes <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div>
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
                                <th scope="col">Data Início</th>
                                <th scope="col">Data Fim</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($active_reservations as $reservation): ?>
                                <tr>
                                    <td><?= htmlspecialchars($reservation['room_name']) ?></td>
                                    <td><?= htmlspecialchars(format_date_br($reservation['start_time'])) ?></td>
                                    <td><?= htmlspecialchars(format_date_br($reservation['end_time'])) ?></td>
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
                                <th scope="col">Data Início</th>
                                <th scope="col">Data Fim</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($finished_reservations as $reservation): ?>
                                <tr>
                                    <td><?= htmlspecialchars($reservation['room_name']) ?></td>
                                    <td><?= htmlspecialchars(format_date_br($reservation['start_time'])) ?></td>
                                    <td><?= htmlspecialchars(format_date_br($reservation['end_time'])) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
