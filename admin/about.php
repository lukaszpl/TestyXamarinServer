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
        <h2>Aplikacja Twoje Testy</h2>
        <h3>Panel administartora v1.0.0</h3>
        <p>Twórca: Łukasz Soroczyński</p>
    </div>
</body>
<script>
    $(function() {
        window.mdc.autoInit();
        const textFields = document.querySelectorAll('.mdc-text-field');
        for (const textField of textFields) {
            mdc.textField.MDCTextField.attachTo(textField);
        }
    })
</script>