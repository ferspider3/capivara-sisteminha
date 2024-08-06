# README - Sistema de Gestão de Reservas para Capivaras 📜
Este sistema foi desenvolvido para facilitar o gerenciamento de reservas de salas para o bem-estar das capivaras. Ele permite que usuários registrem e gerenciem suas reservas, enquanto administradores têm acesso a ferramentas adicionais para gerenciar usuários e visualizar todas as reservas.

## Funcionalidades ☕️
Login e Registro
- Usuários podem se registrar no sistema.
- Administradores podem adicionar novos usuários.
- Autenticação segura com proteção contra SQL Injection e tentativas de login limitadas.

Gerenciamento de Reservas
- Usuários podem reservar salas, especificando a data e hora de início e término.
- Visualização de reservas ativas e finalizadas.

Administração
- Administradores podem visualizar todos os usuários e reservas.
- Adicionar, editar e remover usuários.

## Estrutura do Banco de Dados 🔥
```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    access_level ENUM('user', 'admin') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE rooms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    room_id INT NOT NULL,
    user_id INT NOT NULL,
    start_time DATETIME NOT NULL,
    end_time DATETIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE login_attempts (
    ip VARCHAR(45) NOT NULL PRIMARY KEY,
    attempts INT NOT NULL,
    last_attempt TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

## Instalação e Configuração ✨
Requisitos
- PHP 7.4+
- MySQL 5.7+
- Servidor Web (Apache, Nginx, etc.)

## Passos para Instalação 🔥
1 - Clonar o Repositório
```bash
git clone https://github.com/seu-usuario/seu-repositorio.git
cd seu-repositorio
```

2 - Configurar o Banco de Dados
- Crie um banco de dados no MySQL:
```sql
CREATE DATABASE capivara;
```
- Cole o SQL do Readme

3 - Configurar o Projeto
- Edite as informações do banco de dados no conexao.php:
```php
<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'capivara');
define('DB_USER', 'seu-usuario');
define('DB_PASS', 'sua-senha');
?>
```

4 - Iniciar o Servidor
- Configure seu servidor web para apontar para a pasta do projeto.
- Certifique-se de que o mod_rewrite está habilitado se estiver usando Apache.

## Utilização

### Login e Registro
- Acesse a página de login através do navegador.
- Registre-se no sistema, fornecendo nome, e-mail e senha.
- Faça login com as credenciais registradas.
<img src="https://github.com/ferspider3/capivara-sisteminha/blob/main/print/1.PNG" alt="Página de Login" min-width="400px" max-width="400px" width="400px">

### Gerenciamento de Reserva
- Navegue até a seção de agendamento.
- Selecione o dia desejado.
- Ao abrir o dia, selecione o horário.
- Escolha a sala e definda a data e hora de início e término, ao qual carregará previamente.
- Clique em "Reservar".
<img src="https://github.com/ferspider3/capivara-sisteminha/blob/main/print/3.PNG" alt="Página de Reserva" min-width="400px" max-width="400px" width="400px">

###  Administração
Após fazer login como administrador, navegue até a seção de administração de usuários.
Adicione novos usuários utilizando o modal de "Adicionar Usuário".
Edite ou remova usuários existentes clicando nos nomes na tabela.

### Segurança
- Proteção contra SQL Injection: As consultas SQL utilizam prepared statements para evitar SQL Injection.
- Limitação de Tentativas de Login: O sistema limita as tentativas de login para proteger contra ataques de força bruta.
- Validação de Entrada: As entradas de formulário são validadas para garantir a integridade dos dados.

## Suporte ❤️
Para mais informações, abra uma issue no repositório ou entre em contato comigo por e-mail: ferspider3@hotmail.com.

## Print do sistema

<img src="https://github.com/ferspider3/capivara-sisteminha/blob/main/print/1.PNG" alt="Página de Login" min-width="400px" max-width="400px" width="400px">
<img src="https://github.com/ferspider3/capivara-sisteminha/blob/main/print/2.PNG" alt="Página Inicial" min-width="400px" max-width="400px" width="400px">
<img src="https://github.com/ferspider3/capivara-sisteminha/blob/main/print/3.PNG" alt="Página de Reserva" min-width="400px" max-width="400px" width="400px">
<img src="https://github.com/ferspider3/capivara-sisteminha/blob/main/print/4.PNG" alt="Página de Reserva - Geral" min-width="400px" max-width="400px" width="400px">
<img src="https://github.com/ferspider3/capivara-sisteminha/blob/main/print/5.PNG" alt="Página das Salas" min-width="400px" max-width="400px" width="400px">
<img src="https://github.com/ferspider3/capivara-sisteminha/blob/main/print/6.PNG" alt="Página Administrativa" min-width="400px" max-width="400px" width="400px">
