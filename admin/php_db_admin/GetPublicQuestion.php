<?php 
    require 'DatabaseHandler.php';
    //$token = $_POST["token"];
    //if($token == ""){
        $questionId = $_POST["questionId"];
        echo json_encode(DatabaseHandler::GetPublicQuestion($questionId));
   // }

?>