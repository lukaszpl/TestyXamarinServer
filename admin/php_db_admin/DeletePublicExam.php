<?php 
    require 'DatabaseHandler.php';
    //$token = $_POST["token"];
    //if($token == ""){
        $examId = $_POST["ExamId"];
        echo json_encode(DatabaseHandler::DeletePublicExam($examId));
   // }

?>