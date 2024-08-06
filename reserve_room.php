<?php
require 'includes/conexao.php';
require 'includes/functions.php';
require 'includes/messages.php';
redirect_if_not_logged_in();

date_default_timezone_set('America/Sao_Paulo'); // Define a timezone

// Verifica se há salas no banco de dados
$stmt = $pdo->query("SELECT * FROM rooms");
$rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $reservation_id = $_POST['reservation_id'] ?? null;
    $room_id = $_POST['room_id'];
    $user_id = $_SESSION['user_id'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];

    // Verifica se a sala já está reservada no período solicitado
    $stmt = $pdo->prepare("SELECT * FROM reservations WHERE room_id = ? AND (start_time < ? AND end_time > ?) AND id != ?");
    $stmt->execute([$room_id, $end_time, $start_time, $reservation_id]);
    if ($stmt->rowCount() > 0) {
        add_message("A sala já está reservada nesse período.", 'danger');
    } else {
        if ($reservation_id) {
            // Atualiza a reserva existente
            $stmt = $pdo->prepare("UPDATE reservations SET room_id = ?, start_time = ?, end_time = ? WHERE id = ?");
            $stmt->execute([$room_id, $start_time, $end_time, $reservation_id]);
            add_message("Reserva atualizada com sucesso.", 'success');
        } else {
            // Cria a nova reserva
            $stmt = $pdo->prepare("INSERT INTO reservations (room_id, user_id, start_time, end_time) VALUES (?, ?, ?, ?)");
            $stmt->execute([$room_id, $user_id, $start_time, $end_time]);
            add_message("Reserva realizada com sucesso.", 'success');
        }
    }
    header('Location: reserve_room.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <title>Reservar Sala</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" type="image/x-icon" href="images/favicon16.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
    <style>
        #calendar {
            width: 100%;
            padding: 10px;
        }
        .fc {
            max-width: 100%;
            margin: 0 auto;
        }
        .content-container {
            flex-grow: 1;
            overflow: hidden;
        }
        .container-fluid {
            padding: 0;
        }
        .calendar-container {
            max-width: 58%;
            margin-left: auto;
            margin-right: auto;
            padding-right: 40px;
            background-color: white;
            border: 1px solid #ced4da;
            border-radius: 0.55rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 0px;
        }
        .card-calendar {
            margin-top: 10px;
            background-color: white;
        }
        .fc .fc-daygrid-event {
            white-space: normal !important;
            overflow: hidden;
            text-overflow: ellipsis;
            height: auto;
            line-height: 1.2;
            color: white;
        }
        .fc-daygrid-day-number {
            text-decoration: none;
        }
        .fc-highlight {
            background-color: #00CCCC !important;
        }
        .override-bg-opacity {
            --bs-bg-opacity: 1 !important;
            background-color: #2B3035 !important;
        }
    </style>
</head>
<body>
<nav>
    <?php include 'includes/menu.php'; ?>
</nav>
<div class="d-flex">
    <div class="content-container">
        <div class="container-fluid p-4">
            <div class="row">
                <div class="col">
                    <?php include 'includes/toasts.php'; ?>
                    <div class="calendar-container card card-calendar border-success">
                        <div class="card-header bg-success text-white">Calendário de Reservas</div>
                        <div class="card-body">
                            <div id="calendar"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para adicionar/editar reserva -->
<div class="modal fade" id="reserveModal" tabindex="-1" aria-labelledby="reserveModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reserveModalLabel">Reservar Sala</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="reserveForm" method="post" action="">
                    <input type="hidden" id="reservation_id" name="reservation_id">
                    <div class="mb-3">
                        <label for="room_id" class="form-label">Sala</label>
                        <select class="form-control" id="room_id" name="room_id" required>
                            <?php foreach ($rooms as $room): ?>
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
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            selectable: true,
            editable: true,
            locale: 'pt-br',
            buttonText: {
                today: 'hoje',
                month: 'mês',
                week: 'semana',
                day: 'dia',
                list: 'lista'
            },
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
            },
            select: function(info) {
                if (calendar.view.type === 'timeGridDay') {
                    $('#start_time').val(info.startStr.slice(0, 16));
                    $('#end_time').val(info.endStr.slice(0, 16));
                    $('#reservation_id').val('');
                    $('#reserveModal').modal('show');
                }
            },
            events: [
                <?php
                $stmt = $pdo->query("SELECT r.*, ro.name AS room_name FROM reservations r JOIN rooms ro ON r.room_id = ro.id");
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)):
                    $color = (new DateTime($row['end_time'])) < new DateTime() ? 'bg-danger' : 'bg-primary';
                ?>
                {
                    id: '<?= $row['id'] ?>',
                    title: '<?= htmlspecialchars($row['room_name']) ?>',
                    start: '<?= (new DateTime($row['start_time']))->format(DateTime::ATOM) ?>',
                    end: '<?= (new DateTime($row['end_time']))->format(DateTime::ATOM) ?>',
                    className: '<?= $color ?>',
                    extendedProps: {
                        room_id: '<?= $row['room_id'] ?>'
                    }
                },
                <?php endwhile; ?>
            ],
            dateClick: function(info) {
                calendar.changeView('timeGridDay', info.dateStr);
            },
            eventClick: function(info) {
                var eventObj = info.event;
                $('#reservation_id').val(eventObj.id);
                $('#room_id').val(eventObj.extendedProps.room_id);
                $('#start_time').val(new Date(eventObj.start).toISOString().slice(0, 16));
                $('#end_time').val(new Date(eventObj.end).toISOString().slice(0, 16));
                $('#reserveModal').modal('show');
                // Fecha o popover ao abrir o modal
                $('[data-bs-toggle="popover"]').popover('hide');
            },
            eventDrop: function(info) {
                $('#reservation_id').val(info.event.id);
                $('#room_id').val(info.event.extendedProps.room_id);
                $('#start_time').val(new Date(info.event.start).toISOString().slice(0, 16));
                $('#end_time').val(new Date(info.event.end).toISOString().slice(0, 16));
                $('#reserveModal').modal('show');
            },
            dayMaxEventRows: 3,
        });

        calendar.render();

        // Aplicando estilos específicos apenas para esta página
        document.querySelector('#sidebar').classList.add('override-bg-opacity');
        document.querySelector('body').style.backgroundColor = '#212529';

        // Ajusta a cor das linhas
        var hrElements = document.querySelectorAll('#sidebar hr');
        hrElements.forEach(function(hr) {
            hr.style.borderTop = '1px solid #585D62';
        });

        // Remove a classe que define a opacidade de fundo
        document.querySelector('#sidebar').classList.remove('bg-body-tertiary');

        // Desativa a propriedade background-color: rgba(var(--bs-tertiary-bg-rgb),var(--bs-bg-opacity))!important;
        document.querySelector('#sidebar').style.setProperty('--bs-bg-opacity', '1', 'important');
        document.querySelector('#sidebar').style.setProperty('background-color', '#2B3035', 'important');

    });
</script>
</body>
</html>
