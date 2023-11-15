<?php echo "<!doctype html>"; ?>
<?php require('sites/globalViews.php') ?>
<?php drawHeader(); ?>

<body>
    <div class="left" style="float: left;" id="menu">
        <?php
        drawMenu();
        ?>
    </div>
    <div class="center" id="content">
        <table id="usersTable"></table>
        <div class="block" style="text-align: right;">
            <button class="mdc-button mdc-button--raised" id="addUserButton">
                <span class="mdc-button__label">Dodaj nowego ucznia</span>
            </button>
        </div>
    </div>

    <div id="editUserDialog" style="display:none;">
        <input type="hidden" id="isEditMode"></input>
        <div class="mdc-dialog mdc-dialog--open mdc-dialog--fullscreen">>
            <div class="mdc-dialog__container">
                <div class="mdc-dialog__surface" role="dialog" aria-modal="true" aria-labelledby="my-dialog-title" aria-describedby="my-dialog-content">
                    <div class="mdc-dialog__header">
                        <h2 class="mdc-dialog__title" id="my-dialog-title">
                            Edycja ucznia
                        </h2>
                        <button class="mdc-icon-button material-icons mdc-dialog__close" data-mdc-dialog-action="close" id="closeButton">
                            close
                        </button>
                    </div>
                    <div class="mdc-dialog__content" id="my-dialog-content" style="padding: 10px;">
                        <div class="block">
                            <label class="mdc-text-field mdc-text-field--outlined">
                                <span class="mdc-notched-outline mdc-notched-outline--upgraded">
                                    <span class="mdc-notched-outline__leading"></span>
                                    <span class="mdc-notched-outline__notch">
                                        <span class="mdc-floating-label" id="my-label-id">Token ucznia</span>
                                    </span>
                                    <span class="mdc-notched-outline__trailing"></span>
                                </span>
                                <input type="text" class="mdc-text-field__input" aria-labelledby="my-label-id" id="Token_text">
                            </label>
                        </div>
                        <div class="block">
                            <label class="mdc-text-field mdc-text-field--outlined">
                                <span class="mdc-notched-outline mdc-notched-outline--upgraded">
                                    <span class="mdc-notched-outline__leading"></span>
                                    <span class="mdc-notched-outline__notch">
                                        <span class="mdc-floating-label" id="my-label-id">Imię</span>
                                    </span>
                                    <span class="mdc-notched-outline__trailing"></span>
                                </span>
                                <input type="text" class="mdc-text-field__input" aria-labelledby="my-label-id" id="Name_text">
                            </label>
                        </div>
                        <div class="block">
                            <label class="mdc-text-field mdc-text-field--outlined">
                                <span class="mdc-notched-outline mdc-notched-outline--upgraded">
                                    <span class="mdc-notched-outline__leading"></span>
                                    <span class="mdc-notched-outline__notch">
                                        <span class="mdc-floating-label" id="my-label-id">Nazwisko</span>
                                    </span>
                                    <span class="mdc-notched-outline__trailing"></span>
                                </span>
                                <input type="text" class="mdc-text-field__input" aria-labelledby="my-label-id" id="Surname_text">
                            </label>
                        </div>
                        <div class="block">
                            <label for="groupChecker">Grupa:</label>
                            <select id="groupChecker">

                            </select>
                        </div>
                    </div>
                    <div class="mdc-dialog__actions">
                        <input type="hidden" id="isNewExam" value="true">
                        <button type="button" class="mdc-button mdc-dialog__button" data-mdc-dialog-action="ok" id="SaveButton">
                            <div class="mdc-button__ripple"></div>
                            <span class="mdc-button__label">Zapisz</span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="mdc-dialog__scrim"></div>
        </div>
    </div>
