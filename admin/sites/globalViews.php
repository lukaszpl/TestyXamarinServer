<?php
function drawMenu()
{
?>
    <aside class="mdc-drawer">
        <div class="mdc-drawer__header">
            <h3 class="mdc-drawer__title">Panel administratora</h3>
            <h6 class="mdc-drawer__subtitle">v.1.0.0</h6>
        </div>
        <div class="mdc-drawer__content">
            <nav class="mdc-list">
                <a class="mdc-list-item" href="index.php">
                    <span class="mdc-list-item__ripple"></span>
                    <i class="material-icons mdc-list-item__graphic" aria-hidden="true">settings</i>
                    <span class="mdc-list-item__text">Konfiguracja podstawowa</span>
                </a>
                <a class="mdc-list-item" href="public_exams.php">
                    <span class="mdc-list-item__ripple"></span>
                    <i class="material-icons mdc-list-item__graphic" aria-hidden="true">task</i>
                    <span class="mdc-list-item__text">Lista testów publicznych</span>
                </a>
                <a class="mdc-list-item" href="exams.php">
                    <span class="mdc-list-item__ripple"></span>
                    <i class="material-icons mdc-list-item__graphic" aria-hidden="true">quiz</i>
                    <span class="mdc-list-item__text">Lista egzaminów</span>
                </a>
                <a class="mdc-list-item" href="users.php">
                    <span class="mdc-list-item__ripple"></span>
                    <i class="material-icons mdc-list-item__graphic" aria-hidden="true">face</i>
                    <span class="mdc-list-item__text">Uczniowie</span>
                </a>
                <a class="mdc-list-item" href="groups.php">
                    <span class="mdc-list-item__ripple"></span>
                    <i class="material-icons mdc-list-item__graphic" aria-hidden="true">groups</i>
                    <span class="mdc-list-item__text">Grupy</span>
                </a>
                <a class="mdc-list-item" href="active_exams.php">
                    <span class="mdc-list-item__ripple"></span>
                    <i class="material-icons mdc-list-item__graphic" aria-hidden="true">toggle_on</i>
                    <span class="mdc-list-item__text">Aktywne egzaminy</span>
                </a>
                <a class="mdc-list-item" href="history.php">
                    <span class="mdc-list-item__ripple"></span>
                    <i class="material-icons mdc-list-item__graphic" aria-hidden="true">history</i>
                    <span class="mdc-list-item__text">Historia egzaminów</span>
                </a>
                <a class="mdc-list-item" href="about.php">
                    <span class="mdc-list-item__ripple"></span>
                    <i class="material-icons mdc-list-item__graphic" aria-hidden="true">build</i>
                    <span class="mdc-list-item__text">O aplikacji</span>
                </a>
            </nav>
        </div>
    </aside>
<?php
}

function drawHeader()
{
?>
    <head>
        <title>Panel administratora</title>
        <meta charset="UTF-8">
        <link href="https://unpkg.com/material-components-web@latest/dist/material-components-web.min.css" rel="stylesheet">
        <script src="https://unpkg.com/material-components-web@latest/dist/material-components-web.min.js"></script>
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.11.3/datatables.min.css" />
        <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.11.3/datatables.min.js"></script>
        <link rel="stylesheet" type="text/css" href="css/style.css" />
    </head>
<?php
}
?>