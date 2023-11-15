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
        <div class="block">
            <label class="mdc-text-field mdc-text-field--outlined mdc-text-field--label-floating">
                <span class="mdc-notched-outline mdc-notched-outline--upgraded mdc-notched-outline--notched">
                    <span class="mdc-notched-outline__leading"></span>
                    <span class="mdc-notched-outline__notch" style="width: 107px;">
                        <span class="mdc-floating-label mdc-floating-label--float-above" id="my-label-id">Nazwa organizacji</span>
                    </span>
                    <span class="mdc-notched-outline__trailing"></span>
                </span>
                <input type="text" class="mdc-text-field__input" aria-labelledby="my-label-id" id="orgName_text">
            </label>
        </div>
        <div class="block">
            <label class="mdc-text-field mdc-text-field--outlined mdc-text-field--label-floating">
                <span class="mdc-notched-outline mdc-notched-outline--upgraded mdc-notched-outline--notched">
                    <span class="mdc-notched-outline__leading"></span>
                    <span class="mdc-notched-outline__notch" style="width: 107px;">
                        <span class="mdc-floating-label mdc-floating-label--float-above" id="my-label-id">Informacje</span>
                    </span>
                    <span class="mdc-notched-outline__trailing"></span>
                </span>
                <input type="text" class="mdc-text-field__input" aria-labelledby="my-label-id" id="info_text">
            </label>
        </div>
        <div class="block" style="margin-bottom: 20px;">
            <h3>Logo organizacji:</h3>
            <form runat="server">
                <input accept="image/*" type='file' id="logoimg" />
                <img id="showimg" src="#" alt="your image" style="max-width:500px;" />
            </form>
            <script>
                logoimg.onchange = evt => {
                    const [file] = logoimg.files
                    if (file) {
                        var reader = new FileReader();
                        reader.readAsDataURL(file);
                        reader.onloadend = function() {
                            showimg.src = reader.result;
                        }
                    }
                }
            </script>
        </div>
        <div class="block">
            <label class="mdc-text-field mdc-text-field--filled mdc-ripple-upgraded mdc-text-field--label-floating" style="--mdc-ripple-fg-size: 300px; --mdc-ripple-fg-scale: 1.7104207556885966; --mdc-ripple-fg-translate-start: 265.29998779296875px, -114.76667785644531px; --mdc-ripple-fg-translate-end: 100px, -122px;">
                <span class="mdc-text-field__ripple"></span>
                <span class="mdc-floating-label mdc-floating-label--float-above">Adres serwera</span>
                <input class="mdc-text-field__input" type="text" aria-labelledby="my-label-id" aria-controls="my-helper-id" aria-describedby="my-helper-id" id="srv_addr">
                <span class="mdc-line-ripple" style="transform-origin: 399.3px center 0px;"></span>
            </label>
            <div class="mdc-text-field-helper-line">
                <div class="mdc-text-field-helper-text" id="my-helper-id" aria-hidden="true">Adres skryptu odpowiadającego za
                    obsługę egzaminów</div>
            </div>
        </div>
        <div class="block">
            <button class="mdc-button mdc-button--raised" id="save_conf">
                <span class="mdc-button__label">Zapisz</span>
            </button>
            <p id="isSavedText" style="color: green; display: none;">Zapisano!</p>
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

        //load from db
        $.post('php_db_admin/GetConfiguration.php', {
                token: 'testToken'
            },
            function(data, status, jqXHR) {
                var obj = jQuery.parseJSON(data);
                $('#orgName_text').val(obj.OrganisationName);
                $('#info_text').val(obj.OrganisationInformations);
                $('#srv_addr').val(obj.ExamScriptAddres);
                $('#showimg').attr('src', 'data:image/png;base64,' + obj.Logo_Base64);
            })
    });

    $('#save_conf').click(function() {
        const base64 = $('#showimg').attr('src');
        $.post('php_db_admin/SetConfiguration.php', {
                token: 'testToken',
                organisationName: $('#orgName_text').val(),
                organisationInformations: $('#info_text').val(),
                logo_Base64: base64.slice(base64.indexOf(',') + 1),
                examScriptAddres: $('#srv_addr').val()
            },
            function(data, status, jqXHR) {
                if (data) {
                    $('#isSavedText').css('display', 'block');
                }
            })
    });
</script>
