<?php
require 'includes/conexao.php';
require 'includes/functions.php';
redirect_if_not_logged_in();

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $room_id = $_POST['room_id'];
    $user_id = $_SESSION['user_id'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];

    // Verifica se a sala já está reservada no período solicitado
    $stmt = $pdo->prepare("SELECT * FROM reservations WHERE room_id = ? AND (start_time < ? AND end_time > ?)");
    $stmt->execute([$room_id, $end_time, $start_time]);
    if ($stmt->rowCount() > 0) {
        $message = "A sala já está reservada nesse período.";
    } else {
        // Cria a reserva
        $stmt = $pdo->prepare("INSERT INTO reservations (room_id, user_id, start_time, end_time) VALUES (?, ?, ?, ?)");
        $stmt->execute([$room_id, $user_id, $start_time, $end_time]);
        $message = "Reserva realizada com sucesso.";
    }
}

$user_id = $_SESSION['user_id'];

// Buscar reservas ativas
$active_reservations = $pdo->prepare("SELECT r.name as room_name, res.start_time, res.end_time FROM reservations res JOIN rooms r ON res.room_id = r.id WHERE res.user_id = ? AND res.end_time > NOW() ORDER BY res.start_time ASC");
$active_reservations->execute([$user_id]);
$active_reservations = $active_reservations->fetchAll();

// Buscar reservas finalizadas
$finished_reservations = $pdo->prepare("SELECT r.name as room_name, res.start_time, res.end_time FROM reservations res JOIN rooms r ON res.room_id = r.id WHERE res.user_id = ? AND res.end_time <= NOW() ORDER BY res.start_time DESC");
$finished_reservations->execute([$user_id]);
$finished_reservations = $finished_reservations->fetchAll();

$rooms = $pdo->query("SELECT * FROM rooms")->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-br" data-bs-theme="dark">
<head>
    <title>Reservar Sala</title>
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
        <h2>Reservar Sala</h2>
        <?php if ($message): ?>
            <div class="alert <?= strpos($message, 'sucesso') !== false ? 'alert-success' : 'alert-danger' ?>" role="alert">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>
        <form method="post" action="">
            <div class="mb-3">
                <label for="room_id" class="form-label">Sala</label>
                <select class="form-control" id="room_id" name="room_id" required>
                    <?php foreach ($rooms as $room): ?>
                        <option value="<?= $room['id'] ?>"><?= htmlspecialchars($room['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="start_time" class="form-label">Data e Hora de Início</label>
                <input type="datetime-local" class="form-control" id="start_time" name="start_time" required>
            </div>
            <div class="mb-3">
                <label for="end_time" class="form-label">Data e Hora de Término</label>
                <input type="datetime-local" class="form-control" id="end_time" name="end_time" required>
            </div>
            <button type="submit" class="btn btn-primary">Reservar</button>
        </form>
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
                                <th scope="col">Data Início</th>
                                <th scope="col">Data Fim</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($finished_reservations as $reservation): ?>
                                <tr>
                                    <td><?= htmlspecialchars($reservation['room_name']) ?></td>
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
