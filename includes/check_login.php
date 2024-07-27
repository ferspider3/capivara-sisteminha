<?php
session_start();
require 'conexao.php';
require 'functions.php';
require 'messages.php';

// Função para verificar se o valor contém comandos SQL
function contains_sql_commands($value) {
    $sql_commands = ['SELECT', 'INSERT', 'UPDATE', 'DELETE', 'DROP', 'TRUNCATE', 'ALTER', 'CREATE', 'RENAME'];
    foreach ($sql_commands as $command) {
        if (stripos($value, $command) !== false) {
            return true;
        }
    }
    return false;
}

// Função para registrar tentativa de login na sessão
function record_login_attempt_in_session() {
    if (!isset($_SESSION['login_attempts'])) {
        $_SESSION['login_attempts'] = 0;
    }
    $_SESSION['login_attempts']++;
}

// Função para registrar tentativa de login no banco de dados
function record_login_attempt_in_db($ip) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO login_attempts (ip, attempts, last_attempt) VALUES (?, 3, NOW()) ON DUPLICATE KEY UPDATE attempts = 3, last_attempt = NOW()");
    $stmt->execute([$ip]);
}

// Função para verificar tentativas de login
function check_login_attempts($ip) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT attempts, (CASE WHEN last_attempt IS NOT NULL THEN TIMESTAMPDIFF(SECOND, last_attempt, NOW()) ELSE NULL END) AS seconds_since_last_attempt FROM login_attempts WHERE ip = ?");
    $stmt->execute([$ip]);
    return $stmt->fetch();
}

$ip = $_SERVER['REMOTE_ADDR'];
$login_attempts = check_login_attempts($ip);

// Verificar tentativas de login na sessão e no banco de dados
if (isset($_SESSION['login_attempts']) && $_SESSION['login_attempts'] >= 3) {
    if (!$login_attempts || $login_attempts['seconds_since_last_attempt'] >= 30) {
        $_SESSION['login_attempts'] = 0;
    } else {
        add_message("Muitas tentativas de login incorretas. Tente novamente em 30 segundos.", 'danger');
        header('Location: ../login.php');
        exit();
    }
} elseif ($login_attempts && $login_attempts['attempts'] >= 3 && $login_attempts['seconds_since_last_attempt'] < 30) {
    add_message("Muitas tentativas de login incorretas. Tente novamente em 30 segundos.", 'danger');
    header('Location: ../login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Verificação do formato do e-mail
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        add_message("Formato de e-mail inválido.", 'danger');
        header('Location: ../login.php');
        exit();
    }

    // Verificação de comandos SQL
    if (contains_sql_commands($email) || contains_sql_commands($password)) {
        add_message("Entrada inválida.", 'danger');
        header('Location: ../login.php');
        exit();
    }

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['access_level'] = $user['access_level'];
        $_SESSION['login_attempts'] = 0;
        add_message("Login realizado com sucesso.", 'success');
        header('Location: ../index.php');
        exit();
    } else {
        record_login_attempt_in_session();
        if ($_SESSION['login_attempts'] >= 3) {
            record_login_attempt_in_db($ip);
            add_message("Muitas tentativas de login incorretas. Tente novamente em 30 segundos.", 'danger');
        } else {
            add_message("Email ou senha incorretos.", 'danger');
        }
        header('Location: ../login.php');
        exit();
    }
}
?>
