<?php 
    require 'DatabaseHandler.php';
    //$token = $_POST["token"];
    //if($token == ""){
        echo json_encode(DatabaseHandler::GetAllActiveExams());
   // }

?>