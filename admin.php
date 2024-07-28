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
        <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addUserModal">Adicionar Usuário</button>
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
                        <tr data-bs-toggle="modal" data-bs-target="#editUserModal" data-id="<?= $user['id'] ?>" data-name="<?= htmlspecialchars($user['name']) ?>" data-email="<?= htmlspecialchars($user['email']) ?>" data-access_level="<?= htmlspecialchars($user['access_level']) ?>">
                            <td><?= htmlspecialchars($user['name']) ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td><?= htmlspecialchars($user['access_level']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Adicionar Usuário -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addUserModalLabel">Adicionar Usuário</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form id="addUserForm" method="post" action="includes/add_user.php">
                <div class="mb-3">
                    <label for="add-name" class="form-label">Nome</label>
                    <input type="text" class="form-control" id="add-name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="add-email" class="form-label">E-mail</label>
                    <input type="email" class="form-control" id="add-email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="add-password" class="form-label">Senha</label>
                    <input type="password" class="form-control" id="add-password" name="password" required autocomplete="new-password">
                </div>
                <div class="mb-3">
                    <label for="add-access_level" class="form-label">Nível de Acesso</label>
                    <select class="form-control" id="add-access_level" name="access_level" required>
                        <option value="user">Usuário</option>
                        <option value="admin">Administrador</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Adicionar</button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Editar Usuário -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editUserModalLabel">Editar Usuário</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form id="editUserForm" method="post" action="includes/edit_user.php">
                <input type="hidden" id="edit-id" name="id">
                <div class="mb-3">
                    <label for="edit-name" class="form-label">Nome</label>
                    <input type="text" class="form-control" id="edit-name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="edit-email" class="form-label">E-mail</label>
                    <input type="email" class="form-control" id="edit-email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="edit-password" class="form-label">Senha</label>
                    <input type="password" class="form-control" id="edit-password" name="password" autocomplete="new-password">
                    <small class="form-text text-muted">Deixe em branco para manter a mesma senha.</small>
                </div>
                <div class="mb-3">
                    <label for="edit-access_level" class="form-label">Nível de Acesso</label>
                    <select class="form-control" id="edit-access_level" name="access_level" required>
                        <option value="user">Usuário</option>
                        <option value="admin">Administrador</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Salvar</button>
            </form>
          </div>
        </div>
      </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#editUserModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var name = button.data('name');
            var email = button.data('email');
            var access_level = button.data('access_level');

            var modal = $(this);
            modal.find('#edit-id').val(id);
            modal.find('#edit-name').val(name);
            modal.find('#edit-email').val(email);
            modal.find('#edit-access_level').val(access_level);
        });
    });
</script>
</body>
</html>
