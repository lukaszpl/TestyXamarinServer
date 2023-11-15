<?php 
    require 'DatabaseHandler.php';
    //$token = $_POST["token"];
    //if($token == ""){
        $questionId = $_POST["QuestionId"];
        echo json_encode(DatabaseHandler::DeletePublicQuestion($questionId));
   // }

?>