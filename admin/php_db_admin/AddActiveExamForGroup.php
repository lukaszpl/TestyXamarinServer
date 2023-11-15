<?php 
    require 'DatabaseHandler.php';
    //$token = $_POST["token"];
    //if($token == ""){
        $groupId = $_POST["GroupId"];
        $examId = $_POST["ExamId"];
        echo json_encode(DatabaseHandler::AddActiveExamForGroup($groupId, $examId));
   // }

?>