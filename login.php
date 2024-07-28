<?php
session_start();
require 'includes/functions.php';
require 'includes/messages.php';
?>
<!DOCTYPE html>
<html lang="pt-br" data-bs-theme="dark">
<head>
    <title>Acesso</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" type="image/x-icon" href="images/favicon16.png">
</head>
<body>
    <div class="d-flex vh-100">
        <div class="container text-center d-flex justify-content-center align-items-center">
            <div class="row justify-content-md-center text-center w-100">
                <div class="col-md-4 register-section">
                    <p>Facilite o cuidado e o bem-estar das nossas queridas capivarinhas.</p> 
                    <p>Ao se registrar, você terá acesso a ferramentas simples e eficientes para organizar e monitorar as reservas de espaços destinados a elas.</p>
                    <p>Garantir o melhor ambiente para as capivaras nunca foi tão fácil!</p>
                    <div class="register-button-container button-align">
                        <button type="button" class="btn btn-success register-button" data-bs-toggle="modal" data-bs-target="#registro">Registrar</button>
                    </div>
                </div>
                <div class="col-md-1 divider-container">
                    <figure class="figure">
                        <img src="images/login.png" class="figure-img img-fluid rounded-circle figure-login" alt="capivara">
                    </figure>
                    <div class="divider"></div>
                </div>
                <div class="col-md-4 login-section">
                    <h2 class="mb-4">Login</h2>
                    <form class="form-signin login-form w-100 m-auto" method="post" action="includes/check_login.php">
                        <?php include 'includes/toasts.php'; ?>
                        <div class="form-floating form-email">
                            <input type="email" class="form-control" id="email" name="email" placeholder="Email" required autocomplete="email">
                            <label for="email" class="form-label fw-normal">Endereço de E-mail</label>
                        </div>
                        <div class="form-floating form-senha">
                            <input type="password" class="form-control" id="password" name="password" placeholder="Senha" required autocomplete="current-password">
                            <label for="password" class="form-label fw-normal">Senha</label>
                        </div>
                        <div class="d-flex justify-content-center mt-3 button-align">
                            <button type="submit" class="btn btn-primary w-50">Login</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Registro -->
    <div class="modal fade" id="registro" tabindex="-1" aria-labelledby="registrar" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Registro</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form id="registerForm" method="post" action="includes/register.php">
                <div class="mb-3">
                    <label for="name" class="form-label">Nome</label>
                    <input type="text" class="form-control" id="name" name="name" required autocomplete="name">
                </div>
                <div class="mb-3">
                    <label for="register-email" class="form-label">E-mail</label>
                    <input type="email" class="form-control" id="register-email" name="email" required autocomplete="email">
                </div>
                <div class="mb-3">
                    <label for="register-password" class="form-label">Senha</label>
                    <input type="password" class="form-control" id="register-password" name="password" required autocomplete="new-password">
                </div>
                <div class="mb-3">
                    <label for="password-confirm" class="form-label">Repetir Senha</label>
                    <input type="password" class="form-control" id="password-confirm" name="password-confirm" required autocomplete="new-password">
                </div>
                <div class="mb-3" id="password-requirements">
                    <p>Requisitos de senha:</p>
                    <ul>
                        <li id="length" class="invalid">Mínimo 8 caracteres</li>
                        <li id="uppercase" class="invalid">Mínimo 1 letra maiúscula</li>
                        <li id="lowercase" class="invalid">Mínimo 1 letra minúscula</li>
                        <li id="special" class="invalid">Mínimo 1 caractere especial</li>
                        <li id="number" class="invalid">Mínimo 1 número</li>
                    </ul>
                </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Fechar</button>
            <button type="button" class="btn btn-primary" id="registerButton" disabled>Registrar</button>
          </div>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#register-password, #password-confirm').on('keyup', function() {
                var password = $('#register-password').val();
                var confirmPassword = $('#password-confirm').val();
                var requirementsMet = true;

                // Verifica se a senha atende aos requisitos
                if (password.length >= 8) {
                    $('#length').removeClass('invalid').addClass('valid');
                } else {
                    $('#length').removeClass('valid').addClass('invalid');
                    requirementsMet = false;
                }

                if (/[A-Z]/.test(password)) {
                    $('#uppercase').removeClass('invalid').addClass('valid');
                } else {
                    $('#uppercase').removeClass('valid').addClass('invalid');
                    requirementsMet = false;
                }

                if (/[a-z]/.test(password)) {
                    $('#lowercase').removeClass('invalid').addClass('valid');
                } else {
                    $('#lowercase').removeClass('valid').addClass('invalid');
                    requirementsMet = false;
                }

                if (/[0-9]/.test(password)) {
                    $('#number').removeClass('invalid').addClass('valid');
                } else {
                    $('#number').removeClass('valid').addClass('invalid');
                    requirementsMet = false;
                }

                if (/[^A-Za-z0-9]/.test(password)) {
                    $('#special').removeClass('invalid').addClass('valid');
                } else {
                    $('#special').removeClass('valid').addClass('invalid');
                    requirementsMet = false;
                }

                // Verifica se as senhas são iguais e não estão vazias
                if (password && password === confirmPassword) {
                    $('#password-confirm').removeClass('is-invalid').addClass('is-valid');
                } else {
                    $('#password-confirm').removeClass('is-valid').addClass('is-invalid');
                    requirementsMet = false;
                }

                // Ativa/desativa o botão de registro com base nos requisitos
                if (requirementsMet) {
                    $('#registerButton').prop('disabled', false);
                } else {
                    $('#registerButton').prop('disabled', true);
                }
            });

            $('#registerButton').click(function() {
                if ($('#registerForm')[0].checkValidity()) {
                    $('#registerForm').submit();
                }
            });
        });
    </script>
</body>
</html>