</body>
<script>
    function textBoxes() {
        window.mdc.autoInit();
        const textFields = document.querySelectorAll('.mdc-text-field');
        for (const textField of textFields) {
            mdc.textField.MDCTextField.attachTo(textField);
            textField.focus();
        }
    }

    $(function() {
        textBoxes();
        loadUsersTable();
        //load groups
        loadAllGroups();
    })
    var table;

    function loadUsersTable() {
        $.post('php_db_admin/GetAllUsers.php', {
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
        return $('#usersTable').DataTable({
            data: obj,
            columns: [{
                    data: 'UserToken',
                    title: 'UserToken'
                },
                {
                    data: 'Name',
                    title: 'Imię'
                },
                {
                    data: 'Surname',
                    title: 'Nazwisko'
                },
                {
                    data: null,
                    title: 'Grupa',
                    render: function(data, type, row) {
                        return data.Group_name;
                    }
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        return "<button onClick='userEditClick(" + data.Id + ")'>Edytuj</button>";
                    }
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        return "<button onClick='userDeleteClick(" + data.Id + ")'>Usuń</button>";
                    }
                }
            ],
            "search": {
                "caseInsensitive": false
            }
        });
    }

    function userDeleteClick(id) {
        $.post('php_db_admin/DeleteUser.php', {
                token: 'testToken',
                Id: id
            },
            function(data, status, jqXHR) {
                var obj = jQuery.parseJSON(data);
                if (obj) {
                    location.reload(true);
                } else {
                    alert("Do ucznia są przypisane jakieś egzaminy i/lub ich historia. Rozważ usunięcie przypisanych egzaminów oraz ich historii przed usunięciem ucznia");
                }
            })
    }

    $('#closeButton').click(function() {
        $('#editUserDialog').css('display', 'none');
        //clear 
        clearEditBox();
    })

    $('#addUserButton').click(function() {
        //set adduser mode
        $('#isEditMode').val('');
        $('#editUserDialog').css('display', '');
    })

    function loadAllGroups() {
        $.post('php_db_admin/GetAllGroups.php', {
                token: 'testToken'
            },
            function(data, status, jqXHR) {
                var obj = jQuery.parseJSON(data);
                for (const item of obj) {
                    $('#groupChecker').append("<option value='" + item.Id + "'>" + item.Name + "</option>");
                }
            })
    }

    $('#SaveButton').click(function() {
        if ($('#isEditMode').val() != "") {
            $.post('php_db_admin/UpdateUser.php', {
                    token: 'testToken',
                    Id: $('#isEditMode').val(),
                    UserToken: $('#Token_text').val(),
                    Name: $('#Name_text').val(),
                    Surname: $('#Surname_text').val(),
                    Group_id: $('#groupChecker').val()
                },
                function(data, status, jqXHR) {
                    var obj = jQuery.parseJSON(data);
                    if (obj)
                        location.reload(true);
                    else
                        alert("Taki token już istnieje!");
                })
        } else {
            $.post('php_db_admin/AddUser.php', {
                    token: 'testToken',
                    UserToken: $('#Token_text').val(),
                    Name: $('#Name_text').val(),
                    Surname: $('#Surname_text').val(),
                    Group_id: $('#groupChecker').val()
                },
                function(data, status, jqXHR) {
                    var obj = jQuery.parseJSON(data);
                    if (obj)
                        location.reload(true);
                    else
                        alert("Taki token już istnieje!");
                })
        }
    })

    function userEditClick(id) {
        $('#isEditMode').val(id);
        $.post('php_db_admin/GetUser.php', {
                token: 'testToken',
                UserId: id
            },
            function(data, status, jqXHR) {
                var obj = jQuery.parseJSON(data);
                $('#Token_text').val(obj.UserToken);
                $('#Name_text').val(obj.Name);
                $('#Surname_text').val(obj.Surname);

                for (const item of $('#groupChecker').children('option')) {
                    if ($(item).val() == obj.Group_id)
                        $(item).attr('selected', 'selected');
                }
                textBoxes();
            })
        $('#editUserDialog').css('display', '');
    }

    function clearEditBox(){
        $('#Token_text').val('');
        $('#Name_text').val('');
        $('#Surname_text').val('');
    }
</script>