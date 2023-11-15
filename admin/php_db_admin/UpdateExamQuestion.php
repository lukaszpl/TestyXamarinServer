<?php 
    require 'DatabaseHandler.php';
    //$token = $_POST["token"];
    //if($token == ""){
        $questionId = $_POST["QuestionId"];
        $question = $_POST["Question"];
        $base64Img = $_POST["Base64Img"];
        $answers = $_POST["Answers"];
        echo json_encode(DatabaseHandler::UpdateExamQuestion($questionId, $question, $base64Img, $answers));
   // }

?>