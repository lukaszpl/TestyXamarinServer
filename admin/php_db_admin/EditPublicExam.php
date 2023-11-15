<?php 
    require 'DatabaseHandler.php';
    //$token = $_POST["token"];
    //if($token == ""){
        $id= $_POST["ExamId"];
        $examName= $_POST["ExamName"];
        $examInfo = $_POST["ExamInfo"];
        echo json_encode(DatabaseHandler::UpdatePublicExam($id, $examName, $examInfo));
   // }

?>