<?php echo "<!doctype html>"; ?>
<?php require('sites/globalViews.php') ?>
<?php drawHeader(); ?>

<body>
    <div class="left" style="float: left;" id="menu">
        <?php
        drawMenu();
        ?>
    </div>
    <div class="center" id="content" style="min-width: 1000px;">
        <h1>Aktywne egzaminy</h1>
        <table id="activeExams_table"></table>
        <div class="block" style="text-align: right;">
            <button class="mdc-button mdc-button--raised" id="addActiveExam">
                <span class="mdc-button__label">Przypisz egzamin dla ucznia</span>
            </button>
            <button class="mdc-button mdc-button--raised" id="addGroupActiveExam">
                <span class="mdc-button__label">Przypisz egzamin dla grupy</span>
            </button>
        </div>
    </div>
    <div id="AddActiveExamDialog" style="display:none;">
        <input type="hidden" id="isEditMode"></input>
        <div class="mdc-dialog mdc-dialog--open mdc-dialog--fullscreen">>
            <div class="mdc-dialog__container">
                <div class="mdc-dialog__surface" role="dialog" aria-modal="true" aria-labelledby="my-dialog-title" aria-describedby="my-dialog-content">
                    <div class="mdc-dialog__header">
                        <h2 class="mdc-dialog__title" id="my-dialog-title">
                            Edycja ucznia
                        </h2>
                        <button class="mdc-icon-button material-icons mdc-dialog__close" data-mdc-dialog-action="close" id="ActiveExamDialog_closeButton">
                            close
                        </button>
                    </div>
                    <div class="mdc-dialog__content" id="my-dialog-content" style="padding: 10px;">
                        <div class="block">
                            <label for="userChecker">Uczeń:</label>
                            <select id="userChecker">
                            </select>
                        </div>
                        <div class="block">
                            <label for="examChecker">Egzamin:</label>
                            <select id="examChecker">
                            </select>
                        </div>
                    </div>
                    <div class="mdc-dialog__actions">
                        <input type="hidden" id="isNewExam" value="true">
                        <button type="button" class="mdc-button mdc-dialog__button" data-mdc-dialog-action="ok" id="SaveActiveExamButton">
                            <div class="mdc-button__ripple"></div>
                            <span class="mdc-button__label">Przypisz egzamin</span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="mdc-dialog__scrim"></div>
        </div>
    </div>

    <div id="AddActiveExamForGroupDialog" style="display:none;">
        <div class="mdc-dialog mdc-dialog--open mdc-dialog--fullscreen">>
            <div class="mdc-dialog__container">
                <div class="mdc-dialog__surface" role="dialog" aria-modal="true" aria-labelledby="my-dialog-title" aria-describedby="my-dialog-content">
                    <div class="mdc-dialog__header">
                        <h2 class="mdc-dialog__title" id="my-dialog-title">
                            Edycja grupy
                        </h2>
                        <button class="mdc-icon-button material-icons mdc-dialog__close" data-mdc-dialog-action="close" id="ActiveExamForGroupDialog_closeButton">
                            close
                        </button>
                    </div>
                    <div class="mdc-dialog__content" id="my-dialog-content" style="padding: 10px;">
                        <div class="block">
                            <label for="groupChecker">Grupa:</label>
                            <select id="groupChecker">
                            </select>
                        </div>
                        <div class="block">
                            <label for="examGroupChecker">Egzamin:</label>
                            <select id="examGroupChecker">
                            </select>
                        </div>
                    </div>
                    <div class="mdc-dialog__actions">
                        <input type="hidden" id="isNewExam" value="true">
                        <button type="button" class="mdc-button mdc-dialog__button" data-mdc-dialog-action="ok" id="SaveActiveExamForGroupButton">
                            <div class="mdc-button__ripple"></div>
                            <span class="mdc-button__label">Przypisz egzamin</span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="mdc-dialog__scrim"></div>
        </div>
    </div>
