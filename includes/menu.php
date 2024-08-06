<?php
require 'conexao.php';
require 'functions.php';
$current_page = basename($_SERVER['PHP_SELF']);
?>

<div id="sidebar" class="d-flex flex-column flex-shrink-0 p-3 bg-body-tertiary" style="width: 280px;">
    <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
        <svg class="bi pe-none me-2" width="40" height="32"><use xlink:href="#bootstrap"/></svg>
        <span class="fs-4">Capivarinha</span>
    </a>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto">
        <li>
            <a href="index.php" class="nav-link text-white <?= $current_page == 'index.php' ? 'active' : '' ?>">
                <i class="fa-solid fa-house"></i>
                Início
            </a>
        </li>
        <li>
            <a href="reserve_room.php" class="nav-link text-white <?= $current_page == 'reserve_room.php' ? 'active' : '' ?>">
                <i class="fa-solid fa-calendar-days"></i>
                Agendamento
            </a>
        </li>
        <?php if (is_admin()): ?>
            <li>
                <a href="painel.php" class="nav-link text-white <?= $current_page == 'painel.php' ? 'active' : '' ?>">
                    <i class="fa-solid fa-table-columns"></i>
                    Painel
                </a>
            </li>
            <li>
                <a href="manage_rooms.php" class="nav-link text-white <?= $current_page == 'manage_rooms.php' ? 'active' : '' ?>">
                    <i class="fa-solid fa-book"></i>
                    Gerenciar Salas
                </a>
            </li>
            <li>
                <a href="admin.php" class="nav-link text-white <?= $current_page == 'admin.php' ? 'active' : '' ?>">
                    <i class="fa-solid fa-users"></i>
                    Usuários
                </a>
            </li>
        <?php endif; ?>
    </ul>
    <hr>
    <div class="dropdown">
        <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="images/favicon32.png" alt="" width="32" height="32" class="rounded-circle me-2">
            <strong><?= $_SESSION['name'] ?></strong>
        </a>
        <ul class="dropdown-menu dropdown-menu-dark text-small shadow">
            <li><a class="dropdown-item" href="#">Editar</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="logout.php">Sair</a></li>
        </ul>
    </div>
</div>
