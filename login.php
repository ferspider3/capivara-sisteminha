<?php
// login.php
require 'includes/conexao.php';
require 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['access_level'] = $user['access_level'];
        header('Location: index.php');
        exit();
    } else {
        $login_error = "Email ou senha incorretos.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="d-flex vh-100">
        <div class="container d-flex justify-content-center align-items-center">
            <div class="text-center w-50">
                <h2>Login</h2>
                <form class="form-signin w-100 m-auto" method="post" action="">
                    <?php if (!empty($login_error)): ?>
                        <div class="alert alert-danger" role="alert">
                            <?= $login_error ?>
                        </div>
                    <?php endif; ?>
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                        <label for="email" class="form-label fw-normal">Email</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Senha" required>
                        <label for="password" class="form-label fw-normal">Senha</label>
                    </div>
                    <button type="submit" class="w-100 btn btn-lg btn-primary">Login</button>
                </form>
            </div>
        </div>
    </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