</body>
<script>
    $(function() {
        window.mdc.autoInit();
        const textFields = document.querySelectorAll('.mdc-text-field');
        for (const textField of textFields) {
            mdc.textField.MDCTextField.attachTo(textField);
        }
        loadTable();
        loadAllUsers();
        loadAllExamItems();
        loadAllGroups();
    })
    var table;

    function loadTable() {
        $.post('php_db_admin/GetAllActiveExams.php', {
                token: 'testToken'
            },
            function(data, status, jqXHR) {
                var obj = jQuery.parseJSON(data);
                if (table) {
                    table.clear().draw();
                    table.rows.add(obj); // Add new data
                    table.columns.adjust().draw(); // Redraw the DataTable
                } else {
                    table = createTable(obj);
                }
            })
    }

    function createTable(obj) {
        return $('#activeExams_table').DataTable({
            data: obj,
            columns: [{
                    title: 'Nazwa egzaminu',
                    data: null,
                    render: function(data, type, row) {
                        return data.ExamItem[0];
                    }
                },
                {
                    title: 'Imię i nazwisko',
                    data: null,
                    render: function(data, type, row) {
                        return data.User.Name + " " + data.User.Surname;
                    }
                }, {
                    title: 'userToken',
                    data: null,
                    render: function(data, type, row) {
                        return data.User.UserToken;
                    }
                }, {
                    title: 'Grupa',
                    data: null,
                    render: function(data, type, row) {
                        return data.User.Group_name;
                    }
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        return "<button onClick='activeExamDeleteClick(" + data.Id + ")'>Usuń</button>";
                    }
                }
            ],
            "search": {
                "caseInsensitive": false
            }
        });
    }

    $('#ActiveExamDialog_closeButton').click(function() {
        $('#AddActiveExamDialog').css('display', 'none');
    })
    $('#ActiveExamForGroupDialog_closeButton').click(function() {
        $('#AddActiveExamForGroupDialog').css('display', 'none');
    })
    $('#addActiveExam').click(function() {
        $('#AddActiveExamDialog').css('display', '');
    })
    $('#addGroupActiveExam').click(function(){
        $('#AddActiveExamForGroupDialog').css('display', '');
    })
    $('#SaveActiveExamButton').click(function() {
        $('#AddActiveExamDialog').css('display', 'none');
        $.post('php_db_admin/AddActiveExam.php', {
                token: 'testToken',
                UserId: $('#userChecker').val(),
                ExamId: $('#examChecker').val()
            },
            function(data, status, jqXHR) {
                var obj = jQuery.parseJSON(data);
                if (obj)
                    location.reload(true);
                else
                    alert("Uczeń ma już przypisany jakiś egzamin!");
            })
    })

    $('#SaveActiveExamForGroupButton').click(function(){
        $('#AddActiveExamForGroupDialog').css('display', 'none');
        $.post('php_db_admin/AddActiveExamForGroup.php', {
                token: 'testToken',
                GroupId: $('#groupChecker').val(),
                ExamId: $('#examGroupChecker').val()
            },
            function(data, status, jqXHR) {
                var obj = jQuery.parseJSON(data);
                if (obj)
                    location.reload(true);
                else
                    alert("Błąd :(");
            })
    })

    function loadAllUsers() {
        $.post('php_db_admin/GetAllUsers.php', {
                token: 'testToken'
            },
            function(data, status, jqXHR) {
                var obj = jQuery.parseJSON(data);
                for (const item of obj) {
                    $('#userChecker').append("<option value='" + item.Id + "'>" + item.Name + " " + item.Surname + " |" + item.UserToken + "</option>");
                }
            })
    }

    function loadAllGroups(){
        $.post('php_db_admin/GetAllGroups.php', {
                token: 'testToken'
            },
            function(data, status, jqXHR) {
                var obj = jQuery.parseJSON(data);
                for (const item of obj) {
                    $('#groupChecker').append("<option value='" + item.Id + "'>" + item.Name +"</option>");
                }
            })
    }

    function loadAllExamItems() {
        $.post('php_db_admin/GetAllExams.php', {
                token: 'testToken'
            },
            function(data, status, jqXHR) {
                var obj = jQuery.parseJSON(data);
                for (const item of obj) {
                    $('#examChecker').append("<option value='" + item[0] + "'>" + item[1] + "</option>");
                    $('#examGroupChecker').append("<option value='" + item[0] + "'>" + item[1] + "</option>");           
                }
            })
    }

    function activeExamDeleteClick(id){
        $.post('php_db_admin/DeleteActiveExam.php', {
                token: 'testToken',
                Id: id
            },
            function(data, status, jqXHR) {
                var obj = jQuery.parseJSON(data);
                if(obj)
                    loadTable();
            })
    }
</script>