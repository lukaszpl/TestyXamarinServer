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
        <table id="examsHistory_table"></table>
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
    })
    var table;

    function loadTable() {
        $.post('php_db_admin/GetHistoryTable.php', {
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
        return $('#examsHistory_table').DataTable({
            data: obj,
            columns: [{
                    data: 'Id',
                    title: 'Id'
                },
                {
                    title: 'Imię i nazwisko',
                    data: null,
                    render: function(data, type, row) {
                        return data.User.Name + " " + data.User.Surname;
                    }
                },
                {
                    data: 'Date',
                    title: 'Data'
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        return "<form target='_blank' action='examView.php' action='GET'><input type='hidden' name='Id' value='"+ data.Id +"' /><input type='hidden' name='UserId' value='"+ data.User.Id +"' /><input type='submit' value='Pokaż szczegóły'/></form>";
                    }
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        return "<button onClick='historyExamDeleteClick(" + data.Id + ")'>Usuń</button>";
                    }
                }
            ],
            "search": {
                "caseInsensitive": false
            }
        });
    }

    function historyExamDeleteClick(id){
        $.post('php_db_admin/DeleteHistoryItem.php', {
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