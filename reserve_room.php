<?php
require 'includes/conexao.php';
require 'includes/functions.php';
redirect_if_not_logged_in();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $room_id = $_POST['room_id'];
    $user_id = $_SESSION['user_id'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];

    // Verifica se a sala já está reservada no período solicitado
    $stmt = $pdo->prepare("SELECT * FROM reservations WHERE room_id = ? AND (start_time < ? AND end_time > ?)");
    $stmt->execute([$room_id, $end_time, $start_time]);
    if ($stmt->rowCount() > 0) {
        echo "A sala já está reservada nesse período.";
    } else {
        // Cria a reserva
        $stmt = $pdo->prepare("INSERT INTO reservations (room_id, user_id, start_time, end_time) VALUES (?, ?, ?, ?)");
        $stmt->execute([$room_id, $user_id, $start_time, $end_time]);
        echo "Reserva realizada com sucesso.";
    }
}
$rooms = $pdo->query("SELECT * FROM rooms")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Reservar Sala</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="d-flex">
    <?php include 'includes/menu.php'; ?>
    <div id="content" class="container-fluid p-3">
        <h2>Reservar Sala</h2>
        <form method="post" action="">
            <div class="mb-3">
                <label for="room_id" class="form-label">Sala</label>
                <select class="form-control" id="room_id" name="room_id" required>
                    <?php
                    $rooms = $pdo->query("SELECT * FROM rooms")->fetchAll();
                    foreach ($rooms as $room): ?>
                        <option value="<?= $room['id'] ?>"><?= $room['name'] ?></option>
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
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
