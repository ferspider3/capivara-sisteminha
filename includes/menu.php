<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

<div id="sidebar" class="d-flex flex-column flex-shrink-0 p-3 text-bg-dark" style="width: 280px;">
    <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
        <svg class="bi pe-none me-2" width="40" height="32"><use xlink:href="#bootstrap"/></svg>
        <span class="fs-4">Capivarinha</span>
    </a>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a href="index.php" class="nav-link <?= $current_page == 'index.php' ? 'active' : '' ?>" aria-current="page">
                <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#home"/></svg>
                Início
            </a>
        </li>
        <li>
            <a href="painel.php" class="nav-link text-white <?= $current_page == 'painel.php' ? 'active' : '' ?>">
                <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#speedometer2"/></svg>
                Painel
            </a>
        </li>
        <li>
            <a href="reserve_room.php" class="nav-link text-white <?= $current_page == 'reserve_room.php' ? 'active' : '' ?>">
                <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#table"/></svg>
                Agendamento
            </a>
        </li>
        <?php if (is_admin()): ?>
            <li>
                <a href="manage_rooms.php" class="nav-link text-white <?= $current_page == 'manage_rooms.php' ? 'active' : '' ?>">
                    <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#grid"/></svg>
                    Gerenciar Salas
                </a>
            </li>
            <li>
                <a href="admin.php" class="nav-link text-white <?= $current_page == 'admin.php' ? 'active' : '' ?>">
                    <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#people-circle"/></svg>
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
