<?php 
    require 'DatabaseHandler.php';
    //$token = $_POST["token"];
    //if($token == ""){
        $userId = $_POST["UserId"];
        $examId = $_POST["ExamId"];
        echo json_encode(DatabaseHandler::AddActiveExam($userId, $examId));
   // }

?>