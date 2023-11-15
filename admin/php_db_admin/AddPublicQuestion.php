<?php 
    require 'DatabaseHandler.php';
    //$token = $_POST["token"];
    //if($token == ""){
        $examId = $_POST["ExamId"];
        $question = $_POST["Question"];
        $base64Img = $_POST["Base64Img"];
        $answers = $_POST["Answers"];
        echo json_encode(DatabaseHandler::AddPublicQuestion($examId, $question, $base64Img, $answers));
   // }

?>