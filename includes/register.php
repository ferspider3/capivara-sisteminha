<?php
session_start();
require 'conexao.php';
require 'functions.php';
require 'messages.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $passwordConfirm = $_POST['password-confirm'];

    // Verificação do formato do e-mail
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        add_message("Formato de e-mail inválido.", 'danger');
        header('Location: ../login.php');
        exit();
    }

    // Verificação de comandos SQL
    if (contains_sql_commands($name) || contains_sql_commands($email) || contains_sql_commands($password)) {
        add_message("Entrada inválida.", 'danger');
        header('Location: ../login.php');
        exit();
    }

    // Verificação se as senhas coincidem
    if ($password !== $passwordConfirm) {
        add_message("As senhas não coincidem.", 'danger');
        header('Location: ../login.php');
        exit();
    }

    // Hash da senha
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // Inserção no banco de dados
    try {
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $passwordHash]);
        add_message("Registro realizado com sucesso.", 'success');
        header('Location: ../login.php');
        exit();
    } catch (PDOException $e) {
        add_message("Erro ao registrar usuário: " . $e->getMessage(), 'danger');
        header('Location: ../login.php');
        exit();
    }
}
?>
