<?php
require 'includes/conexao.php';
require 'includes/functions.php';
redirect_if_not_admin();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['create'])) {
        $name = $_POST['name'];
        $capacity = $_POST['capacity'];
        $location = $_POST['location'];
        $stmt = $pdo->prepare("INSERT INTO rooms (name, capacity, location) VALUES (?, ?, ?)");
        $stmt->execute([$name, $capacity, $location]);
    } elseif (isset($_POST['update'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $capacity = $_POST['capacity'];
        $location = $_POST['location'];
        $stmt = $pdo->prepare("UPDATE rooms SET name = ?, capacity = ?, location = ? WHERE id = ?");
        $stmt->execute([$name, $capacity, $location, $id]);
    } elseif (isset($_POST['delete'])) {
        $id = $_POST['id'];
        $stmt = $pdo->prepare("DELETE FROM rooms WHERE id = ?");
        $stmt->execute([$id]);
    }
}
$rooms = $pdo->query("SELECT * FROM rooms")->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-br" data-bs-theme="dark">
<head>
    <title>Gerenciar Salas</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" type="image/x-icon" href="images/favicon16.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
<div class="d-flex">
    <?php include 'includes/menu.php'; ?>
    <div id="content" class="container-fluid p-3">
        <h2>Gerenciar Salas</h2>
        <?php if (is_admin()): ?>
        <form method="post" action="">
            <div class="mb-3">
                <label for="name" class="form-label">Nome da Sala</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="capacity" class="form-label">Capacidade</label>
                <input type="number" class="form-control" id="capacity" name="capacity" required>
            </div>
            <div class="mb-3">
                <label for="location" class="form-label">Localização</label>
                <input type="text" class="form-control" id="location" name="location">
            </div>
            <button type="submit" name="create" class="btn btn-primary">Criar Sala</button>
        </form>
        <?php endif; ?>
        <hr>
        <h3>Salas Existentes</h3>
        <ul class="list-group">
            <?php
            $rooms = $pdo->query("SELECT * FROM rooms")->fetchAll();
            foreach ($rooms as $room): ?>
                <li class="list-group-item">
                    <form method="post" action="">
                        <input type="hidden" name="id" value="<?= $room['id'] ?>">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nome da Sala</label>
                            <input type="text" class="form-control" name="name" value="<?= $room['name'] ?>" required <?= !is_admin() ? 'readonly' : '' ?>>
                        </div>
                        <div class="mb-3">
                            <label for="capacity" class="form-label">Capacidade</label>
                            <input type="number" class="form-control" name="capacity" value="<?= $room['capacity'] ?>" required <?= !is_admin() ? 'readonly' : '' ?>>
                        </div>
                        <div class="mb-3">
                            <label for="location" class="form-label">Localização</label>
                            <input type="text" class="form-control" name="location" value="<?= $room['location'] ?>" <?= !is_admin() ? 'readonly' : '' ?>>
                        </div>
                        <?php if (is_admin()): ?>
                            <button type="submit" name="update" class="btn btn-success">Atualizar</button>
                            <button type="submit" name="delete" class="btn btn-danger">Excluir</button>
                        <?php endif; ?>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
