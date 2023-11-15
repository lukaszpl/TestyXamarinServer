<?php 
    require 'DatabaseHandler.php';
    //$token = $_POST["token"];
    //if($token == ""){
        $userId = $_POST["UserId"];
        echo json_encode(DatabaseHandler::GetUser($userId));
   // }

?>