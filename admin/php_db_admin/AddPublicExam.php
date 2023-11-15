<?php 
    require 'DatabaseHandler.php';
    //$token = $_POST["token"];
    //if($token == ""){
        $examName= $_POST["ExamName"];
        $examInfo = $_POST["ExamInfo"];
        echo json_encode(DatabaseHandler::AddPublicExam($examName, $examInfo));
   // }

?>