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
        <table id="groups_table"></table>
        <div class="block" style="text-align: right;">
            <button class="mdc-button mdc-button--raised" id="addGroupButton">
                <span class="mdc-button__label">Dodaj nową</span>
            </button>
        </div>
    </div>

    <div id="editGroupDialog" style="display:none;">
        <input type="hidden" id="isEditMode"></input>
        <div class="mdc-dialog mdc-dialog--open mdc-dialog--fullscreen">>
            <div class="mdc-dialog__container">
                <div class="mdc-dialog__surface" role="dialog" aria-modal="true" aria-labelledby="my-dialog-title" aria-describedby="my-dialog-content">
                    <div class="mdc-dialog__header">
                        <h2 class="mdc-dialog__title" id="my-dialog-title">
                            Edycja grupy
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
                                        <span class="mdc-floating-label" id="my-label-id">Nazwa grupy</span>
                                    </span>
                                    <span class="mdc-notched-outline__trailing"></span>
                                </span>
                                <input type="text" class="mdc-text-field__input" aria-labelledby="my-label-id" id="Group_text">
                            </label>
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
    $(function() {
        textBoxes();

        loadTable();
    })

    function textBoxes() {
        window.mdc.autoInit();
        const textFields = document.querySelectorAll('.mdc-text-field');
        for (const textField of textFields) {
            mdc.textField.MDCTextField.attachTo(textField);
            textField.focus();
        }
    }

    var table;

    function loadTable() {
        $.post('php_db_admin/GetAllGroups.php', {
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
        return $('#groups_table').DataTable({
            data: obj,
            columns: [{
                    data: 'Name',
                    title: 'Nazwa grupy'
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        return "<button onClick='groupClick(" + data.Id + ")'>Edytuj</button>";
                    }
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        return "<button onClick='groupDeleteClick(" + data.Id + ")'>Usuń</button>";
                    }
                }
            ],
            "search": {
                "caseInsensitive": false
            }
        });
    }

    //edit
    function groupClick(id) {
        $('#isEditMode').val(id);
        $('#editGroupDialog').css('display', '');
        //
        if ($('#isEditMode').val() != "") {
            $.post('php_db_admin/GetGroup.php', {
                    token: 'testToken',
                    Id: $('#isEditMode').val()
                },
                function(data, status, jqXHR) {
                    var obj = jQuery.parseJSON(data);
                    $('#Group_text').val(obj);
                    textBoxes();
                })
        }
    }

    //delete
    function groupDeleteClick(id) {
        $.post('php_db_admin/DeleteGroup.php', {
                token: 'testToken',
                Id: id
            },
            function(data, status, jqXHR) {
                var obj = jQuery.parseJSON(data);
                if (obj) {
                    location.reload(true);
                } else {
                    alert("Do grupy przypisani są uczniowie, najpierw usuń wszystkich uczniów");
                }
            })
    }

    $('#SaveButton').click(function() {
        if ($('#isEditMode').val() != "") {
            $.post('php_db_admin/UpdateGroup.php', {
                    token: 'testToken',
                    Id: $('#isEditMode').val(),
                    Name: $('#Group_text').val()
                },
                function(data, status, jqXHR) {
                    var obj = jQuery.parseJSON(data);
                    location.reload(true);
                })
        } else {
            $.post('php_db_admin/AddNewGroup.php', {
                    token: 'testToken',
                    Name: $('#Group_text').val()
                },
                function(data, status, jqXHR) {
                    var obj = jQuery.parseJSON(data);
                    location.reload(true);
                })
        }
    })

    $('#addGroupButton').click(function() {
        groupClick("");
    })

    $('#closeButton').click(function() {
        $('#editGroupDialog').css('display', 'none');
        //clear
        $('#Group_text').val('');
    })
</script>