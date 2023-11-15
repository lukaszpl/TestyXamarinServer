<?php echo "<!doctype html>"; ?>
<?php require('sites/globalViews.php') ?>
<?php drawHeader(); ?>
<div class="left" style="float: left;" id="menu">
    <?php
    drawMenu();
    ?>
</div>
<div class="center">
    <h1 style="text-align: center;">Testy publiczne</h1>
    <div class="block">
        <ul class="mdc-list mdc-list--two-line" id="exams_list" style="width: 500px;"> </ul>
    </div>
    <div class="block" style="text-align: right;">
        <button class="mdc-button mdc-button--raised" id="addExamButton">
            <span class="mdc-button__label">Dodaj nowy</span>
        </button>
        <button class="mdc-button mdc-button--raised" id="editExamButton">
            <span class="mdc-button__label">Edytuj wybrany</span>
        </button>
        <button class="mdc-button mdc-button--raised" id="delExamButton">
            <span class="mdc-button__label">Usuń wybrany</span>
        </button>
    </div>

    <div id="questions" style="display:none;">
        <table id="questions_table"></table>
        <div class="block" style="text-align: right;">
            <button class="mdc-button mdc-button--raised" id="addQuestionButton">
                <span class="mdc-button__label">Dodaj pytanie</span>
            </button>
        </div>
    </div>

    <div id="editQuestionDialog" style="display:none;">
        <input type="hidden" id="questionId" value="0">
        <input type="hidden" id="EditquestionExamId" value="null">
        <div class="mdc-dialog mdc-dialog--open mdc-dialog--fullscreen">>
            <div class="mdc-dialog__container">
                <div class="mdc-dialog__surface" role="dialog" aria-modal="true" aria-labelledby="my-dialog-title" aria-describedby="my-dialog-content">
                    <div class="mdc-dialog__header">
                        <h2 class="mdc-dialog__title" id="my-dialog-title">
                            Edycja pytania
                        </h2>
                        <button class="mdc-icon-button material-icons mdc-dialog__close" data-mdc-dialog-action="close" id="closeButton">
                            close
                        </button>
                    </div>
                    <div class="mdc-dialog__content" id="my-dialog-content" style="padding: 10px;">
                        <div class="block">
                            <label class="mdc-text-field mdc-text-field--outlined mdc-text-field--label-floating">
                                <span class="mdc-notched-outline mdc-notched-outline--upgraded mdc-notched-outline--notched">
                                    <span class="mdc-notched-outline__leading"></span>
                                    <span class="mdc-notched-outline__notch" style="width: 48.5px;">
                                        <span class="mdc-floating-label mdc-floating-label--float-above" id="my-label-id">Pytanie</span>
                                    </span>
                                    <span class="mdc-notched-outline__trailing"></span>
                                </span>
                                <input type="text" class="mdc-text-field__input" aria-labelledby="my-label-id" id="Question_text">
                            </label>
                        </div>
                        <div class="block">
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
                            <h3>Odpowiedzi:</h3>
                            <ul class="mdc-list" role="group" aria-label="List with checkbox items" id="answersList">

                            </ul>
                            <div class="block" style="text-align: right;">
                                <button class="mdc-button mdc-button--raised" id="addAnswerButton">
                                    <span class="mdc-button__label">Dodaj nową odpowiedź</span>
                                </button>
                            </div>
                        </div>

                    </div>
                    <div class="mdc-dialog__actions">
                        <button type="button" class="mdc-button mdc-dialog__button" data-mdc-dialog-action="ok" id="SaveQuestionButton">
                            <div class="mdc-button__ripple"></div>
                            <span class="mdc-button__label">Zapisz</span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="mdc-dialog__scrim"></div>
        </div>
    </div>


    <div id="editExamDialog" style="display:none;">
        <div class="mdc-dialog mdc-dialog--open mdc-dialog--fullscreen">>
            <div class="mdc-dialog__container">
                <div class="mdc-dialog__surface" role="dialog" aria-modal="true" aria-labelledby="my-dialog-title" aria-describedby="my-dialog-content">
                    <div class="mdc-dialog__header">
                        <h2 class="mdc-dialog__title" id="my-dialog-title">
                            Edycja egzaminu
                        </h2>
                        <button class="mdc-icon-button material-icons mdc-dialog__close" data-mdc-dialog-action="close" id="closeExamButton">
                            close
                        </button>
                    </div>
                    <div class="mdc-dialog__content" id="my-dialog-content" style="padding: 10px;">
                        <div class="block">
                            <label class="mdc-text-field mdc-text-field--outlined">
                                <span class="mdc-notched-outline mdc-notched-outline--upgraded">
                                    <span class="mdc-notched-outline__leading"></span>
                                    <span class="mdc-notched-outline__notch">
                                        <span class="mdc-floating-label" id="my-label-id">Nazwa egzaminu</span>
                                    </span>
                                    <span class="mdc-notched-outline__trailing"></span>
                                </span>
                                <input type="text" class="mdc-text-field__input" aria-labelledby="my-label-id" id="Exam_text">
                            </label>
                        </div>
                        <div class="block">
                            <label class="mdc-text-field mdc-text-field--outlined">
                                <span class="mdc-notched-outline mdc-notched-outline--upgraded">
                                    <span class="mdc-notched-outline__leading"></span>
                                    <span class="mdc-notched-outline__notch">
                                        <span class="mdc-floating-label" id="my-label-id">Opis</span>
                                    </span>
                                    <span class="mdc-notched-outline__trailing"></span>
                                </span>
                                <input type="text" class="mdc-text-field__input" aria-labelledby="my-label-id" id="ExamInfo_text">
                            </label>
                        </div>
                    </div>
                    <div class="mdc-dialog__actions">
                        <input type="hidden" id="isNewExam" value="true">
                        <button type="button" class="mdc-button mdc-dialog__button" data-mdc-dialog-action="ok" id="SaveExamButton">
                            <div class="mdc-button__ripple"></div>
                            <span class="mdc-button__label">Zapisz</span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="mdc-dialog__scrim"></div>
        </div>
    </div>


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
            //load from db
            $.post('php_db_admin/GetAllPublicExams.php', {
                    token: 'testToken'
                },
                function(data, status, jqXHR) {
                    var obj = jQuery.parseJSON(data);
                    var i = 0;
                    for (const exam of obj) {
                        $("#exams_list").append("<li onClick='examClick(" + exam[0] + ")' class='mdc-list-item' id=" + i + " tabindex='" + i + "'><span class='mdc-list-item__ripple'></span><span class='mdc-list-item__text' style='width:500px; text-align:center; margin-bottom:2%;'><span class='mdc-list-item__primary-text'>" + exam[1] + "</span><span class='mdc-list-item__secondary-text'>" + exam[2] + "</span></span></li>");
                        i++;
                    }
                })
        });

        $('#addExamButton').click(function() {
            $('#isNewExam').val('true');
            $('#editExamDialog').css('display', '');
        })

        $('#editExamButton').click(function() {
            if ($('#EditquestionExamId').val() != "null") {
                $('#isNewExam').val('false');
                $('#editExamDialog').css('display', '');
                //
                $.post('php_db_admin/GetPublicExamItem.php', {
                        token: 'testToken',
                        ExamId: $('#EditquestionExamId').val(),
                    },
                    function(data, status, jqXHR) {
                        var obj = jQuery.parseJSON(data);
                        if (obj) {
                            $('#Exam_text').val(obj[0]),
                                $('#ExamInfo_text').val(obj[1])
                        }
                        textBoxes();
                    })
            }
        })
        $('#delExamButton').click(function() {
            $.post('php_db_admin/DeletePublicExam.php', {
                    token: 'testToken',
                    ExamId: $('#EditquestionExamId').val()
                },
                function(data, status, jqXHR) {
                    var obj = jQuery.parseJSON(data);
                    if (obj) {
                        location.reload(true);
                    } else {
                        alert("Usuwanie nie powiodło się, najpierw usuń wszystkie pytania!");
                    }
                })
        })

        $('#SaveExamButton').click(function() {
            if ($('#isNewExam').val() == "true") {
                $.post('php_db_admin/AddPublicExam.php', {
                        token: 'testToken',
                        ExamName: $('#Exam_text').val(),
                        ExamInfo: $('#ExamInfo_text').val()
                    },
                    function(data, status, jqXHR) {
                        var obj = jQuery.parseJSON(data);
                        if (obj) {
                            location.reload(true);
                        }
                        $('#editExamDialog').css('display', 'none');
                    })
            } else {
                $.post('php_db_admin/EditPublicExam.php', {
                        token: 'testToken',
                        ExamId: $('#EditquestionExamId').val(),
                        ExamName: $('#Exam_text').val(),
                        ExamInfo: $('#ExamInfo_text').val()
                    },
                    function(data, status, jqXHR) {
                        var obj = jQuery.parseJSON(data);
                        if (obj) {
                            location.reload(true);
                        }
                        $('#editExamDialog').css('display', 'none');
                    })
            }
        })



        var table;

        function examClick(id) {
            $('#EditquestionExamId').val(id);
            $('#questions').css("display", "")
            $.post('php_db_admin/GetPublicExam.php', {
                    token: 'testToken',
                    ExamId: id
                },
                function(data, status, jqXHR) {
                    var obj = jQuery.parseJSON(data);
                    if (table) {
                        table.clear().draw();
                        table.rows.add(obj); // Add new data
                        table.columns.adjust().draw(); // Redraw the DataTable
                        $('#questions_table').css("width", "600px");
                    } else {
                        table = createTable(obj);
                    }
                })
        }

        function createTable(obj) {
            return $('#questions_table').DataTable({
                data: obj,
                columns: [{
                        data: 'Question',
                        title: 'Pytanie'
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return "<button onClick='questionClick(" + data.Id + ")'>Edytuj</button>";
                        }
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return "<button onClick='questionDeleteClick(" + data.Id + ")'>Usuń</button>";
                        }
                    }
                ],
                "search": {
                    "caseInsensitive": false
                }
            });
        }

        var ans_id = 0;

        function questionClick(id) {
            $('#questionId').val(id);
            $('#editQuestionDialog').css('display', '');
            if (id != null) {
                $.post('php_db_admin/GetPublicQuestion.php', {
                        token: 'testToken',
                        questionId: id
                    },
                    function(data, status, jqXHR) {
                        var obj = jQuery.parseJSON(data);
                        $('#Question_text').val(obj.Question);
                        $('#showimg').attr('src', 'data:image/png;base64,' + obj.Base64Img);
                        var answers = jQuery.parseJSON(obj.Answers);
                        for (const element of answers.Answers) {
                            if (element.IsCorrect)
                                var checked = "checked";
                            else
                                var checked = "";
                            $('#answersList').append("<li id='answer_" + ans_id + "' class='mdc-list-item' role='checkbox' aria-checked='false'><span class='mdc-list-item__ripple'></span><span class='mdc-list-item__graphic'><div class='mdc-checkbox' style='margin-top: 20%;'><input type='checkbox' class='mdc-checkbox__native-control' id='ans_checkBox'" + checked + "/> <div class='mdc-checkbox__background'> <svg class='mdc-checkbox__checkmark' viewBox='0 0 24 24'> <path class='mdc-checkbox__checkmark-path' fill='none' d='M1.73,12.91 8.1,19.28 22.79,4.59'/> </svg> <div class='mdc-checkbox__mixedmark'></div></div></div></span> <label class='mdc-text-field mdc-text-field--filled'> <span class='mdc-text-field__ripple'></span> <span class='mdc-floating-label' id='my-label-id'>Odpowiedź</span> <input class='mdc-text-field__input' type='text' aria-labelledby='my-label-id' id='answerText' value='" + element.Answer + "'> <span class='mdc-line-ripple'></span> </label> <button onClick='delAnswerButton(" + ans_id + ")'> <span class='material-icons'> delete_forever </span> </button> </li>")
                            ans_id++;
                        }
                        textBoxes();
                    })
            }
        }

        function questionDeleteClick(id) {
            $.post('php_db_admin/DeletePublicQuestion.php', {
                    token: 'testToken',
                    QuestionId: id
                },
                function(data, status, jqXHR) {
                    alert(data);
                    examClick($('#EditquestionExamId').val()); //reloadTable             
                })
        }

        $('#SaveQuestionButton').click(function() {
            //
            let AnswersArr = [];

            for (const el of $('#answersList').children('li')) {
                var isCorrect = $(el).find('#ans_checkBox').is(":checked");
                var answer = $(el).find('#answerText').val();

                var ansObj = {
                    Answer: answer,
                    IsCorrect: isCorrect
                };
                AnswersArr.push(ansObj);
            }
            let AnswersToParse = new Answers(AnswersArr);
            let AnswersJson = JSON.stringify(AnswersToParse);
            const base64 = $('#showimg').attr('src');
            //if with id - update
            if ($('#questionId').val() != "") {
                $.post('php_db_admin/UpdatePublicQuestion.php', {
                        token: 'testToken',
                        QuestionId: $('#questionId').val(),
                        Question: $('#Question_text').val(),
                        Base64Img: base64.slice(base64.indexOf(',') + 1),
                        Answers: AnswersJson
                    },
                    function(data, status, jqXHR) {
                        alert(data);
                        examClick($('#EditquestionExamId').val());
                    })
            } else {
                $.post('php_db_admin/AddPublicQuestion.php', {
                        token: 'testToken',
                        ExamId: $('#EditquestionExamId').val(),
                        Question: $('#Question_text').val(),
                        Base64Img: base64.slice(base64.indexOf(',') + 1),
                        Answers: AnswersJson
                    },
                    function(data, status, jqXHR) {
                        alert(data);
                        examClick($('#EditquestionExamId').val());
                    })
            }
            //
            $('#editQuestionDialog').css('display', 'none');
            //clear
            $('#answersList').empty();
            $('#Question_text').val('')
            $('#showimg').attr('src', '');
        });


        $('#addAnswerButton').click(function() {
            $('#answersList').append("<li id='answer_" + ans_id + "' class='mdc-list-item' role='checkbox' aria-checked='false'><span class='mdc-list-item__ripple'></span><span class='mdc-list-item__graphic'><div class='mdc-checkbox' style='margin-top: 20%;'><input type='checkbox' class='mdc-checkbox__native-control' id='ans_checkBox'/> <div class='mdc-checkbox__background'> <svg class='mdc-checkbox__checkmark' viewBox='0 0 24 24'> <path class='mdc-checkbox__checkmark-path' fill='none' d='M1.73,12.91 8.1,19.28 22.79,4.59'/> </svg> <div class='mdc-checkbox__mixedmark'></div></div></div></span> <label class='mdc-text-field mdc-text-field--filled'> <span class='mdc-text-field__ripple'></span> <span class='mdc-floating-label' id='my-label-id'>Odpowiedź</span> <input class='mdc-text-field__input' type='text' aria-labelledby='my-label-id' id='answerText'> <span class='mdc-line-ripple'></span> </label> <button onClick='delAnswerButton(" + ans_id + ")'> <span class='material-icons'> delete_forever </span> </button> </li>")
            ans_id++;
            textBoxes();
        })

        function delAnswerButton(id) {
            var element = "#answer_" + id;
            $(element).remove();
        }


        $('#addQuestionButton').click(function() {
            questionClick(null);
        })

        $('#closeButton').click(function() {
            $('#editQuestionDialog').css('display', 'none');
            //clear
            $('#answersList').empty();
            $('#Question_text').val('');
            $('#showimg').attr('src', '');
        })
        $('#closeExamButton').click(function() {
            //exam
            $('#editExamDialog').css('display', 'none');
            $('#Exam_text').val('');
            $('#ExamInfo_text').val('');
        })

        class Answers {
            constructor(answers) {
                this.Answers = answers;
            }
            Answers;
        }
    </script>