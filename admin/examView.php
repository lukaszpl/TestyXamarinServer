<?php echo "<!DOCTYPE html>";
header('Content-Type: text/html; charset=utf-8'); ?>
<?php require('sites/globalViews.php') ?>
<html lang="pl">
<?php drawHeader(); ?>
<?php
$histId = $_GET["Id"];
$userId = $_GET["UserId"];
require 'php_db_admin/DatabaseHandler.php';
$user = DatabaseHandler::GetUser($userId);
$json = DatabaseHandler::GetHistoryJson($histId);
$obj = json_decode($json);
?>

<body>
    <h1 style="text-align: center;"><?php echo $obj->ExamName; ?></h1>
    <h1 style="text-align: center;">Użytkownik: <?php echo $user->Name." ".$user->Surname?></h1>
    <h1 style="text-align: center;"><?php echo getPointsForExam($obj) . "/" . getAllPosiblePoints($obj) ?></h1>
    <div class="block">
        <?php
        foreach ($obj->Questions as $question) {
            echo "<div class='question'>";
            echo "<h2 style='text-align: center;'>" . $question->Question . "</h2>";
            if(strlen($question->Base64Img) > 2)
                echo "<div class='imgcenter'><img width='450'  src='data:image/jpeg;base64,".$question->Base64Img."'></div>";
            echo "<br>";
            foreach ($question->Answers as $answer) {
                if (($answer->UserAnswer == true) && ($answer->IsCorrect == true)) {
        ?>
                    <div class="mdc-form-field" style="background-color:#54c942; border-left: 4px solid #000">
                        <div class="mdc-checkbox">
                            <input type="checkbox" class="mdc-checkbox__native-control" id="<?php echo $answer->Id  ?>" checked disabled/>
                            <div class="mdc-checkbox__background">
                                <svg class="mdc-checkbox__checkmark" viewBox="0 0 24 24">
                                    <path class="mdc-checkbox__checkmark-path" fill="none" d="M1.73,12.91 8.1,19.28 22.79,4.59" />
                                </svg>
                                <div class="mdc-checkbox__mixedmark"></div>
                            </div>
                            <div class="mdc-checkbox__ripple"></div>
                        </div>
                        <label for="<?php echo $answer->Id  ?>"><?php echo $answer->Answer ?></label>
                    </div>
                <?php
                } else if (($answer->UserAnswer == true) && ($answer->IsCorrect == false)) {
                ?>
                    <div class="mdc-form-field" style="background-color:#d44c4c; border-left: 4px solid #000">
                        <div class="mdc-checkbox">
                            <input type="checkbox" class="mdc-checkbox__native-control" id="<?php echo $answer->Id  ?>" checked disabled/>
                            <div class="mdc-checkbox__background">
                                <svg class="mdc-checkbox__checkmark" viewBox="0 0 24 24">
                                    <path class="mdc-checkbox__checkmark-path" fill="none" d="M1.73,12.91 8.1,19.28 22.79,4.59" />
                                </svg>
                                <div class="mdc-checkbox__mixedmark"></div>
                            </div>
                            <div class="mdc-checkbox__ripple"></div>
                        </div>
                        <label for="<?php echo $answer->Id  ?>"><?php echo $answer->Answer ?></label>
                    </div>
                <?php
                } else if (($answer->UserAnswer == false) && ($answer->IsCorrect == true)) {
                ?>
                    <div class="mdc-form-field" style="background-color:#54c942;">
                        <div class="mdc-checkbox">
                            <input type="checkbox" class="mdc-checkbox__native-control" id="<?php echo $answer->Id  ?>" disabled/>
                            <div class="mdc-checkbox__background">
                                <svg class="mdc-checkbox__checkmark" viewBox="0 0 24 24">
                                    <path class="mdc-checkbox__checkmark-path" fill="none" d="M1.73,12.91 8.1,19.28 22.79,4.59" />
                                </svg>
                                <div class="mdc-checkbox__mixedmark"></div>
                            </div>
                            <div class="mdc-checkbox__ripple"></div>
                        </div>
                        <label for="<?php echo $answer->Id  ?>"><?php echo $answer->Answer ?></label>
                    </div>
                <?php
                } else if ($answer->UserAnswer == false) {
                ?>
                    <div class="mdc-form-field" style="background-color: #d1d1d1;">
                        <div class="mdc-checkbox">
                            <input type="checkbox" class="mdc-checkbox__native-control" id="<?php echo $answer->Id  ?>" disabled/>
                            <div class="mdc-checkbox__background">
                                <svg class="mdc-checkbox__checkmark" viewBox="0 0 24 24">
                                    <path class="mdc-checkbox__checkmark-path" fill="none" d="M1.73,12.91 8.1,19.28 22.79,4.59" />
                                </svg>
                                <div class="mdc-checkbox__mixedmark"></div>
                            </div>
                            <div class="mdc-checkbox__ripple"></div>
                        </div>
                        <label for="<?php echo $answer->Id?>"><?php echo $answer->Answer?></label>
                    </div>
        <?php
                }
                echo "<br>";
            }
            echo "</div>";
            echo "<br>";
        }
        ?>


    </div>
</body>

</html>

<?php

function getPointsForExam($obj)
{
    $points = 0;
    foreach ($obj->Questions as $question) {
        $pointsForQuestion = 0;
        foreach ($question->Answers as $answer) {
            if (($answer->UserAnswer == true) && ($answer->IsCorrect == true)) {
                $pointsForQuestion++;
            } else if (($answer->UserAnswer == true) && ($answer->IsCorrect == false)) {
                $pointsForQuestion = 0;
                break;
            }
        }
        $points += $pointsForQuestion;
    }
    return $points;
}
function getAllPosiblePoints($obj)
{
    $points = 0;
    foreach ($obj->Questions as $question) {
        foreach ($question->Answers as $answer) {
            if ($answer->IsCorrect == true) {
                $points++;
            }
        }
    }
    return $points;
}

?>

<style>
.mdc-form-field{
    display: flex;
    font-size: 13px;
}
body{
    background-color: #e6e6e6;
}
h2{
    font-size: 21px;
    margin: 4px;
    background-color: #bbb;
    padding: 8px;
    font-weight: bold;
    border-left: 4px solid #bbb;
    border-right: 4px solid #bbb;
}
.question{
    margin-left: 50px;
    margin-right: 50px;
    margin-bottom: 20px;
    border: 4px solid #bbbb;
    padding: 10px;
}
.imgcenter {
  text-align: center;
}
</style>